<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_process_monitoring_model.class.php';
    $perso = $conn->db_conn_personalization(); //* Manufacturing Database connection
    $processPlanner = new PersoProcessPlanner();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_job_process_data':
            $process_section = trim($_POST['process_section']);
            $process_category = trim($_POST['process_category']);
            echo $processPlanner->loadProcessTableData($perso, $process_section, $process_category);
            break;

        case 'update_process_planner':
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $process_priority = trim($_POST['process_priority']);
            $process_machine = trim($_POST['process_machine']);
            $release_date = trim($_POST['release_date']);
            $process_instructions = trim($_POST['process_instructions']);
            $processPlanner->updateProcessPlanner($perso, $jobentry_id, $process_id, $process_priority, $process_machine, $release_date, $process_instructions);
            break;

        case 'load_job_process_table_data_done':
            $process_section = trim($_POST['process_section']);
            echo $processPlanner->loadProcessTableDataDone($perso, $process_section);
            break;
    }
}
