<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $BannerWeb = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
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
                0 => 'process_name'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT processid,process_name FROM bpi_perso_process_list";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT processid,process_name FROM bpi_perso_process_list WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND process_name ILIKE '%" . $searchValue . "%'";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $nestedData = array();
                $nestedData[] = $row['process_name'];
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
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_process_section':
            $sqlstring = "SELECT * FROM bpi_section_perso";
            $result_stmt = $BannerWeb->prepare($sqlstring);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                echo '<option value="' . $row['section_name'] . '">' . $row['section_name'] . '</option>';
            }
            $BannerWeb = null; //* ======== Close Connection ========
            break;

        case 'save_process':
            $strProcessName = trim($_POST['strProcessName']);
            $strProcessDivision = trim($_POST['strProcessDivision']);
            $strProcessSection = trim($_POST['strProcessSection']);
            $strProcessCategory = trim($_POST['strProcessCategory']);

            $result_chk_existing_sql = "SELECT * FROM bpi_perso_process_list WHERE process_name = :processname";
            $result_chk_existing_stmt = $perso->prepare($result_chk_existing_sql);
            $result_chk_existing_stmt->bindParam(':processname', $strProcessName);
            $result_chk_existing_stmt->execute();

            if ($result_chk_existing_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $result_sql = "INSERT INTO bpi_perso_process_list(process_name,process_section,process_division,process_category) 
                    VALUES(:processname,:process_section,:process_division,:process_category)";
                $result_stmt = $perso->prepare($result_sql);
                $result_stmt->bindParam(':processname', $strProcessName);
                $result_stmt->bindParam(':process_division', $strProcessDivision);
                $result_stmt->bindParam(':process_section', $strProcessSection);
                $result_stmt->bindParam(':process_category', $strProcessCategory);
                $result_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_process_information':
            $processid = trim($_POST['processid']);

            $result_sql = "SELECT * FROM bpi_perso_process_list WHERE processid = :processid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':processid', $processid);
            $result_stmt->execute();

            $processData_List = array();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $processData_List['processname'] = $row['process_name'];
                $processData_List['process_division'] = $row['process_division'];
                $processData_List['process_section'] = $row['process_section'];
                $processData_List['process_category'] = $row['process_category'];
            }
            echo json_encode($processData_List);
            $perso = null; //* ======== Close Connection ========
            break;

        case 'update_process':
            $processid = trim($_POST['processid']);
            $strModProcessName = trim($_POST['strModProcessName']);
            $strModProcessDivision = trim($_POST['strModProcessDivision']);
            $strModProcessSection = trim($_POST['strModProcessSection']);
            $strModProcessCategory = trim($_POST['strModProcessCategory']);

            $result_chk_existing_sql = "SELECT * FROM bpi_perso_process_list WHERE process_name = :processname AND processid <> :processid";
            $result_chk_existing_stmt = $perso->prepare($result_chk_existing_sql);
            $result_chk_existing_stmt->bindParam(':processid', $processid);
            $result_chk_existing_stmt->bindParam(':processname', $strModProcessName);
            $result_chk_existing_stmt->execute();

            if ($result_chk_existing_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $result_sql = "UPDATE bpi_perso_process_list SET process_name = :processname, process_division = :process_division, process_section = :process_section ,process_category = :process_category 
                    WHERE processid = :processid";
                $result_stmt = $perso->prepare($result_sql);
                $result_stmt->bindParam(':processid', $processid);
                $result_stmt->bindParam(':processname', $strModProcessName);
                $result_stmt->bindParam(':process_division', $strModProcessDivision);
                $result_stmt->bindParam(':process_section', $strModProcessSection);
                $result_stmt->bindParam(':process_category', $strModProcessCategory);
                $result_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'delete_process_name':
            $processid = trim($_POST['processid']);

            $result_sql = "DELETE FROM bpi_perso_process_list WHERE processid = :processid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':processid', $processid);
            $result_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;
    }
}
