<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_repair_request_main_model.class.php';
    $ITRepairRequest = new ITRepairMainRequest();
    $action = trim($_POST['action']);
    $date_created = date("Y-m-d");

    switch ($action) {
        case 'load_table_repair':
            $statusVal = trim($_POST['statusVal']) ?? 'On Hold';
            $searchValue = $_POST['search']['value'];
            echo $ITRepairRequest->loadRepairTable($php_fetch_it_repair_api,  $statusVal, $searchValue);
            break;

        case 'load_repair_count':
            echo $ITRepairRequest->loadRepairCount($php_fetch_it_repair_api);
            break;

        case 'load_details':
            $id = trim($_POST['id']);
            echo $ITRepairRequest->loadDetails($php_fetch_it_repair_api,  $id);
            break;

        case 'acknowledge':
            $id = trim($_POST['id']);
            $priority = trim($_POST['priority']);
            $logged_name = trim($_POST['logged_name']);

            $ITRepairRequest->saveAcknowledge($php_update_it_repair_api,  $id, $priority);
            break;

        case 'cancel':
            $id = trim($_POST['id']);
            $ITRepairRequest->saveCancel($php_update_it_repair_api,  $id);
            break;

        case 'repair':
            $id = trim($_POST['id']);
            $ITRepairRequest->saveRepair($php_update_it_repair_api,  $id);
            break;

        case 'accomplish':
            $id = trim($_POST['id']);
            $actionTaken = trim($_POST['action_taken']);
            $sender = trim($_POST['sender']);
            $requested = trim($_POST['requested']);
            $logged_name = trim($_POST['logged_name']);
            $ITRepairRequest->saveAccomplish($php_fetch_bannerweb_api, $php_update_it_repair_api, $php_insert_it_repair_api, $actionTaken, $id, $sender, $logged_name, $date_created);
            break;
        case 'getDoneStatus':
            echo $ITRepairRequest->getDoneStatus($php_fetch_it_repair_api);
            break;
        case 'setDuration':
            $id = trim($_POST['id']);
            $totalMinutes = trim($_POST['totalMinutes']);
            $ITRepairRequest->setDuration($php_update_it_repair_api,  $id, $totalMinutes);
            break;
        case 'loadDept':
            echo $ITRepairRequest->loadDept($php_fetch_bannerweb_api);
            break;
        case 'proceedAccomplish':
            $id = trim($_POST['id']);
            $logged_name = trim($_POST['logged_name']);
            echo $ITRepairRequest->proceedAccomplish($php_fetch_bannerweb_api, $php_fetch_it_repair_api, $php_update_it_repair_api, $php_update_bannerweb_api, $php_insert_bannerweb_api, $id, $logged_name, $date_created);
            break;
    }
}
