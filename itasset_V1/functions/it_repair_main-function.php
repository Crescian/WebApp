<?php
include '../../configuration/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');
$ITR = $conn->db_conn_it_repair_request(); //* IT Repair Request Database connection

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadRepairCount':
            $stmt = $ITR->prepare("SELECT status, COUNT(*) AS count FROM tblit_repair GROUP BY status");
            $stmt->execute();
            $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($stmt->rowCount()) foreach ($rowData as $row)  $data[$row['status']] = sprintf("%03d", $row['count']);
            $data ??= [null];
            echo json_encode($data);
            $ITR = null;
            break;

        case 'loadTableRepair':
            //* =========== Variables ===========
            $search = $_POST['search']['value'];
            $statusVal = $_POST['statusVal'] ?? 'On Hold';
            $columns = array(
                0 => 'queue_number',
                1 => 'item',
                2 => 'remarks',
                3 => 'location',
                4 => 'requested_by',
                5 => 'date_requested',
            );

            //* =========== Fetch Total Record Data ===========
            $query = "SELECT * FROM tblit_repair WHERE status = ? ";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$statusVal]);
            $totalRecord = $stmt->rowCount();

            //* =========== Fetch Total Filtered Record Data ===========
            if (!empty($search)) {
                $query .= "AND (queue_number ILIKE '%{$search}%'
                    OR item ILIKE '%{$search}%'
                    OR remarks ILIKE '%{$search}%'
                    OR location ILIKE '%{$search}%'
                    OR requested_by ILIKE '%{$search}%'
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
                    $row['item'],
                    $row['remarks'],
                    $row['location'],
                    $row['requested_by'],
                    $row['date_requested'],
                    [
                        "status" => $row['status'],
                        "sender" => $row['queue_number'],
                        "id" => $row['repair_id']
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
            $query = "SELECT * FROM tblit_repair WHERE repair_id = ?";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            $ITR = null;
            break;

        case 'acknowledge':
            $id = $_POST['id'];
            $priority = $_POST['priority'];

            $query = "UPDATE tblit_repair SET status = 'Pending', datetime_acknowledge = LOCALTIMESTAMP(0), acknowledged_by = ?, priority = ? WHERE repair_id = ?";
            $stmt = $ITR->prepare($query)->execute([$_SESSION['fullname'], $priority, $id]);
            $ITR = null;
            break;

        case 'cancel':
            $id = $_POST['id'];
            $query = "UPDATE tblit_repair SET status = 'Cancelled' WHERE repair_id = ?";
            $stmt = $ITR->prepare($query)->execute([$id]);
            $ITR = null;
            break;

        case 'repair':
            $id = $_POST['id'];
            $query = "UPDATE tblit_repair SET status = 'Ongoing', datetime_repair = LOCALTIMESTAMP(0) WHERE repair_id = ?";
            $stmt = $ITR->prepare($query)->execute([$id]);
            $ITR = null;
            break;

        case 'accomplish':
            $actionTaken = trim($_POST['action_taken']);
            $id = $_POST['id'];
            $sender = $_POST['sender'];

            $query = "UPDATE tblit_repair SET status = 'Done', datetime_accomplish = LOCALTIMESTAMP(0), repaired_by = ?, action_taken = ? WHERE repair_id = ?";
            $stmt = $ITR->prepare($query)->execute([$_SESSION['fullname'], $actionTaken, $id]);

            $query = "DELETE FROM tblit_conversation WHERE sender = ?";
            $stmt = $ITR->prepare($query)->execute([$sender]);
            $ITR = null;
            break;
    }
}
