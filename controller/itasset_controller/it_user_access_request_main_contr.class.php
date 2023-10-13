<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_user_access_request_main_model.class.php';
    $ITUserAccess = new ITUserAccessMain();
    $date_created = date("Y-m-d");
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_table_request':
            $statusVal = trim($_POST['statusVal']);
            $searchValue = $_POST['search']['value'];
            echo $ITUserAccess->loadTableRequestData($php_fetch_itasset_api,  $statusVal, $searchValue);
            break;
        case 'load_request_count':
            echo $ITUserAccess->loadRequestCount($php_fetch_itasset_api);
            break;
        case 'acknowledgeRequest':
            $data = trim($_POST['data']);
            echo $ITUserAccess->acknowledgeRequest($php_update_itasset_api, $data);
            break;
        case 'cancelRequest':
            $data = trim($_POST['data']);
            echo $ITUserAccess->cancelRequest($php_update_itasset_api, $data);
            break;
        case 'reapproveRequest':
            $data = trim($_POST['data']);
            echo $ITUserAccess->reapproveRequest($php_update_itasset_api, $data);
            break;
        case 'accomplishRequest':
            $data = trim($_POST['data']);
            $logged_user = trim($_POST['logged_user']);
            echo $ITUserAccess->accomplishRequest($php_update_itasset_api, $php_update_bannerweb_api, $logged_user, $date_created, $data);
            break;
        case 'detailsRequest':
            $data = trim($_POST['data']);
            echo $ITUserAccess->detailsRequest($php_fetch_itasset_api, $data);
            break;
        case 'update_approval_details':
            $access = trim($_POST['access']);
            $priority = trim($_POST['priority']);
            $purpose = trim($_POST['purpose']);
            $mail_account = trim($_POST['mail_account']);
            $file_storage_access = trim($_POST['file_storage_access']);
            $in_house_access = trim($_POST['in_house_access']);
            $domain = trim($_POST['domain']);
            $prepared_by_date = trim($_POST['prepared_by_date']);
            $date_needed = trim($_POST['date_needed']);
            $request_id = trim($_POST['request_id']);
            echo $ITUserAccess->update_approval_details($php_update_itasset_api, $access, $priority, $purpose, $mail_account, $file_storage_access, $in_house_access, $domain, $prepared_by_date, $date_needed, $request_id);
            break;
    }
}
