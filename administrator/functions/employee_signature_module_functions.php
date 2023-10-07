<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    //* Banner Web Database connection
    $BannerWebLive = $conn->db_conn_bannerweb();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_employee_signature_table':
            //* ======== Read Data ========  
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'emp_name'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT empsignatureid, emp_name FROM bpi_employee_signature";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT empsignatureid, emp_name FROM bpi_employee_signature
                WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND (emp_name ILIKE '%" . $searchValue . "%')";
            }
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= "ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $btnAction = '<button type="button" class="btn btn-primary col-sm-6" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview Signature" onclick="btnPreviewSignature(\'' . $row['empsignatureid']  . '\');"><i class="fa-regular fa-pen-to-square"></i></button>
                    <button type="button" class="btn btn-danger col-sm-6" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Signature" onclick="btnDeleteSignature(\'' .  $row['empsignatureid']   . '\');"><i class="fa-solid fa-trash-can"></i></i></button>';

                $nestedData = array();
                $nestedData[] = $row['emp_name'];
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
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_department':
            $sqlstring = "SELECT * FROM bpi_department ORDER BY department ASC";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<option value="' . $row['dept_code'] . '">' . $row['department'] . '</option>';
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_employee':
            $dept_code = trim($_POST['dept_code']);
            $sqlstring = "SELECT empno,(emp_fn || ' ' || emp_sn) AS emp_name FROM prl_employee WHERE dept_code = :dept_code ORDER BY emp_name ASC";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':dept_code', $dept_code);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<option value="' . $row['emp_name'] . '">' . $row['emp_name'] . '</option>';
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'save_employee_signature':
            $sign_employee = trim($_POST['sign_employee']);
            $dept_code = trim($_POST['dept_code']);
            $employee_signature_image = trim($_POST['employee_signature_image']);
            $dateCreated = date('Y-m-d H:m:s');

            $chkExist = "SELECT * FROM bpi_employee_signature WHERE emp_name = :emp_name";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':emp_name', $sign_employee);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_employee_signature(emp_name,employee_signature,date_created,dept_code) 
                VALUES(:sign_employee,:employee_signature_image,:datetime_today,:dept_code)";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':sign_employee', $sign_employee);
                $result_stmt->bindParam(':dept_code', $dept_code);
                $result_stmt->bindParam(':employee_signature_image', $employee_signature_image);
                $result_stmt->bindParam(':datetime_today', $dateCreated);
                $result_stmt->execute();
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'preview_employee_signature':
            $empsignatureid = trim($_POST['empsignatureid']);
            $resultData_List = array();

            $sqlstring = "SELECT emp_name, dept_code,encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature
                WHERE empsignatureid = :empsignatureid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':empsignatureid', $empsignatureid);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $resultData_List['emp_name'] = $row['emp_name'];
                $resultData_List['dept_code'] = $row['dept_code'];
                $resultData_List['employee_signature'] = '<img src="data:image/jpeg;base64,' . $row['employee_signature'] . '" value="' . $row['employee_signature'] . '" id="employee_signature_image">';
            }
            echo json_encode($resultData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'update_employee_signature':
            $empsignatureid = trim($_POST['empsignatureid']);
            $sign_employee = trim($_POST['sign_employee']);
            $dept_code = trim($_POST['dept_code']);
            $employee_signature_image = trim($_POST['employee_signature_image']);
            $dateCreated = date('Y-m-d H:m:s');

            $chkExist = "SELECT * FROM bpi_employee_signature WHERE emp_name = :emp_name AND empsignatureid <> :empsignatureid";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':empsignatureid', $empsignatureid);
            $chkExist_stmt->bindParam(':emp_name', $sign_employee);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "UPDATE bpi_employee_signature SET emp_name = :emp_name, employee_signature = :employee_signature, date_created = :dateCreated, dept_code = :dept_code
                    WHERE empsignatureid = :empsignatureid";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':empsignatureid', $empsignatureid);
                $result_stmt->bindParam(':emp_name', $sign_employee);
                $result_stmt->bindParam(':dept_code', $dept_code);
                $result_stmt->bindParam(':employee_signature', $employee_signature_image);
                $result_stmt->bindParam(':dateCreated', $dateCreated);
                $result_stmt->execute();
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'delete_employee_signature':
            $empsignatureid = trim($_POST['empsignatureid']);

            $sqlstring = "DELETE FROM bpi_employee_signature WHERE empsignatureid = :empsignatureid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':empsignatureid', $empsignatureid);
            $result_stmt->execute();
            $BannerWebLive = null; //* ======== Close Connection ========
            break;
    }
}
