<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_particular_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $particularModule = new PhdParticularModule();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date_created = date("Y-m-d");

    switch ($action) {
        case 'load_location_table':
            echo $particularModule->fetchDataParticular($PHD);
            break;
        case 'load_assign_table':
            echo $particularModule->fetchDataAssignParticular($PHD);
            break;
        case 'load_select_value_with_id':
            $inTable = 'phd_location';
            $inFieldId = 'phdlocationid';
            $inField = 'location_name';
            echo $particularModule->loadSelectValueWithId($PHD, $inTable, $inFieldId, $inField);
            break;
        case 'save_particular':
            $add_particular_name = trim($_POST['add_particular_name']);
            $location_name = trim($_POST['location_name']);
            echo $particularModule->saveDataParticular($PHD, $add_particular_name, $location_name);
            break;
        case 'update_particular':
            $add_particular_name = trim($_POST['add_particular_name']);
            $particularPreview = trim($_POST['particularPreview']);
            echo $particularModule->updateDataParticular($PHD, $add_particular_name, $particularPreview);
            break;
        case 'preview_particular':
            $id = trim($_POST['id']);
            echo $particularModule->previewDataParticular($PHD, $id);
            break;
        case 'delete_particular':
            $id = trim($_POST['id']);
            echo $particularModule->deleteDataParticular($PHD, $id);
            break;
    }
}
