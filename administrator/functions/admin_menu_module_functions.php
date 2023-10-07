<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    //* Banner Web Database connection
    $BannerWebLive = $conn->db_conn_bannerweb();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_application_table_data':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'app_name',
                1 => 'app_link',
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT appid,app_name,app_link FROM bpi_app_menu";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT appid,app_name,app_link FROM bpi_app_menu WHERE 1 = 1";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND (app_name ILIKE '%" . $searchValue . "%' OR app_link ILIKE '%" . $searchValue . "%')";
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
                $nestedData = array();
                $nestedData[] = $row['app_name'];
                $nestedData[] = $row['app_link'];
                $nestedData[] = $row['appid'];
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

        case 'save_application':
            $app_name = trim($_POST['app_name']);
            $app_link = trim($_POST['app_link']);

            $chkExist = "SELECT * FROM bpi_app_menu WHERE app_name = :app_name AND app_link = :app_link";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':app_name', $app_name);
            $chkExist_stmt->bindParam(':app_link', $app_link);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_app_menu(app_name,app_link) VALUES(:app_name,:app_link)";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':app_name', $app_name);
                $result_stmt->bindParam(':app_link', $app_link);
                $result_stmt->execute();
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_app_info':
            $appid = trim($_POST['appid']);
            $resultData_List = array();

            $result_sql = "SELECT * FROM bpi_app_menu WHERE appid = :appid";
            $result_stmt = $BannerWebLive->prepare($result_sql);
            $result_stmt->bindParam(':appid', $appid);
            $result_stmt->execute();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultData_List['app_name'] = $row['app_name'];
                $resultData_List['app_link'] = $row['app_link'];
            }
            echo json_encode($resultData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'update_application':
            $app_name = trim($_POST['app_name']);
            $app_link = trim($_POST['app_link']);
            $appid = trim($_POST['appid']);

            $chkExist = "SELECT * FROM bpi_app_menu WHERE app_name = :app_name AND app_link = :app_link AND appid <> :appid";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':app_name', $app_name);
            $chkExist_stmt->bindParam(':app_link', $app_link);
            $chkExist_stmt->bindParam(':appid', $appid);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "UPDATE bpi_app_menu SET app_name = :app_name, app_link = :app_link WHERE appid = :appid";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':app_name', $app_name);
                $result_stmt->bindParam(':app_link', $app_link);
                $result_stmt->bindParam(':appid', $appid);
                $result_stmt->execute();
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'remove_application':
            $appid = trim($_POST['appid']);

            $sqlstring = "DELETE FROM bpi_app_menu WHERE appid = :appid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':appid', $appid);
            $result_stmt->execute();
            echo 'success';
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_menu_table_data':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'app_menu_title',
                1 => 'app_menu_link',
                2 => 'app_name',
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT appmenuid,app_menu_title,app_menu_link,app_name FROM bpi_app_menu_module 
                INNER JOIN bpi_app_menu ON bpi_app_menu.appid = bpi_app_menu_module.app_id";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT appmenuid,app_menu_title,app_menu_link,app_name FROM bpi_app_menu_module 
                INNER JOIN bpi_app_menu ON bpi_app_menu.appid = bpi_app_menu_module.app_id
                WHERE 1 = 1";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND (app_menu_title ILIKE '%" . $searchValue . "%' OR app_menu_link ILIKE '%" . $searchValue . "%' OR app_name ILIKE '%" . $searchValue . "%')";
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
                $nestedData = array();
                $nestedData[] = $row['app_menu_title'];
                $nestedData[] = $row['app_menu_link'];
                $nestedData[] = $row['app_name'];
                $nestedData[] = $row['appmenuid'];
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

        case 'save_menu':
            $app_id = trim($_POST['app_id']);
            $app_menu_title = trim($_POST['app_menu_title']);
            $app_menu_link = trim($_POST['app_menu_link']) == '' ? '#' : trim($_POST['app_menu_link']);
            $app_menu_parent_id = trim($_POST['app_menu_parent_id']);

            $chkExist = "SELECT * FROM bpi_app_menu_module WHERE app_menu_link = :app_menu_link AND app_id = :app_id AND app_menu_link <> '#'";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':app_menu_link', $app_menu_link);
            $chkExist_stmt->bindParam(':app_id', $app_id);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_app_menu_module(app_menu_title,app_menu_link,app_menu_parent_id,app_id)
                    VALUES(:app_menu_title,:app_menu_link,:app_menu_parent_id,:app_id)";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':app_id', $app_id);
                $result_stmt->bindParam(':app_menu_title', $app_menu_title);
                $result_stmt->bindParam(':app_menu_link', $app_menu_link);
                $result_stmt->bindParam(':app_menu_parent_id', $app_menu_parent_id);
                $result_stmt->execute();
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_menu_info':
            $appmenuid = trim($_POST['appmenuid']);
            $resultData_List = array();

            $sqlstring = "SELECT * FROM bpi_app_menu_module WHERE appmenuid = :appmenuid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':appmenuid', $appmenuid);
            $result_stmt->execute();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultData_List['app_id'] = $row['app_id'];
                $resultData_List['app_menu_title'] = $row['app_menu_title'];
                $resultData_List['app_menu_link'] = $row['app_menu_link'];
                $resultData_List['app_menu_parent_id'] = $row['app_menu_parent_id'];
            }
            echo json_encode($resultData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'update_menu':
            $appmenuid = trim($_POST['appmenuid']);
            $app_id = trim($_POST['app_id']);
            $app_menu_title = trim($_POST['app_menu_title']);
            $app_menu_link = trim($_POST['app_menu_link']) == '' ? '#' : trim($_POST['app_menu_link']);
            $app_menu_parent_id = trim($_POST['app_menu_parent_id']);

            $chkExist = "SELECT * FROM bpi_app_menu_module WHERE app_menu_link = :app_menu_link AND app_id = :app_id AND app_menu_link <> '#' AND appmenuid <> :appmenuid";
            $chkExist_stmt = $BannerWebLive->prepare($chkExist);
            $chkExist_stmt->bindParam(':app_menu_link', $app_menu_link);
            $chkExist_stmt->bindParam(':app_id', $app_id);
            $chkExist_stmt->bindParam(':appmenuid', $appmenuid);
            $chkExist_stmt->execute();
            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "UPDATE bpi_app_menu_module SET app_menu_title = :app_menu_title, app_menu_link = :app_menu_link, app_menu_parent_id = :app_menu_parent_id, app_id = :app_id WHERE appmenuid = :appmenuid";
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                $result_stmt->bindParam(':appmenuid', $appmenuid);
                $result_stmt->bindParam(':app_id', $app_id);
                $result_stmt->bindParam(':app_menu_title', $app_menu_title);
                $result_stmt->bindParam(':app_menu_link', $app_menu_link);
                $result_stmt->bindParam(':app_menu_parent_id', $app_menu_parent_id);
                $result_stmt->execute();
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'remove_menu':
            $appmenuid = trim($_POST['appmenuid']);

            $sqlstring = "DELETE FROM bpi_app_menu_module WHERE appmenuid = :appmenuid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':appmenuid', $appmenuid);
            $result_stmt->execute();
            echo 'success';
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_parent_menu':
            $app_id = trim($_POST['app_id']) == '' ? NULL : trim($_POST['app_id']);
            $itemData_List = array();

            $sqlstring = "SELECT appmenuid,app_menu_title FROM bpi_app_menu_module WHERE app_id = :app_id AND app_menu_parent_id = '0' AND app_menu_link = '#'";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':app_id', $app_id);
            $result_stmt->execute();
            if ($result_stmt->rowCount() > 0) {
                foreach ($result_stmt->fetchAll() as $row) {
                    $itemData_List[$row['appmenuid']] = $row['app_menu_title'];
                }
            } else {
                $itemData_List['appmenuid'] = 'empty';
            }
            echo json_encode($itemData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_job_title':
            $dept_code = trim($_POST['dept_code']);
            $itemData_List = array();

            $sqlstring = "SELECT DISTINCT prl_position.pos_code, pos_name FROM prl_employee
                INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
                WHERE dept_code = :dept_code ORDER BY pos_name ASC";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':dept_code', $dept_code);
            $result_stmt->execute();
            if ($result_stmt->rowCount() > 0) {
                foreach ($result_stmt->fetchAll() as $row) {
                    $itemData_List[$row['pos_code']] = $row['pos_name'];
                }
            } else {
                $itemData_List['pos_code'] = 'empty';
            }
            echo json_encode($itemData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_employee':
            $pos_code = trim($_POST['pos_code']);
            $dept_code = trim($_POST['dept_code']);
            $itemData_List = array();

            $sqlstring = "SELECT empno,(emp_fn || ' ' || emp_sn) AS emp_name FROM prl_employee WHERE dept_code = :dept_code AND pos_code = :pos_code";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':dept_code', $dept_code);
            $result_stmt->bindParam(':pos_code', $pos_code);
            $result_stmt->execute();
            if ($result_stmt->rowCount() > 0) {
                foreach ($result_stmt->fetchAll() as $row) {
                    $itemData_List[$row['empno']] = $row['emp_name'];
                }
            } else {
                $itemData_List['empno'] = 'empty';
            }
            echo json_encode($itemData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_user_access':
            $empno = trim($_POST['empno']);
            $itemData_List = array();

            $sqlstring = "SELECT app_name,app_menu_title FROM bpi_access_module
                INNER JOIN bpi_app_menu ON bpi_app_menu.appid = bpi_access_module.app_id
                INNER JOIN bpi_app_menu_module ON bpi_app_menu_module.appmenuid = bpi_access_module.appmenu_id
                WHERE access_user = :empno";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':empno', $empno);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['app_name']][] = $row['app_menu_title'];
            }
            echo json_encode($itemData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_grant_access_menu':
            $appid = trim($_POST['appid']);
            $itemData_List = array();

            $sqlstring = "SELECT CAST(appmenuid AS varchar) AS id,app_menu_title AS text, CASE WHEN CAST(app_menu_parent_id AS varchar) = '0' THEN '#' ELSE CAST(app_menu_parent_id AS varchar) END AS parent 
                FROM bpi_app_menu_module WHERE app_id = :appid";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':appid', $appid);
            $result_stmt->execute();

            if ($result_stmt->rowCount() > 0) {
                while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $app_menu[] = $row;
                }
                //* build array of item references
                foreach ($app_menu as $key => $item) {
                    $itemData_List[$item['id']] = $item;
                }
                echo json_encode($app_menu);
            } else {
                echo json_encode('empty');
            }
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'load_access_rigths':
            $appid = trim($_POST['appid']);
            $empno = trim($_POST['empno']);
            $itemData_List = array();

            $sqlstring = "SELECT * FROM bpi_access_module WHERE app_id = :appid AND access_user = :empno";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':appid', $appid);
            $result_stmt->bindParam(':empno', $empno);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[] = $row['appmenu_id'];
            }
            echo json_encode($itemData_List);
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'delete_access_rights':
            $app_id = trim($_POST['app_id']);
            $access_user = trim($_POST['access_user']);

            $sqlstring = "DELETE FROM bpi_access_module WHERE access_user = :access_user AND app_id = :app_id";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':app_id', $app_id);
            $result_stmt->bindParam(':access_user', $access_user);
            $result_stmt->execute();
            $BannerWebLive = null; //* ======== Close Connection ========
            break;

        case 'save_access_rigths':
            $appmenuid = trim($_POST['appmenuid']);
            $app_id = trim($_POST['app_id']);
            $access_user = trim($_POST['access_user']);

            $sqlstring = "INSERT INTO bpi_access_module(appmenu_id,access_user,app_id) VALUES(:appmenu_id,:access_user,:app_id)";
            $result_stmt = $BannerWebLive->prepare($sqlstring);
            $result_stmt->bindParam(':appmenu_id', $appmenuid);
            $result_stmt->bindParam(':access_user', $access_user);
            $result_stmt->bindParam(':app_id', $app_id);
            $result_stmt->execute();
            $BannerWebLive = null; //* ======== Close Connection ========
            break;
    }
}
