<?php
include './../includes/connection.php';
date_default_timezone_set('Asia/Manila');

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);
    $date = date('Y-m-d');

    switch ($action) {

        case 'loadTableAssignCPU':

            $search_value = $_POST['search']['value'];
            ## ====== Create Array for column same with column names on database for ordering ======
            $columns = array(
                0 => 'employee',
                1 => 'cpu_control_no',
                2 => 'description',
                3 => 'location',
                4 => 'switch_tag',
                5 => 'lan_cable_tag',
                6 => 'ip_address',
                7 => 'date_updated',
            );

            ## ====== Fetch Total Record Data ======
            $sqlstring = "SELECT * FROM tblit_cpu_control_no WHERE active_pc = 'True' ";
            $result_stmt = $ITA->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();

            ## ======== Fetch Total Filtered Record Data ========
            if (!empty($search_value)) {
                $sqlstring .= "AND (employee ILIKE '%{$search_value}%'
                    OR cpu_control_no ILIKE '%{$search_value}%'
                    OR description ILIKE '%{$search_value}%'
                    OR location ILIKE '%{$search_value}%'
                    OR switch_tag ILIKE '%{$search_value}%'
                    OR lan_cable_tag ILIKE '%{$search_value}%'
                    OR ip_address ILIKE '%{$search_value}%'
                    OR TO_CHAR(date_updated, 'YYYY-MM-DD') ILIKE '%{$search_value}%') ";
            }
            $result_stmt = $ITA->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            ## ======== Ordering ========
            $sqlstring .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
            $result_stmt = $ITA->prepare($sqlstring);
            $result_stmt->execute();

            ## ======== Preparing an array ========
            $data = array();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $data_row = array();
                $data_row[] = $row['employee'];
                $data_row[] = $row['cpu_control_no'];
                $data_row[] = $row['description'];
                $data_row[] = $row['location'];
                $data_row[] = $row['switch_tag'];
                $data_row[] = $row['lan_cable_tag'];
                $data_row[] = $row['ip_address'];
                $data_row[] = $row['date_updated'];
                $data_row[] = '<div class="btn-group">
                                <button class="btn btn-outline-primary" onclick="updateAssignCPU();" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-outline-danger" onclick="deleteAssignCPU();" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="fa-solid fa-trash"></i></button>
                                <button class="btn btn-outline-success" onclick="printAssignCPU();" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print"><i class="fa-solid fa-print"></i></button>
                            </div>';
                $data[] = $data_row;
            }

            ## ====== Output Data Array ======
            $json = array(
                "draw"                    =>  intval($_POST["draw"]),
                "iTotalRecords"           =>  $result_total_record,
                "iTotalDisplayRecords"    =>  $result_total_record_filtered,
                "data"                    =>  $data
            );

            ## ====== Send Data as JSON Format ======
            echo json_encode($json);
            $ITA = null;
            break;
    }
}