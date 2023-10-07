<?php

use PgSql\Lob;

if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_job_entry_model.class.php';
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $perso_archive = $conn->db_conn_personalization_archive(); //* Personalization Archive Database connection
    $bannerData = $conn->db_conn_bannerdata(); //* BannerData Database connection
    $cms_data = $conn->db_conn_cms_data(); //* cms_data Database connection
    $jobEntry = new PersoJobEntry();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_job_entry_table_data':
            echo $jobEntry->loadJobEntryTableData($perso);
            break;

        case 'load_job_done_table_data':
            echo $jobEntry->loadJobEntryTableDataDone($perso);
            break;

        case 'load_company_name':
            echo $jobEntry->loadSelectValueDefault($perso, 'customer_name', 'bpi_perso_template_assign', 'ASC');
            break;

        case 'load_job_order_number':
            $company_name = trim($_POST['companyname']);
            echo $jobEntry->loadSelectValueCondition($perso, 'jonumber', 'bpi_perso_template_assign', 'customer_name', $company_name, 'DESC');
            break;

        case 'load_job_description':
            $jonumber = trim($_POST['jonumber']);
            echo $jobEntry->loadJobNumberDescriptions($bannerData, $jonumber);
            break;

        case 'load_template_name':
            $company = trim($_POST['company']);
            $jonumber = trim($_POST['jonumber']);
            $orderid = trim($_POST['orderid']);
            echo $jobEntry->loadJobTemplate($perso, $company, $jonumber, $orderid);
            break;

        case 'load_template_process':
            $templateid = trim($_POST['templateid']);
            echo $jobEntry->loadTemplateProcess($perso, $templateid);
            break;

        case 'load_template_material':
            $templateid = trim($_POST['templateid']);
            echo $jobEntry->loadTemplateMaterial($perso, $templateid);
            break;

        case 'load_job_courier':
            $company_name = trim($_POST['company_name']);
            echo $jobEntry->loadJobCourier($cms_data, $company_name);
            break;

        case 'save_job_entry':
            $company = trim($_POST['company']);
            $jonumber = trim($_POST['jonumber']);
            $job_description = trim($_POST['job_description']);
            $orderid = trim($_POST['orderid']);
            $job_filename = trim($_POST['job_filename']);
            $job_template = trim($_POST['job_template']);
            $releaseDate = trim($_POST['releaseDate']) == '' ? NULL : trim($_POST['releaseDate']);
            $dateReceive = trim($_POST['dateReceive']) == '' ? NULL : trim($_POST['dateReceive']);
            $job_cutoff = trim($_POST['job_cutoff']);
            $job_quantity = trim(str_replace(',', '', $_POST['job_quantity']));
            $mode_delivery = trim($_POST['mode_delivery']);
            $pickup_courier = trim($_POST['pickup_courier']) == '' ? NULL : trim($_POST['pickup_courier']);
            $job_chk_hold = trim($_POST['job_chk_hold']);
            echo $jobEntry->saveJobEntry($perso, $company, $jonumber, $orderid, $job_description, $job_filename, $job_quantity, $releaseDate, $dateReceive, $mode_delivery, $job_template, $job_chk_hold, $pickup_courier, $job_cutoff);
            break;

        case 'save_tempprocess_temp_material':
            $jobentryid = trim($_POST['jobentryid']);
            $templateid = trim($_POST['templateid']);
            $job_chk_hold = trim($_POST['job_chk_hold']);
            $jobEntry->saveProcessAndMaterial($perso, $jobentryid, $templateid, $job_chk_hold);
            break;

        case 'load_job_entry_info':
            $jobentryid = trim($_POST['jobentryid']);
            echo $jobEntry->loadJobEntryInfo($perso, $jobentryid);
            break;

        case 'update_job_entry':
            $jobentryid = trim($_POST['jobentryid']);
            $company = trim($_POST['company']);
            $jonumber = trim($_POST['jonumber']);
            $job_description = trim($_POST['job_description']);
            $orderid = trim($_POST['orderid']);
            $job_filename = trim($_POST['job_filename']);
            $job_template = trim($_POST['job_template']);
            $releaseDate = trim($_POST['releaseDate']) == '' ? NULL : trim($_POST['releaseDate']);
            $dateReceive = trim($_POST['dateReceive']) == '' ? NULL : trim($_POST['dateReceive']);
            $date_entry = trim($_POST['date_entry']) == '' ? NULL : trim($_POST['date_entry']);
            $job_cutoff = trim($_POST['job_cutoff']);
            $job_quantity = trim(str_replace(',', '', $_POST['job_quantity']));
            $mode_delivery = trim($_POST['mode_delivery']);
            $pickup_courier = trim($_POST['pickup_courier']) == '' ? NULL : trim($_POST['pickup_courier']);
            $job_status = trim($_POST['job_status']);
            echo $jobEntry->updateJobEntry($perso, $jobentryid, $company, $jonumber, $orderid, $job_description, $job_filename, $job_quantity, $date_entry, $releaseDate, $dateReceive, $mode_delivery, $job_template, $job_status, $pickup_courier, $job_cutoff);
            break;

        case 'delete_job_entry':
            $jobentryid = trim($_POST['jobentryid']);
            $jobEntry->deleteJobEntry($perso, $jobentryid);
            break;

        case 'load_jobentry_process_data':
            $processDivision = trim($_POST['processDivision']);
            $jobentryid = trim($_POST['jobentryid']);
            echo $jobEntry->loadJobEntryProcessData($perso, $processDivision, $jobentryid);
            break;

        case 'load_jobentry_material_data':
            $materialSection = trim($_POST['materialSection']);
            $jobentryid = trim($_POST['jobentryid']);
            echo $jobEntry->loadJobEntryMaterialData($perso, $materialSection, $jobentryid);
            break;

        case 'reset_process_status':
            $jobentryid = trim($_POST['jobentryid']);
            $processid = trim($_POST['processid']);
            $jobEntry->resetProcessData($perso, $jobentryid, $processid);
            break;

        case 'reset_material_status':
            $jobentryid = trim($_POST['jobentryid']);
            $materialid = trim($_POST['materialid']);
            $jobEntry->resetMaterialData($perso, $jobentryid, $materialid);
            break;

        case 'load_process_info':
            $jobentryid = trim($_POST['jobentryid']);
            $processid = trim($_POST['processid']);
            $processDivision = trim($_POST['processDivision']);
            echo $jobEntry->loadProcessDoneInfo($perso, $jobentryid, $processid, $processDivision);
            break;

        case 'load_process_operator':
            $jobentryid = trim($_POST['jobentryid']);
            $processid = trim($_POST['processid']);
            $processSequence = '0';
            $jobCategory = 'Job Entry';
            echo $jobEntry->loadProcessDoneInfoOperator($perso, $jobentryid, $processid, $processSequence, $jobCategory);
            break;

        case 'load_material_info':
            $jobentryid = trim($_POST['jobentryid']);
            $materialid = trim($_POST['materialid']);
            echo $jobEntry->loadMaterialDoneInfo($perso, $jobentryid, $materialid);
            break;

        case 'load_material_operator':
            $jobentryid = trim($_POST['jobentryid']);
            $materialid = trim($_POST['materialid']);
            $jobCategory = 'Job Entry';
            echo $jobEntry->loadMaterialDoneInfoOperator($perso, $jobentryid, $materialid, $jobCategory);
            break;

        case 'archive_job_entry':
            $month_from = trim($_POST['month_from']);
            $month_to = trim($_POST['month_to']);
            echo $jobEntry->archiveJobEntryDone($perso, $perso_archive, $month_from, $month_to);
            break;

        case 'load_input_val':
            $scan_code = trim($_POST['scan_code']);
            echo $jobEntry->loadScanCodeValue($bannerData, $scan_code);
            break;

        case 'load_filename_quantity':
            $childid = trim($_POST['childid']);
            $filename = trim($_POST['filename']);
            echo $jobEntry->loadFilenameQuantity($bannerData, $childid, $filename);
            break;
    }
}
