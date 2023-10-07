<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_section_model.php';
    $persoSection = new PersoSection();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_section_list_table':
            $searchValue = $_POST['search']['value'];
            echo $persoSection->loadSectionTable($php_fetch_perso_api, $searchValue);
            break;

        case 'load_section_assigned_list_table':
            $searchValue = $_POST['search']['value'];
            echo $persoSection->loadSectionAssignedTable($php_fetch_bannerweb_api, $php_fetch_perso_api, $searchValue);
            break;

        case 'save_section':
            $section_name = trim($_POST['section_name']);
            echo $persoSection->saveSectionName($php_fetch_perso_api, $php_insert_perso_api, $section_name);
            break;

        case 'load_section_info':
            $perso_sectionid = trim($_POST['perso_sectionid']);
            echo $persoSection->loadSectionName($php_fetch_perso_api, $perso_sectionid);
            break;

        case 'update_section_name':
            $perso_sectionid = trim($_POST['perso_sectionid']);
            $section_name = trim($_POST['section_name']);
            echo $persoSection->updateSectionName($php_fetch_perso_api, $php_update_perso_api, $perso_sectionid, $section_name);
            break;

        case 'delete_section':
            $perso_sectionid = trim($_POST['perso_sectionid']);
            echo $persoSection->deleteSectionName($php_update_perso_api, $perso_sectionid);
            break;

        case 'save_assign_section':
            $perso_section_id = trim($_POST['perso_section_id']);
            $section_job_title = trim($_POST['section_job_title']);
            echo $persoSection->saveAssignedSection($php_fetch_perso_api, $php_insert_perso_api, $perso_section_id, $section_job_title);
            break;

        case 'load_section_assigned_info':
            $perso_sect_assignid = trim($_POST['perso_sect_assignid']);
            echo $persoSection->loadAssignedSection($php_fetch_perso_api, $perso_sect_assignid);
            break;

        case 'update_assign_section':
            $perso_section_id = trim($_POST['perso_section_id']);
            $section_job_title = trim($_POST['section_job_title']);
            $perso_sect_assignid = trim($_POST['perso_sect_assignid']);
            echo $persoSection->updateAssignedSection($php_fetch_perso_api, $php_update_perso_api, $perso_sect_assignid, $perso_section_id, $section_job_title);
            break;

        case 'delete_section_assigned':
            $perso_sect_assignid = trim($_POST['perso_sect_assignid']);
            echo $persoSection->deleteAssignedSection($php_update_perso_api, $perso_sect_assignid);
            break;

        case 'load_select_section_values':
            echo $persoSection->loadSelectSectionValues($php_fetch_perso_api);
            break;

        case 'load_select_job_title_values':
            echo $persoSection->loadSelectJobTitleValues($php_fetch_bannerweb_api);
            break;
    }
}
