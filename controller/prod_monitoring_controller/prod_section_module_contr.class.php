<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/prod_monitoring_model/prod_section_module_model.class.php';
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Manufacturing Database connection
    $sectionModule = new ProdSectionModule();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_section_list_table':
            echo $sectionModule->loadSectionTable($prod);
            break;

        case 'save_section_name':
            $section_name = trim($_POST['section_name']);
            echo $sectionModule->saveSectionName($prod, $section_name);
            break;

        case 'load_section_info':
            $sectionid = trim($_POST['sectionid']);
            echo $sectionModule->loadSectionInfo($prod, $sectionid);
            break;

        case 'update_section':
            $sectionid = trim($_POST['sectionid']);
            $section_name = trim($_POST['section_name']);
            echo $sectionModule->updateSectionName($prod, $sectionid, $section_name);
            break;

        case 'remove_section':
            $sectionid = trim($_POST['sectionid']);
            echo $sectionModule->removeSectionName($prod, $sectionid);
            break;

        case 'load_assign_list_table':
            echo $sectionModule->loadAssignListTable($prod);
            break;

        case 'save_assign':
            $section_id = trim($_POST['section_id']);
            $machine_id = trim($_POST['machine_id']);
            echo $sectionModule->saveAssignMachine($prod, $section_id, $machine_id);
            break;

        case 'load_assign_info':
            $sectionassignid = trim($_POST['sectionassignid']);
            echo $sectionModule->loadSectionMachine($prod, $sectionassignid);
            break;

        case 'update_assign':
            $section_id = trim($_POST['section_id']);
            $machine_id = trim($_POST['machine_id']);
            $sectionassignid = trim($_POST['sectionassignid']);
            echo $sectionModule->updateSectionMachine($prod, $section_id, $machine_id, $sectionassignid);
            break;

        case 'remove_assign':
            $sectionassignid = trim($_POST['sectionassignid']);
            $sectionModule->removeSectionMachine($prod, $sectionassignid);
            break;

        case 'load_assign_employee_table':
            echo $sectionModule->loadAssignEmployeeTable($prod);
            break;

        case 'save_section_job_title':
            $section_id = trim($_POST['section_id']);
            $pos_code = trim($_POST['pos_code']);
            echo $sectionModule->saveAssignEmployeeJobTitle($prod, $section_id, $pos_code);
            break;

        case 'load_assign_employee_info':
            $assignjobid = trim($_POST['assignjobid']);
            echo $sectionModule->loadAssignEmployeeInfo($prod, $assignjobid);
            break;

        case 'update_section_job_title':
            $assignjobid = trim($_POST['assignjobid']);
            $section_id = trim($_POST['section_id']);
            $pos_code = trim($_POST['pos_code']);
            echo $sectionModule->updateAssignEmployeeJobTitle($prod, $assignjobid, $section_id, $pos_code);
            break;

        case 'remove_section_job_title':
            $assignjobid = trim($_POST['assignjobid']);
            $sectionModule->removeAssignEmployeeJobTitle($prod, $assignjobid);
            break;

        case 'load_select_with_id':
            $category = trim($_POST['category']);
            echo $sectionModule->loadSelectWithId($prod, $BannerWebLive, $category);
            break;
    }
}
