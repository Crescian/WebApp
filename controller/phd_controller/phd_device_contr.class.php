<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_device_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $deviceModule = new PhdDeviceModule();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date_created = date("Y-m-d");

    switch ($action) {
        case 'load_category_table':
            echo $deviceModule->fetchDataCategoryList($PHD);
            break;
        case 'load_category_assigned_table':
            echo $deviceModule->fetchDataAssignDevice($PHD);
            break;
        case 'load_units_table':
            echo $deviceModule->fetchDataAssignDeviceUnits($PHD);
            break;
        case 'save_category_name':
            $device_category_name = trim($_POST['device_category_name']);
            echo $deviceModule->saveDataDeviceCategory($PHD, $device_category_name);
            break;
        case 'remove_category_name':
            $devicecategoryid = trim($_POST['devicecategoryid']);
            echo $deviceModule->deleteDataDeviceCategory($PHD, $devicecategoryid);
            break;
        case 'save_assign_device':
            $assign_category_name = trim($_POST['assign_category_name']);
            $location_name = trim($_POST['location_name']) == '' ? NULL : trim($_POST['location_name']);;
            $category_name = trim($_POST['category_name']) == '' ? NULL : trim($_POST['category_name']);;
            $device_name = trim($_POST['device_name']) == '' ? NULL : trim($_POST['device_name']);;
            echo $deviceModule->saveDataAssignList($PHD, $assign_category_name, $location_name, $category_name, $device_name);
            break;
        case 'delete_assigned_category':
            $devicecatassignid = trim($_POST['devicecatassignid']);
            echo $deviceModule->deleteDataAssignList($PHD, $devicecatassignid);
            break;
        case 'saveUnitNameFunction':
            $location_units = trim($_POST['location_units']);
            $units = trim($_POST['units']);
            echo $deviceModule->saveDataUnits($PHD, $location_units, $units);
            break;
        case 'btnUnitDeleteFunction':
            $assignunitsid = trim($_POST['assignunitsid']);
            echo $deviceModule->deleteDataUnits($PHD, $assignunitsid);
            break;
        case 'load_select_values':
            echo $deviceModule->loadSelectDevice($PHD);
            break;
        case 'btnPreviewCategoryFunction':
            $id = trim($_POST['id']);
            echo $deviceModule->loadPreviewCategory($PHD, $id);
            break;
        case 'updateCategoryNameFunction':
            $category_name = trim($_POST['category_name']);
            $id = trim($_POST['id']);
            echo $deviceModule->updateDataCategory($PHD, $category_name, $id);
            break;
        case 'btnPreviewAssign':
            $id = trim($_POST['id']);
            echo $deviceModule->loadPreviewAssign($PHD, $id);
            break;
        case 'updateAssignCategoryFunction':
            $assign_category_name = trim($_POST['assign_category_name']);
            $location_name = trim($_POST['location_name']) == '' ? null : trim($_POST['location_name']);
            $category_name = trim($_POST['category_name']) == '' ? null : trim($_POST['category_name']);
            $device_name = trim($_POST['device_name']) == '' ? null : trim($_POST['device_name']);
            $id = trim($_POST['id']);
            echo $deviceModule->updateDataAssign($PHD, $assign_category_name, $location_name, $category_name, $device_name, $id);
            break;
        case 'btnPreviewAssignUnits':
            $id = trim($_POST['id']);
            echo $deviceModule->loadPreviewAssignUnits($PHD, $id);
            break;
        case 'updateAssignUnitNameFunction':
            $location_units = trim($_POST['location_units']);
            $units = trim($_POST['units']);
            $id = trim($_POST['id']);
            echo $deviceModule->updateDataAssignUnits($PHD, $location_units, $units, $id);
            break;
    }
}
