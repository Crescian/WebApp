<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_user_access_request_model.class.php';
    $WHPO = $conn->db_conn_whpo(); //* Physical Security Database connection
    $ITUserAccess = new ITUserAccess();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'generate_defective_refno':
            $inField = trim($_POST['inField']);
            $inTable = trim($_POST['inTable']);
            echo $ITUserAccess->generate_defective_refno($php_fetch_itasset_api, $inField, $inTable);
            break;
        case 'saveUserAccess':
            $control_no = trim($_POST['control_no']);
            $date_request = trim($_POST['date_request']);
            $date_needed = trim($_POST['date_needed']);
            $access = trim($_POST['access']);
            $priority = trim($_POST['priority']);
            $domainAccount = trim($_POST['domainAccount']);
            $mail_account = trim($_POST['mail_account']);
            $file_storage_access = trim($_POST['file_storage_access']);
            $in_house_access = trim($_POST['in_house_access']);
            $purpose = trim($_POST['purpose']);
            $preparedBy = trim($_POST['preparedBy']);
            $approvedBy = trim($_POST['approvedBy']);
            $notedBy = trim($_POST['notedBy']);
            echo $ITUserAccess->saveUserAccess($php_fetch_itasset_api, $php_fetch_bannerweb_api, $php_update_itasset_api, $php_insert_itasset_api, $control_no, $date_needed, $access, $priority, $domainAccount, $mail_account, $file_storage_access, $in_house_access, $purpose, $preparedBy, $approvedBy, $notedBy);
            break;
        case 'loadDepartmentHead':
            echo $ITUserAccess->loadDepartmentHead($php_fetch_bannerweb_api);
            break;
        case 'getPosCode':
            $user_department = trim($_POST['user_department']);
            echo $ITUserAccess->loadGetPosCode($php_fetch_bannerweb_api, $user_department);
            break;
        case 'loadControlNo':
            $logged_user = trim($_POST['logged_user']);
            echo $ITUserAccess->loadControlNo($php_fetch_itasset_api, $logged_user);
            break;
        case 'previewControlPreview':
            $control_no = trim($_POST['control_no']);
            echo $ITUserAccess->previewControlPreview($php_fetch_itasset_api, $control_no);
            break;
        case 'update':
            $control_no = trim($_POST['control_no']);
            $date_needed = trim($_POST['date_needed']);
            $access = trim($_POST['access']);
            $priority = trim($_POST['priority']);
            $domainAccount = trim($_POST['domainAccount']);
            $mail_account = trim($_POST['mail_account']);
            $file_storage_access = trim($_POST['file_storage_access']);
            $in_house_access = trim($_POST['in_house_access']);
            $purpose = trim($_POST['purpose']);
            $preparedBy = trim($_POST['preparedBy']);
            $approvedBy = trim($_POST['approvedBy']);
            $notedBy = trim($_POST['notedBy']);
            echo $ITUserAccess->update($php_fetch_itasset_api, $control_no, $date_needed, $access, $priority, $domainAccount, $mail_account, $file_storage_access, $in_house_access, $purpose, $preparedBy, $approvedBy, $notedBy);
            break;
        case 'loadEmployee':
            echo $ITUserAccess->loadEmployee($php_fetch_bannerweb_api);
            break;
    }
}
