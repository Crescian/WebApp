<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_machine_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'machine_name'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT machineid,machine_name FROM prod_machine_name";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT machineid,machine_name FROM prod_machine_name WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND machine_name ILIKE '%" . $searchValue . "%'";
            }
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $btnAction = '<button type="button" class="btn col-sm-6 btn-primary btnEditMachine" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Machine" onclick="modifyMachine(\'' . $row["machineid"] . '\');"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                    <button type="button" class="btn col-sm-6 btn-danger btnDeleteMachine" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Machine" onclick="deleteMachine(\'' . $row["machineid"] . '\');"><i class="fa-solid fa-trash-can fa-shake"></i></button>';

                $nestedData = array();
                $nestedData[] = $row['machine_name'];
                $nestedData[] = $btnAction;
                $resultData_List[] = $nestedData;
            }
            //* ======== Output Data ========
            $output = array(
                'draw'                  =>  intval($_POST['draw']),
                'iTotalRecords'         =>  $result_total_record,
                'iTotalDisplayRecords'  =>  $result_total_record_filtered,
                'data'                  =>  $resultData_List
            );
            //* ======== Send Data as JSON Format ========
            echo json_encode($output);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'save_machine':
            $machine_name = trim($_POST['machine_name']);

            $result_chk_existing_sql = "SELECT * FROM prod_machine_name WHERE machine_name = :machine_name";
            $result_chk_existing_stmt = $prod->prepare($result_chk_existing_sql);
            $result_chk_existing_stmt->bindParam(':machine_name', $machine_name);
            $result_chk_existing_stmt->execute();

            if ($result_chk_existing_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO prod_machine_name(machine_name) VALUES(:machine_name)";
                $result_stmt = $prod->prepare($sqlstring);
                $result_stmt->bindParam(':machine_name', $machine_name);
                $result_stmt->execute();
            }
            $prod = null; //* ======== Close Connection ========
            break;

        case 'load_machine_information':
            $machineid = trim($_POST['machineid']);

            $result_sql = "SELECT * FROM prod_machine_name WHERE machineid = :machineid";
            $result_stmt = $prod->prepare($result_sql);
            $result_stmt->bindParam(':machineid', $machineid);
            $result_stmt->execute();

            $resultData_List = array();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultData_List['machine_name'] = $row['machine_name'];
            }
            echo json_encode($resultData_List);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'update_machine':
            $machineid = trim($_POST['machineid']);
            $machine_name = trim($_POST['machine_name']);

            $result_chk_existing_sql = "SELECT * FROM prod_machine_name WHERE machine_name = :machine_name AND machineid <> :machineid";
            $result_chk_existing_stmt = $prod->prepare($result_chk_existing_sql);
            $result_chk_existing_stmt->bindParam(':machineid', $machineid);
            $result_chk_existing_stmt->bindParam(':machine_name', $machine_name);
            $result_chk_existing_stmt->execute();

            if ($result_chk_existing_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $result_sql = "UPDATE prod_machine_name SET machine_name = :machine_name WHERE machineid = :machineid";
                $result_stmt = $prod->prepare($result_sql);
                $result_stmt->bindParam(':machineid', $machineid);
                $result_stmt->bindParam(':machine_name', $machine_name);
                $result_stmt->execute();
            }
            $prod = null; //* ======== Close Connection ========
            break;

        case 'delete_machine_name':
            $machineid = trim($_POST['machineid']);

            $sqlstring = "DELETE FROM prod_machine_name WHERE machineid = :machineid";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':machineid', $machineid);
            $result_stmt->execute();
            $prod = null; //* ======== Close Connection ========
            break;
    }
}
