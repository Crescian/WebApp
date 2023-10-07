<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_process_operations_model.class.php';
    $BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
    $bannerData = $conn->db_conn_bannerdata(); //* BannerData Database connection
    $cms_data = $conn->db_conn_cms_data(); //* cms_data Database connection
    $cms = $conn->db_conn_cms(); //* cms Database connection
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $processOperations = new PersoProcessOperations();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_job_process_data':
            $process_section = trim($_POST['process_section']);
            $job_category = trim($_POST['job_category']);
            $access_level = trim($_POST['access_level']);
            $searchValue = $_POST['search']['value'];
            echo $processOperations->loadProcessTableData($perso, $process_section, $job_category, $access_level, $searchValue);
            break;

        case 'load_job_process_table_data_done':
            $process_section = trim($_POST['process_section']);
            echo $processOperations->loadProcessTableDataDone($perso, $process_section);
            break;

        case 'load_vault_table_data':
            $searchValue = $_POST['search']['value'];
            $process_section = trim($_POST['process_section']);
            $access_level = trim($_POST['access_level']);
            echo $processOperations->loadVaultTableData($perso, $process_section, $access_level, $searchValue);
            break;

        case 'load_vault_table_data_done':
            $process_section = 'Vault Section';
            $mode_delivery = trim($_POST['mode_delivery']);
            echo $processOperations->loadVaultTableDataDone($perso, $process_section, $mode_delivery);
            break;

        case 'load_dispatching_table_data':
            $searchValue = $_POST['search']['value'];
            $process_section = trim($_POST['process_section']);
            $job_category = trim($_POST['job_category']);
            $access_level = trim($_POST['access_level']);
            echo $processOperations->loadDispatchingTableData($perso, $cms_data, $process_section, $job_category, $access_level, $searchValue);
            break;

        case 'load_dispatching_dr_list_table_data':
            echo $processOperations->loadDispatchingDRTableData($perso);
            break;

        case 'load_dispatching_done_table_data':
            $process_section = trim($_POST['process_section']);
            echo $processOperations->loadDispatchingTableDataDone($perso, $process_section);
            break;

        case 'load_material_table':
            $searchValue = $_POST['search']['value'];
            $material_section = trim($_POST['material_section']);
            $access_level = trim($_POST['access_level']);
            $material_section = trim($_POST['material_section']);
            echo $processOperations->loadMaterialTableData($perso, $material_section, $access_level, $searchValue);
            break;

        case 'load_material_done_table':
            $material_section = trim($_POST['material_section']);
            echo $processOperations->loadMaterialTableDataDone($perso, $material_section);
            break;

        case 'process_job_start':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $category = trim($_POST['category']);
            echo $processOperations->processJobStart($perso, $jobentry_id, $process_id, $category);
            break;

        case 'process_job_hold':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $operator_remarks = trim($_POST['operator_remarks']);
            $processOperations->processJobHold($perso, $jobentry_id, $process_id, $operator_remarks);
            break;

        case 'process_job_done':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $sequence_number = trim($_POST['sequence_number']);
            $operator_remarks = trim($_POST['operator_remarks']);
            $processOperations->processJobDone($perso, $jobentry_id, $process_id, $sequence_number, $operator_remarks);
            break;

        case 'save_job_process_operator':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $process_section = trim($_POST['process_section']);
            $process_operator = trim($_POST['process_operator']);
            $job_category = 'Job Entry';
            $processOperations->saveProcessOperator($perso, $jobentry_id, $process_id, $process_section, $job_category, $process_operator);
            break;

        case 'load_job_process_operator':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $processSequence = '0';
            $jobCategory = 'Job Entry';
            echo $processOperations->loadJobProcessOperator($perso, $jobentry_id, $process_id, $processSequence, $jobCategory);
            break;

        case 'load_job_process_timeline':
            $process_division = trim($_POST['process_division']);
            $jobentry_id = trim($_POST['jobentry_id']);
            echo $processOperations->loadProcessTimelineTableData($perso, $process_division, $jobentry_id);
            break;

        case 'load_job_process_info':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            echo $processOperations->loadJobProcessInfo($perso, $jobentry_id, $process_id);
            break;

        case 'load_job_process_dispatch_info':
            $jobentryid = trim($_POST['jobentryid']);
            $processid = trim($_POST['processid']);
            $process_section = trim($_POST['process_section']);
            $processSequence = trim($_POST['processSequence']);
            echo $processOperations->loadJobProcessDispatchInfo($perso, $jobentryid, $processid, $process_section, $processSequence);
            break;

        case 'load_service_report_number':
            $customer_name = trim($_POST['customer_name']);
            echo $processOperations->loadServiceReportNo($cms, $customer_name);
            break;

        case 'save_service_report_number':
            $preparedby = trim($_POST['preparedby']);
            $serviceno = trim($_POST['serviceno']);
            $jobentry_id = trim($_POST['jobentry_id']);
            $processOperations->saveServiceReportNo($perso, $preparedby, $serviceno, $jobentry_id);
            break;

        case 'load_dr_number':
            $orderid = trim($_POST['orderid']);
            $jonumber = trim($_POST['jonumber']);
            echo $processOperations->loadDrNumber($bannerData, $orderid, $jonumber);
            break;

        case 'save_dr_assigned':
            $drnumber = trim($_POST['drnumber']);
            $customerName = trim($_POST['customerName']);
            $jonumber = trim($_POST['jonumber']);
            $jobDescription = trim($_POST['jobDescription']);
            $processOperations->saveDrAssigned($perso, $drnumber, $customerName, $jonumber, $jobDescription);
            break;

        case 'save_job_process_dr':
            $jobentryid = trim($_POST['jobentryid']);
            $remarks = trim($_POST['remarks']);
            $drnumber = trim($_POST['drnumber']);
            $processid = trim($_POST['processid']);
            $processSequence = trim($_POST['processSequence']);
            $processOperations->saveJobProcessDR($perso, $jobentryid, $remarks, $drnumber, $processid, $processSequence);
            break;

        case 'load_verify_courier_info':
            $jobentryid = trim($_POST['jobentryid']);
            echo $processOperations->loadVerifyCourierInfo($perso, $cms_data, $jobentryid);
            break;

        case 'save_dr_assign_received_by':
            $drassignid = trim($_POST['drassignid']);
            $dr_assign_received_by = trim($_POST['dr_assign_received_by']);
            $processOperations->saveDrAssignedBy($perso, $drassignid, $dr_assign_received_by);
            break;

        case 'load_material_operator':
            $empno = trim($_POST['empno']);
            echo $processOperations->loadMaterialOperator($BannerWebLive, $empno);
            break;

        case 'material_job_start':
            $jobentryid = trim($_POST['jobentryid']);
            $materialid = trim($_POST['materialid']);
            $material_section = trim($_POST['material_section']);
            $material_operator_remarks = trim($_POST['material_operator_remarks']);
            $emp_name = trim($_POST['emp_name']);
            $processOperations->saveMaterialStart($perso, $jobentryid, $materialid, $material_section, $material_operator_remarks, $emp_name);
            break;
    }
}
