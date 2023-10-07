<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/notification_model/notification_module_model.class.php';
    $BannerWeb = $conn->db_conn_bannerweb(); //* BANNER WEB Database connection
    $ITR = $conn->db_conn_it_repair_request(); //* IT REPAIR AND REQUEST Database connection
    $InfoSec = $conn->db_conn_info_security(); //* INFO SEC Database connection
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $notif = new notificationModule();
    $action = trim($_POST['action']);
    $date = date('Y-m-d');
    $connDb = ['it_repair_request' => $ITR, 'info_security' => $InfoSec, 'physical_security' => $PHD];

    switch ($action) {
        case 'load_nav_link':
            $fullname = trim($_POST['fullname']);
            $archive = trim($_POST['archive']);
            echo $notif->load_nav_link($BannerWeb, $fullname, $archive);
            break;
        case 'load_assignatory':
            $id = trim($_POST['id']);
            $fullname = trim($_POST['fullname']);
            $archive = trim($_POST['archive']);
            echo $notif->load_assignatory($BannerWeb, $id, $fullname, $archive);
            break;
        case 'loadCount';
            $fullname = trim($_POST['fullname']);
            $app_id = trim($_POST['app_id']);
            echo $notif->loadCount($BannerWeb, $app_id, $fullname);
            break;
        case 'load_request_table':
            $fullname = trim($_POST['fullname']);
            $app_id = trim($_POST['app_id']);
            $label = trim($_POST['label']);
            $archive = trim($_POST['archive']);
            echo $notif->loadTable($BannerWeb, $app_id, $fullname, $label, $archive);
            break;
        case 'generateFields':
            $notificationid = trim($_POST['notificationid']);
            $table_database = trim($_POST['table_database']);
            $table_name = trim($_POST['table_name']);
            $table_field_id = trim($_POST['table_field_id']);
            $table_field_id_name = trim($_POST['table_field_id_name']);
            $connection = $connDb[$_POST['table_database']];
            echo $notif->generateFields($BannerWeb, $connection, $notificationid, $table_name, $table_field_id, $table_field_id_name);
            break;
        case 'cancelAcknowledge':
            $id = trim($_POST['id']);
            $table = trim($_POST['table']);
            $table_name = trim($_POST['table_name']);
            $table_id = trim($_POST['table_id']);
            $table_id_name = trim($_POST['table_id_name']);
            $status = trim($_POST['status']);
            $connection = $connDb[$_POST['table']];
            echo $notif->cancelAcknowledge($connection, $BannerWeb, $id, $table_name, $table_id, $table_id_name);
            break;
        case 'acknowledge':
            $id = trim($_POST['id']);
            $table_id = trim($_POST['table_id']);
            $table_id_name = trim($_POST['table_id_name']);
            $table_name = trim($_POST['table_name']);
            $table_database = trim($_POST['table_name']);
            $db_name = trim($_POST['table']);
            $connection = $connDb[$_POST['table']];
            echo $notif->acknowledge($connection, $ITR, $BannerWeb, $id, $table_id, $table_id_name, $table_name, $date);
            break;
        case 'fillData':
            $table_database = trim($_POST['table_database']);
            $table_name = trim($_POST['table_name']);
            $table_field_id = trim($_POST['table_field_id']);
            $table_field_id_name = trim($_POST['table_field_id_name']);
            $data = trim($_POST['data']);
            $connection = $connDb[$_POST['table_database']];
            echo $notif->fillData($connection, $table_name, $table_field_id, $table_field_id_name, $data);
            break;
    }
}
