<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    //* Banner Web Database connection
    $BannerWebLive = $conn->db_conn_bannerweb();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    function loadColoredJobPosition($dept_code, $div_code, $access_level)
    {
        switch ($dept_code) {
                //* Operations Group
            case 'ITD':
                $loadColoredJobPosition = '<span class="badge bg-red col-sm-12">' . $access_level . '</span>';
                break;
            case 'ISD':
                $loadColoredJobPosition = '<span class="badge date-color14 col-sm-12">' . $access_level . '</span>';
                break;
            case 'PRD':
                switch ($div_code) {
                    case 'ADS':
                    case 'ADM':
                        $dept_color = 'date-color4';
                        break;
                    case 'MAN':
                        $dept_color = 'bg-blue';
                        break;
                    case 'PER':
                        $dept_color = 'bg-cyan';
                        break;
                    case 'MAC':
                        $dept_color = 'date-color1';
                        break;
                }
                $loadColoredJobPosition = '<span class="badge ' . $dept_color . ' col-sm-12">' . $access_level . '</span>';
                break;
            case 'FMD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $access_level . '</span>';
                break;
                //* Innovation & Management Systems Group
            case 'RDD':
                $loadColoredJobPosition = '<span class="badge bg-yellow col-sm-12">' . $access_level . '</span>';
                break;
            case 'MSD':
                $loadColoredJobPosition = '<span class="badge bg-yellow col-sm-12">' . $access_level . '</span>';
                break;
            case 'PHD':
                $loadColoredJobPosition = '<span class="badge bg-yellow col-sm-12">' . $access_level . '</span>';
                break;
                //* Strategic Support Service Group
            case 'FID':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $access_level . '</span>';
                break;
            case 'HRD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $access_level . '</span>';
                break;
            case 'BDD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $access_level . '</span>';
                break;
            case 'SMD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $access_level . '</span>';
                break;
            case 'PSD':
                $loadColoredJobPosition = '<span class="badge bg-success col-sm-12">' . $access_level . '</span>';
                break;
        }
        return $loadColoredJobPosition;
    }

    switch ($action) {
        case 'load_user_list_table':
            //* ======== Read Data ========  
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'empno',
                1 => 'fullname',
                2 => 'username',
                3 => 'access_lvl',
                4 => 'is_logged_in',
                5 => 'is_active',
                6 => 'forgot_password',
                7 => 'act_lockedout'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT user_id,prl_employee.empno,(emp_fn || ' ' || emp_sn) AS fullname,username,pos_name,dept_code,div_code,is_logged_in,is_active,forgot_password,act_lockedout FROM bpi_user_accounts
                INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
                INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT user_id,prl_employee.empno,(emp_fn || ' ' || emp_sn) AS fullname,username,pos_name,dept_code,div_code,is_logged_in,is_active,forgot_password,act_lockedout FROM bpi_user_accounts
                INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
                INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND (prl_employee.empno ILIKE '%" . $searchValue . "%' OR emp_fn ILIKE '%" . $searchValue . "%' OR emp_sn ILIKE '%" . $searchValue . "%' OR username ILIKE '%" . $searchValue . "%' OR pos_name ILIKE '%" . $searchValue . "%') ";
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
                $job_position = loadColoredJobPosition($row['dept_code'], $row['div_code'], $row['pos_name']); //* ======== Set Job Position Colored ========
                $isLoggedIn = $row['is_logged_in'] == 1 ? '<span class="badge bg-success col-sm-12">Logged In</span>' : '<span class="badge bg-danger col-sm-12">Logged Out</span>';
                $forgotPass = $row['forgot_password'] == 1 ? '<span class="badge bg-success col-sm-12">Yes</span>' : '<span class="badge bg-danger col-sm-12">No</span>';
                $btnAction = '<button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnUserPreview(\'' . $row['user_id']  . '\');"><i class="fa-regular fa-pen-to-square"></i></button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reset Password" onclick="btnUserReset(\'' .  $row['user_id']   . '\',\'' . $row['fullname'] . '\');"><i class="fa-solid fa-arrows-rotate"></i></i></button>';
                if ($row['is_active'] == 1) {
                    $isActive = '<span class="badge bg-success col-sm-12">Active</span>';
                    $btnAction .= ' <button type="button" class="btn btn-danger btnDisableCss" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Disable Account" onclick="btnDisable(\'' . $row['user_id'] . '\',\'' . $row['is_active'] . '\',\'' . $row['fullname'] . '\');"><i class="fa-solid fa-user-large-slash"></i></button>';
                } else if ($row['is_active'] == 0) {
                    $isActive = '<span class="badge bg-danger col-sm-12">Inactive</span>';
                    $btnAction .= ' <button type="button" class="btn btn-success btnDisableCss" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Enable Account" onclick="btnDisable(\'' . $row['user_id'] . '\',\'' . $row['is_active'] . '\',\'' . $row['fullname'] . '\');"><i class="fa-solid fa-user-large"></i></button>';
                }
                if ($row['act_lockedout'] == 1) {
                    $act_lockedout = '<span class="badge bg-success col-sm-12"> Yes </span>';
                    $btnAction .= ' <button type="button" class="btn btn-success btnLockoutCss" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unlock Account" onclick="btnLockout(\'' . $row['user_id'] . '\',\'' . $row['act_lockedout'] . '\',\'' . $row['fullname'] . '\');"><i class="fa-solid fa-unlock"></i></button> ';
                } else {
                    $act_lockedout = '<span class="badge bg-danger col-sm-12"> No </span>';
                    $btnAction .= ' <button type="button" class="btn btn-danger btnLockoutCss" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Lock Account" onclick="btnLockout(\'' . $row['user_id'] . '\',\'' . $row['act_lockedout'] . '\',\'' . $row['fullname'] . '\');"><i class="fa-solid fa-user-lock"></i></button>';
                }
                $btnAction .= ' <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete User" onclick="btnUserDelete(\'' . $row['user_id'] . '\',\'' . $row['fullname'] . '\');"><i class="fa-solid fa-trash-can"></i></button>
                    <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="User Info" onclick="btnUserInfo(\'' . $row['user_id'] . '\');"><i class="fa-solid fa-circle-info"></i></button>';

                $nestedData = array();
                $nestedData[] = $row['empno'];
                $nestedData[] = $row['fullname'];
                $nestedData[] = $row['username'];
                $nestedData[] = $job_position;
                $nestedData[] = $isLoggedIn;
                $nestedData[] = $isActive;
                $nestedData[] = $forgotPass;
                $nestedData[] = $act_lockedout;
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

        case 'load_access_level':
            $sqlstring = "SELECT * FROM prl_position";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<option value="' . $row['pos_code'] . '">' . $row['pos_name'] . '</option>';
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
                echo '<option value="' . $row['empno'] . '">' . $row['emp_name'] . '</option>';
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_employee_details':
            $empno = trim($_POST['empno']);
            $itemData_List = array();

            $sqlstring = "SELECT prl_position.pos_code FROM prl_employee
                INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
                WHERE empno = :empno";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':empno', $empno);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List['pos_code'] = $row['pos_code'];
            }
            echo json_encode($itemData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'save_user_account':
            $user_department = trim($_POST['user_department']);
            $user_username = trim($_POST['user_username']);
            $user_status = trim($_POST['user_status']);
            $user_empno = trim($_POST['user_empno']);
            $user_access = trim($_POST['user_access']);
            $user_password = trim($_POST['user_password']);
            $authorize_courier_image = trim($_POST['authorize_courier_image']);
            $dateCreated = date('Y-m-d H:m:s');

            $chkExist = "SELECT * FROM bpi_user_accounts WHERE empno = :empno";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':empno', $user_empno);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $chkUsernameExist = "SELECT * FROM bpi_user_accounts WHERE username = :username";
                $chkUsernameExist_stmt = $BannerWebLive->prepare($chkUsernameExist);
                $chkUsernameExist_stmt->bindParam(':username', $user_username);
                $chkUsernameExist_stmt->execute();
                if ($chkUsernameExist_stmt->rowCount() > 0) {
                    echo 'existing username';
                } else {
                    $sqlstring = "INSERT INTO bpi_user_accounts(empno,username,userpass,access_lvl,is_active,user_image,department,pass_created_date,account_date_created) 
                    VALUES(:user_empno,:user_username,md5(:user_password),:user_access,:user_status,:authorize_courier_image,:user_department,:datetime_today,:datetime_today)";
                    $result_stmt = $BannerWebLive->prepare($sqlstring);
                    $result_stmt->bindParam(':user_department', $user_department);
                    $result_stmt->bindParam(':user_username', $user_username);
                    $result_stmt->bindParam(':user_status', $user_status);
                    $result_stmt->bindParam(':user_empno', $user_empno);
                    $result_stmt->bindParam(':user_access', $user_access);
                    $result_stmt->bindParam(':user_password', $user_password);
                    $result_stmt->bindParam(':authorize_courier_image', $authorize_courier_image);
                    $result_stmt->bindParam(':datetime_today', $dateCreated);
                    $result_stmt->execute();
                }
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'preview_user':
            //* ======== Read Data ========
            $userid = trim($_POST['userid']);
            $resultData_List = array();
            //* ======== Fetch Data ========
            $sqlstring = "SELECT user_id,prl_employee.empno,username,encode(bpi_user_accounts.user_image, 'escape') as user_image,dept_code,is_active,reset_pass
                FROM bpi_user_accounts
                INNER JOIN prl_employee ON prl_employee.empno = bpi_user_accounts.empno
                WHERE user_id = :userid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':userid', $userid);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultData_List['dept_code'] = $row['dept_code'];
                $resultData_List['empno'] = $row['empno'];
                $resultData_List['username'] = $row['username'];
                $resultData_List['reset_pass'] = $row['reset_pass'];
                $resultData_List['is_active'] = $row['is_active'] == 'true' ? '1' : '0';
                $resultData_List['user_image'] = $row['user_image'] == '' ? '<img src="../vendor/images/blank-profile-picture.png" id="add_user_image"' : '<img src="data:image/jpeg;base64,' . $row['user_image'] . '" value="' . $row['user_image'] . '" id="add_user_image">';
            }
            //* ======== Send Data as JSON Format ========
            echo json_encode($resultData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'update_user_account':
            $userid = trim($_POST['userid']);
            $user_department = trim($_POST['user_department']);
            $user_username = trim($_POST['user_username']);
            $user_status = trim($_POST['user_status']);
            $user_empno = trim($_POST['user_empno']);
            $user_access = trim($_POST['user_access']);
            $add_user_image = trim($_POST['add_user_image']);
            $modified_date = date('Y-m-d H:m:s');

            $chkExist = "SELECT * FROM bpi_user_accounts WHERE empno = :empno AND user_id <> :userid";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':empno', $user_empno);
            $chkExist_stmt->bindParam(':userid', $userid);
            $chkExist_stmt->execute();

            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $chkUsernameExist = "SELECT * FROM bpi_user_accounts WHERE username = :username AND user_id <> :userid";
                $chkUsernameExist_stmt = $BannerWebLive->prepare($chkUsernameExist);
                $chkUsernameExist_stmt->bindParam(':username', $user_username);
                $chkUsernameExist_stmt->bindParam(':userid', $userid);
                $chkUsernameExist_stmt->execute();
                if ($chkUsernameExist_stmt->rowCount() > 0) {
                    echo 'existing username';
                } else {
                    $sqlstring = "UPDATE bpi_user_accounts SET empno = :empno, username = :username, is_active = :user_status, 
                    access_lvl = :user_access, department = :department, user_image = :user_image, modified_date = :modified_date WHERE user_id = :userid";
                    $result_stmt = $BannerWebLive->prepare($sqlstring);
                    $result_stmt->bindParam(':userid', $userid);
                    $result_stmt->bindParam(':empno', $user_empno);
                    $result_stmt->bindParam(':department', $user_department);
                    $result_stmt->bindParam(':username', $user_username);
                    $result_stmt->bindParam(':user_status', $user_status);
                    $result_stmt->bindParam(':user_access', $user_access);
                    $result_stmt->bindParam(':user_image', $add_user_image);
                    $result_stmt->bindParam(':modified_date', $modified_date);
                    $result_stmt->execute();
                }
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'reset_user_password':
            $userid = trim($_POST['userid']);
            $date_created = date("Y-m-d H:i:s");
            $defaultPass = 'Banner1994#';

            $sqlstring = "UPDATE bpi_user_accounts SET userpass = md5(:defaultPass), pass_created_date = :date_created, forgot_password = 'false', reset_pass = 'true' WHERE user_id = :userid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':userid', $userid);
            $result_stmt->bindParam(':defaultPass', $defaultPass);
            $result_stmt->bindParam(':date_created', $date_created);
            $result_stmt->execute();
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'disable_enable_user':
            $userid = trim($_POST['userid']);
            $user_status = trim($_POST['isactive']) == 1 ? 'false' : 'true';

            $sqlstring = "UPDATE bpi_user_accounts SET is_active = :isactive ";
            if (trim($_POST['isactive']) == 1) {
                $sqlstring .= ",is_logged_in = 'false' ";
            }
            $sqlstring .= "WHERE user_id = :userid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':userid', $userid);
            $result_stmt->bindParam(':isactive', $user_status);
            $result_stmt->execute();
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'lock_unlock_user':
            $userid = trim($_POST['userid']);
            $user_locked = trim($_POST['lockedout']) == 1 ? 'false' : 'true';

            $sqlstring = "UPDATE bpi_user_accounts SET act_lockedout = :islocked,is_logged_in = 'false' WHERE user_id = :userid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':userid', $userid);
            $result_stmt->bindParam(':islocked', $user_locked);
            $result_stmt->execute();
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'delete_user_account':
            $userid = trim($_POST['userid']);

            $sqlstring = "DELETE FROM bpi_user_accounts WHERE user_id = :userid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':userid', $userid);
            $result_stmt->execute();
            $BannerWebLive = null; //* ======== Close Connection ========
            break;
    }
}
