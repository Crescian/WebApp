<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';

    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_section_table_data':
            //* ======== Read Data ========            
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'section_name'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT sectionpersoid,section_name FROM bpi_section_perso";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT sectionpersoid,section_name FROM bpi_section_perso WHERE 1 = 1";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND section_name ILIKE '%" . $searchValue . "%'";
            }
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $btnAction = '<button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Section" onclick="editSection(\'' . $row["sectionpersoid"] . '\');"><i class="fa-solid fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Section" onclick="deleteSection(\'' . $row["sectionpersoid"] . '\');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>';
                $resultData_List[] = array($row['section_name'], $btnAction);
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
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_assign_section_table_data':
            //* ======== Read Data ========            
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'sec_job_title',
                1 => 'section_name'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT assignsectionid,pos_name,section_name FROM bpi_assigned_section 
                INNER JOIN bpi_section_perso ON bpi_section_perso.sectionpersoid = bpi_assigned_section.sectionperso_id  
                INNER JOIN prl_position ON prl_position.pos_code = bpi_assigned_section.sec_job_title";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT assignsectionid,pos_name,section_name FROM bpi_assigned_section 
                INNER JOIN bpi_section_perso ON bpi_section_perso.sectionpersoid = bpi_assigned_section.sectionperso_id  
                INNER JOIN prl_position ON prl_position.pos_code = bpi_assigned_section.sec_job_title 
                WHERE 1 = 1";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND pos_name ILIKE '%" . $searchValue . "%' OR section_name ILIKE '%" . $searchValue . "%'";
            }
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $btnAction = '<button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Assign" onclick="editAssignSection(\'' . $row["assignsectionid"] . '\');"><i class="fa-solid fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Assign" onclick="deleteAssignSection(\'' . $row["assignsectionid"] . '\');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>';
                $resultData_List[] = array($row['pos_name'], $row['section_name'], $btnAction);
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
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_select_values':
            $inFieldId = trim($_POST['inFieldId']);
            $inField = trim($_POST['inField']);
            $inTable = trim($_POST['inTable']);

            $sqlstring = "SELECT " . $inField . " ," . $inFieldId . " FROM " . $inTable . " ORDER BY " . $inField . " ASC";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_row = $result_stmt->fetchAll();
            echo '<option value="">Choose...</option>';
            foreach ($result_row as $row) {
                echo '<option value="' . $row[$inFieldId] . '">' . $row[$inField] . '</option>';
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'save_section':
            $section_name = trim($_POST['section_name']);

            $sqlChkExist = "SELECT * FROM bpi_section_perso WHERE section_name = :section_name";
            $chkExist_stmt = $BannerWebLive->prepare($sqlChkExist);
            $chkExist_stmt->bindParam(':section_name', $section_name);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_section_perso(section_name) VALUES(:section_name)";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':section_name', $section_name);
                $result_stmt->execute();
                echo 'save';
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'save_assign_section':
            $assign_section_name = trim($_POST['assign_section_name']);
            $section_job_title = trim($_POST['section_job_title']);

            $sqlChkExist = "SELECT * FROM bpi_assigned_section WHERE sec_job_title = :section_job_title AND sectionperso_id = :assign_section_name";
            $chkExist_stmt = $BannerWebLive->prepare($sqlChkExist);
            $chkExist_stmt->bindParam(':assign_section_name', $assign_section_name);
            $chkExist_stmt->bindParam(':section_job_title', $section_job_title);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_assigned_section(sec_job_title,sectionperso_id) VALUES(:section_job_title,:assign_section_name)";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':assign_section_name', $assign_section_name);
                $result_stmt->bindParam(':section_job_title', $section_job_title);
                $result_stmt->execute();
                echo 'save';
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;
    }
}
