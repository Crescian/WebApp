<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_request_main_model.class.php';
    $ITRepair = new ITRepairMain();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_table_request':
            $statusVal = trim($_POST['statusVal']);
            $searchValue = $_POST['search']['value'];
            echo $ITRepair->loadTableRequestData($php_fetch_it_repair_api,  $statusVal, $searchValue);
            break;

        case 'load_request_count':
            echo $ITRepair->loadRequestCount($php_fetch_it_repair_api);
            break;

        case 'load_details':
            $id = trim($_POST['id']);
            echo $ITRepair->loadRequestDetails($php_fetch_it_repair_api,  $id);
            break;

        case 'cancel':  //* Cancel Request
            $id = trim($_POST['id']);
            echo $ITRepair->loadRequestCancel($php_update_it_repair_api,  $id);
            break;

        case 'reapprove':  //* Reapprove Request
            $id = trim($_POST['id']);
            echo $ITRepair->loadRequestReApprove($php_update_it_repair_api, $php_update_bannerweb_api,  $id);
            break;

        case 'process': //* Process Request
            $id = trim($_POST['id']);
            $logged_user = trim($_POST['logged_user']);
            echo $ITRepair->loadRequestProcess($php_fetch_bannerweb_api, $php_update_it_repair_api,  $logged_user, $id);
            break;

        case 'accomplish':  //* Accomplish Request
            $id = trim($_POST['id']);
            $sender = trim($_POST['sender']);
            $request = trim($_POST['request']);
            $logged_user = trim($_POST['logged_user']);
            echo $ITRepair->loadRequestAccomplish($php_update_it_repair_api, $php_update_bannerweb_api, $id, $sender, $request, $logged_user);
            break;

        case 'update_approval_details':
            $date_requested = trim($_POST['date_requested']);
            $date_needed = trim($_POST['date_needed']);
            $request_type = trim($_POST['request_type']);
            $item = trim($_POST['item']);
            $description = trim($_POST['description']);
            $purpose = trim($_POST['purpose']);
            $request_id = trim($_POST['request_id']);
            echo $ITRepair->update_approval_details($php_update_it_repair_api,  $request_id, $date_requested, $date_needed, $request_type, $item, $description, $purpose);
            break;
        case 'accomplishWithReason':
            $id = trim($_POST['id']);
            $reasonRemarks = trim($_POST['reasonRemarks']);
            $logged_user = trim($_POST['logged_user']);
            echo $ITRepair->accomplishWithReason($php_update_it_repair_api, $php_update_bannerweb_api, $id, $reasonRemarks, $logged_user);
            break;
        case 'cancelhWithReason':
            $id = trim($_POST['id']);
            $reasonCancelRemarks = trim($_POST['reasonCancelRemarks']);
            $logged_user = trim($_POST['logged_user']);
            echo $ITRepair->cancelhWithReason($php_update_it_repair_api, $php_update_bannerweb_api, $id, $reasonCancelRemarks, $logged_user);
            break;
        case 'toastNotification':
            echo $ITRepair->toastNotification($php_fetch_it_repair_api);
            break;
    }
}
