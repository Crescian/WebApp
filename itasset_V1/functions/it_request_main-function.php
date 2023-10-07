<?php
include '../../configuration/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');
$ITR = $conn->db_conn_it_repair_request(); //* IT Repair Request Database connection

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadRequestCount':
            $stmt = $ITR->prepare("SELECT status, COUNT(*) AS count FROM tblit_request GROUP BY status");
            $stmt->execute();
            $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($stmt->rowCount()) foreach ($rowData as $row)  $data[$row['status']] = sprintf("%03d", $row['count']);
            $data ??= [null];
            echo json_encode($data);
            break;

        case 'loadTableRequest':
            //* =========== Variables ===========
            $search = $_POST['search']['value'];
            $statusVal = $_POST['statusVal'] ?? 'Pending';
            $columns = array(
                0 => 'queue_number',
                1 => 'requested_by',
                2 => 'date_requested',
                3 => 'request_type',
                4 => 'item',
                5 => 'description',
                6 => 'purpose',
            );

            //* =========== Fetch Total Record Data ===========
            $query = "SELECT * FROM tblit_request WHERE status = ? ";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$statusVal]);
            $totalRecord = $stmt->rowCount();

            //* =========== Fetch Total Filtered Record Data ===========
            if (!empty($search)) {
                $query .= "AND (queue_number ILIKE '%{$search}%'
                    OR requested_by ILIKE '%{$search}%'
                    OR request_type ILIKE '%{$search}%'
                    OR item ILIKE '%{$search}%'
                    OR description ILIKE '%{$search}%'
                    OR purpose ILIKE '%{$search}%'
                    OR TO_CHAR(date_requested, 'YYYY-MM-DD HH24:MI:Ss' ) ILIKE '%{$search}%') ";

                $stmt = $ITR->prepare($query);
                $stmt->execute([$statusVal]);
            }
            $totalFilteredRecord = $stmt->rowCount();

            //* ======== Ordering ========
            $query .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$statusVal]);
            $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            //* ======== Record Data Array ========
            foreach ($rowData as $row) {
                $data[] = array(
                    $row['queue_number'],
                    $row['requested_by'],
                    $row['date_requested'],
                    $row['request_type'],
                    $row['item'],
                    $row['description'],
                    $row['purpose'],
                    [
                        "status" => $row['status'],
                        "sender" => $row['queue_number'],
                        "id" => $row['request_id']
                    ]
                );
            }

            //* ====== Output Data Array ======
            $json = array(
                "draw"                    =>  intval($_POST["draw"]),
                "iTotalRecords"           =>  $totalRecord,
                "iTotalDisplayRecords"    =>  $totalFilteredRecord,
                "data"                    =>  $data
            );

            //* ====== Return Data as JSON Format ======
            echo json_encode($json);
            $ITR = null;
            break;

        case 'details':
            $id = $_POST['id'];
            $query = "SELECT * FROM tblit_request WHERE request_id = ?";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'cancel':  //* Cancel Request
            $id = $_POST['id'];
            $query = "UPDATE tblit_request SET status = 'Cancelled' WHERE request_id = ?";
            $stmt = $ITR->prepare($query)->execute([$id]);
            break;

        case 'reapprove':  //* Reapprove Request
            $id = $_POST['id'];
            $query = "UPDATE tblit_request SET status = 'Pending' WHERE request_id = ?";
            $stmt = $ITR->prepare($query)->execute([$id]);
            break;

        case 'process': //* Process Request
            $id = $_POST['id'];
            $query = "UPDATE tblit_request SET status = 'Ongoing' WHERE request_id = ?";
            $stmt = $ITR->prepare($query)->execute([$id]);
            break;

        case 'accomplish':  //* Accomplish Request
            $id = $_POST['id'];
            $sender = $_POST['sender'];

            $query = "UPDATE tblit_request SET status = 'Done' WHERE request_id = ?";
            $stmt = $ITR->prepare($query)->execute([$id]);

            $query = "DELETE FROM tblit_conversation WHERE sender = ?";
            $stmt = $ITR->prepare($query)->execute([$sender]);
            break;
    }
}
