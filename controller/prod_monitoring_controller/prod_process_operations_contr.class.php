<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/prod_monitoring_model/prod_process_operations_model.class.php';
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection
    $processOperations = new ProdProcessOperations();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_job_process_data':
            $process_section = trim($_POST['process_section']);
            $access_level = trim($_POST['access_level']);
            echo $processOperations->fetchData($prod, $process_section, $access_level);
            break;

        case 'process_job_start':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $processOperations->processJobStart($prod, $jobentry_id, $process_id);
            break;

        case 'process_job_hold':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $operator_remarks = trim($_POST['operator_remarks']);
            $processOperations->processJobHold($prod, $jobentry_id, $process_id, $operator_remarks);
            break;

        case 'process_job_resume':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $processOperations->processJobResume($prod, $jobentry_id, $process_id);
            break;
    }
}
