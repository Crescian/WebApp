<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_request_main_model.class.php';
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $ITRepair = new ITRepairMain();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_table_request':
            $statusVal = trim($_POST['statusVal']);
            $searchValue = $_POST['search']['value'];
            echo $ITRepair->loadTableRequestData($php_fetch_it_repair_api, $statusVal, $searchValue);
            break;

        case 'load_request_count':
            echo $ITRepair->loadRequestCount($php_fetch_it_repair_api);
            break;

        case 'load_details':
            $id = trim($_POST['id']);
            echo $ITRepair->loadRequestDetails($php_fetch_it_repair_api, $id);
            break;

        case 'cancel':  //* Cancel Request
            $id = trim($_POST['id']);
            echo $ITRepair->loadRequestCancel($php_update_it_repair_api, $id);
            break;

        case 'reapprove':  //* Reapprove Request
            $id = trim($_POST['id']);
            echo $ITRepair->loadRequestReApprove($php_update_it_repair_api, $id);
            break;

        case 'process': //* Process Request
            $id = trim($_POST['id']);
            echo $ITRepair->loadRequestProcess($php_update_it_repair_api, $id);
            break;

        case 'accomplish':  //* Accomplish Request
            $id = trim($_POST['id']);
            $sender = trim($_POST['sender']);
            $logged_user = trim($_POST['logged_user']);
            echo $ITRepair->loadRequestAccomplish($php_update_it_repair_api, $id, $sender, $logged_user);
            break;
    }
}
