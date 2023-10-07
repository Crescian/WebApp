<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/prod_monitoring_model/prod_process_planner_model.class.php';
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection
    $processPlanner = new ProdProcessPlanner();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_job_process_data':
            $process_section = trim($_POST['process_section']);
            echo $processPlanner->fetchData($prod, $process_section);
            break;

        case 'load_process_machine':
            $process_section = trim($_POST['process_section']);
            echo $processPlanner->fetchProcessMachine($prod, $process_section);
            break;

        case 'update_process_planner':
            $process_priority = trim($_POST['process_priority']);
            $process_machine = trim($_POST['process_machine']);
            $start_date = trim($_POST['start_date']);
            $end_date = trim($_POST['end_date']);
            $instructions = trim($_POST['instructions']);
            $jobentry_id = trim($_POST['jobentry_id']);
            $process_id = trim($_POST['process_id']);
            $processPlanner->updateProcessPlanner($prod, $process_machine, $process_priority, $instructions, $start_date, $end_date,  $jobentry_id, $process_id);
            break;
    }
}
