<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_push_notification_model.class.php';
    $itPushNotifications = new ITPushNotifications();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'fetch_new_repair_request':
            $request_type = trim($_POST['request_type']);
            echo $itPushNotifications->fetchNewRepairRequest($php_fetch_it_repair_api, $request_type);
            break;
    }
}
