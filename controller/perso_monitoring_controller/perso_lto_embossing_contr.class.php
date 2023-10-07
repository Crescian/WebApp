<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_lto_embossing_model.class.php';
    $lto_conn = $conn->db_conn_lto_serial(); //* ltoserial Database connection

    $ltoSerial = new LtoSerial();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_serial_table_data':
            $searchValue = $_POST['search']['value'];
            $inFilter = trim($_POST['inFilter']);
            $startSerial = trim($_POST['startSerial']);
            $endSerial = trim($_POST['endSerial']);
            $inStatus = trim($_POST['inStatus']);
            echo $ltoSerial->loadLtoSerialData($lto_conn, $searchValue, $inFilter, $startSerial, $endSerial, $inStatus);
            break;

        case 'load_user_table_data';
            $searchValue = $_POST['search']['value'];
            echo $ltoSerial->loadUserData($lto_conn, $searchValue);
            break;

        case 'load_manual_info':
            $serialid = trim($_POST['serialid']);
            echo $ltoSerial->loadSerialInfo($lto_conn, $serialid);
            break;

        case 'save_user':
            $user_name = trim($_POST['user_name']);
            $user_pass = trim($_POST['user_pass']);
            echo $ltoSerial->saveUserData($lto_conn, $user_name, $user_pass);
            break;

        case 'remove_user':
            $userid = trim($_POST['userid']);
            $ltoSerial->removeUserData($lto_conn, $userid);
            break;

        case 'update_user_password':
            $userid = trim($_POST['userid']);
            $new_pass = trim($_POST['new_pass']);
            $ltoSerial->updateUserPass($lto_conn, $userid, $new_pass);
            break;

        case 'update_serial_status':
            $serialid = trim($_POST['serialid']);
            $logged_user = trim($_POST['logged_user']);
            $manual_remarks = trim($_POST['manual_remarks']);
            $ltoSerial->updateSerialStatus($lto_conn, $serialid, $logged_user, $manual_remarks);
            break;

        case 'read_text_file':
            $filename = trim($_POST['filename']);
            $myfile = fopen($filename, 'r') or die('Unable to open file!');
            while (!feof($myfile)) {
                echo fgets($myfile);
                // echo $ltoSerial->insertSerialData($lto_conn, str_replace('"', '', trim(fgets($myfile))));
            }
            fclose($myfile);
            break;
    }
}
