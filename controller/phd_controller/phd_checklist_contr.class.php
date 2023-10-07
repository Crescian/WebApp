<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_checklist_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $checklistModule = new PhdChecklistModule();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date_created = date("Y-m-d");

    switch ($action) {
        case 'load_checklist_name':
            echo $checklistModule->fetchDataChecklistName($PHD);
            break;
        case 'load_assign_table':
            echo $checklistModule->fetchDataAssignTable($PHD);
            break;
        case 'load_select_value_with_id':
            $category = trim($_POST['category']);
            switch ($category) {
                case 'checklist_name':
                    $inTable = 'phd_checklist_name';
                    $inFieldId = 'phdchklistid';
                    $inField = 'checklist_name';
                    break;
                case 'category_name':
                    $inTable = 'phd_location_category';
                    $inFieldId = 'phdloccatid';
                    $inField = 'category_name';
                    break;
                default:
                    $inTable = 'phd_location';
                    $inFieldId = 'phdlocationid';
                    $inField = 'location_name';
            }
            echo $checklistModule->loadSelectValueWithId($PHD, $inTable, $inFieldId, $inField);
            break;
        case 'save_checklist_function':
            $add_checklist_name = trim($_POST['add_checklist_name']);
            echo $checklistModule->saveDataChecklistName($PHD, $add_checklist_name);
            break;
        case 'update_checklist_function':
            $add_checklist_name = trim($_POST['add_checklist_name']);
            $checklistPreview = trim($_POST['checklistPreview']);
            echo $checklistModule->updateDataChecklistName($PHD, $add_checklist_name, $checklistPreview);
            break;
        case 'preview_checklist':
            $id = trim($_POST['id']);
            echo $checklistModule->previewDataChecklistName($PHD, $id);
            break;
        case 'save_assign_function':
            $add_checklist_assign = trim($_POST['add_checklist_assign']);
            $add_category_name = trim($_POST['add_category_name']);
            $add_location_name = trim($_POST['add_location_name']);
            echo $checklistModule->saveDataAssign($PHD, $add_checklist_assign, $add_category_name, $add_location_name);
            break;
        case 'update_assign_function':
            $add_checklist_assign_name = trim($_POST['add_checklist_assign_name']);
            $add_category_name = trim($_POST['add_category_name']);
            $add_location_name = trim($_POST['add_location_name']);
            $assignPreview = trim($_POST['assignPreview']);
            echo $checklistModule->updateDataAssign($PHD, $add_checklist_assign_name, $add_category_name, $add_location_name, $assignPreview);
            break;
        case 'preview_assign':
            $id = trim($_POST['id']);
            echo $checklistModule->previewDataAssign($PHD, $id);
            break;
        case 'delete_checklist':
            $id = trim($_POST['id']);
            echo $checklistModule->deleteDataChecklist($PHD, $id);
            break;
        case 'delete_assign':
            $id = trim($_POST['id']);
            echo $checklistModule->deleteDataAssign($PHD, $id);
            break;
        case 'load_chechklist':
            echo $checklistModule->loadDataChecklist($BannerWebLive);
            break;
    }
}
