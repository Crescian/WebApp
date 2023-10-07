<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_location_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $locationModule = new PhdLocation();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date_created = date("Y-m-d");

    switch ($action) {
        case 'load_location_table':
            echo $locationModule->fetchDataLocation($PHD);
            break;
        case 'load_category_table':
            echo $locationModule->fetchDataCategory($PHD);
            break;
        case 'load_assign_table':
            echo $locationModule->fetchDataAssign($PHD);
            break;
        case 'save_location_function':
            $add_location_name = trim($_POST['add_location_name']);
            echo $locationModule->saveDataLocation($PHD, $add_location_name);
            break;
        case 'location_update_function':
            $add_location_name = trim($_POST['add_location_name']);
            $locationPreview = trim($_POST['locationPreview']);
            echo $locationModule->updateDataLocation($PHD, $add_location_name, $locationPreview);
            break;
        case 'preview_location':
            $id = trim($_POST['id']);
            echo $locationModule->previewDataLocation($PHD, $id);
            break;
        case 'delete_location':
            $id = trim($_POST['id']);
            echo $locationModule->deleteDataLocation($PHD, $id);
            break;
        case 'save_category':
            $add_category_name = trim($_POST['add_category_name']);
            echo $locationModule->saveDataCategory($PHD, $add_category_name);
            break;
        case 'category_update':
            $add_category_name = trim($_POST['add_category_name']);
            $categoryPreview = trim($_POST['categoryPreview']);
            echo $locationModule->updateDataCategory($PHD, $add_category_name, $categoryPreview);
            break;
        case 'preview_category':
            $id = trim($_POST['id']);
            echo $locationModule->previewDataCategory($PHD, $id);
            break;
        case 'delete_category':
            $id = trim($_POST['id']);
            echo $locationModule->deleteDataCategory($PHD, $id);
            break;
        case 'load_location':
            echo $locationModule->loadLocation($PHD);
            break;
        case 'load_category':
            echo $locationModule->loadCategory($PHD);
            break;
        case 'save_assign_function':
            $add_category_name_assign = trim($_POST['add_category_name_assign']);
            $add_location_name_assign = trim($_POST['add_location_name_assign']);
            $zone_name = trim($_POST['zone_name']);
            echo $locationModule->saveDataAssign($PHD, $add_category_name_assign, $add_location_name_assign, $zone_name);
            break;
        case 'assign_update':
            $add_category_name_assign = trim($_POST['add_category_name_assign']);
            $add_location_name_assign = trim($_POST['add_location_name_assign']);
            $zone_name = trim($_POST['zone_name']);
            $assignPreview = trim($_POST['assignPreview']);
            echo $locationModule->updateDataAssign($PHD, $add_category_name_assign, $add_location_name_assign, $zone_name, $assignPreview);
            break;
        case 'preview_assign':
            $id = trim($_POST['id']);
            echo $locationModule->previewDataAssign($PHD, $id);
            break;
        case 'delete_assign':
            $id = trim($_POST['id']);
            echo $locationModule->deleteDataAssign($PHD, $id);
            break;
    }
}
