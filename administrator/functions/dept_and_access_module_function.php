<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    //* Banner Web Database connection
    $BannerWebLive = $conn->db_conn_bannerweb();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');

    switch ($action) {
        case 'loadDeptTable':
            ## Read Data
            $searchValue = $_POST['search']['value']; // Search value

            $col = array(
                0 => 'dept_id',
                1 => 'department'
            );
            ### TOTAL RECORD ###
            $sqlstring = "SELECT * FROM bpi_department";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            ## Fetch filtered Record
            $sqlstring = "SELECT * FROM bpi_department WHERE 1 = 1 ";
            ## Search 
            if (!empty($searchValue)) {
                $sqlstring .= "AND (department ILIKE '%" . $searchValue . "%')";
            }
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            ## ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();

            $resultData_List = array();

            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $dept_id = $row['dept_id'];
                $btnAction = '<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeptDelete(\'' . $dept_id . '\');"><i class="fa-solid fa-trash-can"></i></button>';
                $nestedData = array();
                $nestedData[] = $row['department'];
                $nestedData[] = $btnAction;
                $resultData_List[] = $nestedData;
            }
            ## Output Data
            $output = array(
                "draw"                   =>  intval($_POST["draw"]),
                'iTotalRecords'          =>  $result_total_record,
                'iTotalDisplayRecords'   =>  $result_total_record_filtered,
                'data'                   =>  $resultData_List
            );
            ## Send Data as JSON Format
            echo json_encode($output);
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
        case 'loadJobTitleTable':
            ## Read Data
            $searchValue = $_POST['search']['value']; // Search value

            $col = array(
                0 => 'title_id',
                1 => 'job_title'
            );
            ### TOTAL RECORD ###
            $sqlstring = "SELECT * FROM bpi_emp_job_title";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            ## Fetch filtered Record
            $sqlstring = "SELECT * FROM bpi_emp_job_title WHERE 1 = 1 ";
            ## Search 
            if (!empty($searchValue)) {
                $sqlstring .= "AND (job_title ILIKE '%" . $searchValue . "%')";
            }
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            ## ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();

            $resultData_List = array();

            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $title_id = $row['title_id'];
                $btnAction = '<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnAccessDelete(\'' . $title_id . '\');"><i class="fa-solid fa-trash-can"></i></button>';
                $nestedData = array();
                $nestedData[] = $row['job_title'];
                $nestedData[] = $btnAction;
                $resultData_List[] = $nestedData;
            }
            ## Output Data
            $output = array(
                "draw"                   =>  intval($_POST["draw"]),
                'iTotalRecords'          =>  $result_total_record,
                'iTotalDisplayRecords'   =>  $result_total_record_filtered,
                'data'                   =>  $resultData_List
            );
            ## Send Data as JSON Format
            echo json_encode($output);
            ## CLOSE CONNECTION
            $BannerWebLive = null;
            break;
        case 'btnDeptSaveFunction':
            $department_input = trim($_POST['department_input']);
            $dept_code = trim($_POST['dept_code']);
            $sqlstring = "SELECT * FROM bpi_department WHERE department = '" . $department_input . "'";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            if ($result_stmt->rowCount() > 0) {
                echo 'Department Exist';
            } else {
                $sqlstring = "INSERT INTO bpi_department(department,dept_code)VALUES('" . $department_input . "','" . $dept_code . "')";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->execute();
                echo 'successfully save';
            }
            break;
        case 'btnDeptAccessFunction':
            $dept = trim($_POST['dept']);
            $access_input = trim($_POST['access_input']);
            $sqlstring = "SELECT * FROM bpi_emp_job_title WHERE job_title = '" . $access_input . "'";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            if ($result_stmt->rowCount() > 0) {
                echo 'Access Level Exist';
            } else {
                $sqlstring = "INSERT INTO bpi_emp_job_title(job_title,department_id)VALUES('" . $access_input . "','" . $dept . "')";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->execute();
                echo 'successfully access';
            }
            break;
        case 'deleteFunction':
            $id = trim($_POST['id']);
            $btnDeptAccessDelete = trim($_POST['btnDeptAccessDelete']);
            switch ($btnDeptAccessDelete) {
                case 'btnDeptDelete':
                    $sqlstring = "DELETE FROM bpi_department WHERE dept_id = '" . $id . "'";
                    $result_stmt = $BannerWebLive->prepare($sqlstring);
                    $result_stmt->execute();
                    break;

                case 'btnAccessDelete':
                    $sqlstring = "DELETE FROM bpi_emp_job_title WHERE title_id = '" . $id . "'";
                    $result_stmt = $BannerWebLive->prepare($sqlstring);
                    $result_stmt->execute();
                    break;
            }
            break;
        case 'loadDept':
            $sqlstring = "SELECT * FROM bpi_department ORDER BY department ASC";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_res = $result_stmt->fetchAll();
            echo '<option value="">Choose...</option>';
            foreach ($result_res as $row) {
                echo '<option value="' . $row['dept_code'] . '">' . $row['department'] . '</option>';
            }
            break;
    }
}
