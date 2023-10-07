<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/info_sec_model/info_sec_server_access_model.class.php';
    $infoSecServer = new InfoSecServerAccess();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');

    switch ($action) {
        case 'load_server_access_table_data':
            $searchValue = $_POST['search']['value'];
            $access_status = trim($_POST['access_status']);
            echo $infoSecServer->loadServerAccessData($php_fetch_info_sec_api, $searchValue, $access_status);
            break;

        case 'receive_request':
            $serveraccessid = trim($_POST['serveraccessid']);
            $receive_by = trim($_POST['receive_by']);
            $date_received = date('Y-m-d');
            $infoSecServer->saveReceiveRequest($php_update_info_sec_api, $php_fetch_bannerweb_api, $serveraccessid, $receive_by, $date_received);
            break;
    }
}
