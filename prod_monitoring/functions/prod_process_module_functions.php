<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_process_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'process_name',
                1 => 'section_id'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT processid,process_name,section_name FROM prod_process_name
                INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_process_name.section_id";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT processid,process_name,section_name FROM prod_process_name
                INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_process_name.section_id WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND (process_name ILIKE '%" . $searchValue . "%' OR section_name ILIKE '%" . $searchValue . "%')";
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
                $nestedData = array();
                $nestedData[] = $row['process_name'];
                $nestedData[] = $row['section_name'];
                $nestedData[] = $row['processid'];
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

        case 'save_process':
            $process_name = trim($_POST['process_name']);
            $section_id = trim($_POST['section_id']);

            $chkExist = "SELECT * FROM prod_process_name WHERE process_name = :process_name AND section_id= :section_id";
            $chkExist_stmt = $prod->prepare($chkExist);
            $chkExist_stmt->bindParam(':process_name', $process_name);
            $chkExist_stmt->bindParam(':section_id', $section_id);
            $chkExist_stmt->execute();

            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO prod_process_name(process_name,section_id) 
                    VALUES(:process_name,:section_id)";
                $result_stmt = $prod->prepare($sqlstring);
                $result_stmt->bindParam(':process_name', $process_name);
                $result_stmt->bindParam(':section_id', $section_id);
                $result_stmt->execute();
            }
            $prod = null; //* ======== Close Connection ========
            break;

        case 'load_process_info':
            $processid = trim($_POST['processid']);

            $sqlstring = "SELECT * FROM prod_process_name WHERE processid = :processid";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':processid', $processid);
            $result_stmt->execute();

            $processData_List = array();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $processData_List['process_name'] = $row['process_name'];
                $processData_List['section_id'] = $row['section_id'];
            }
            echo json_encode($processData_List);
            $prod = null; //* ======== Close Connection ========
            break;

        case 'update_process':
            $processid = trim($_POST['processid']);
            $process_name = trim($_POST['process_name']);
            $section_id = trim($_POST['section_id']);

            $chkExist = "SELECT * FROM prod_process_name WHERE process_name = :process_name AND section_id = :section_id AND processid <> :processid";
            $chkExist_stmt = $prod->prepare($chkExist);
            $chkExist_stmt->bindParam(':processid', $processid);
            $chkExist_stmt->bindParam(':process_name', $process_name);
            $chkExist_stmt->bindParam(':section_id', $section_id);
            $chkExist_stmt->execute();

            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "UPDATE prod_process_name SET process_name = :process_name, section_id = :section_id WHERE processid = :processid";
                $result_stmt = $prod->prepare($sqlstring);
                $result_stmt->bindParam(':processid', $processid);
                $result_stmt->bindParam(':process_name', $process_name);
                $result_stmt->bindParam(':section_id', $section_id);
                $result_stmt->execute();
            }
            $prod = null; //* ======== Close Connection ========
            break;

        case 'remove_process':
            $processid = trim($_POST['processid']);

            $sqlstring = "DELETE FROM prod_process_name WHERE processid = :processid";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->bindParam(':processid', $processid);
            $result_stmt->execute();
            $prod = null; //* ======== Close Connection ========
            break;
    }
}
