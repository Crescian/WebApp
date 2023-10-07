<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/prod_monitoring_model/prod_machine_name_model.class.php';
    $prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection

    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    $machine = new ProdMachineName();

    switch ($action) {
        case 'load_machine_list_table':
            $machine->fetchData($prod);
            echo json_encode($result);
            break;

        case 'insert_machine_name':
            $machine_name = trim($_POST['machine_name']);
            $result = $machine->insertData($prod, $machine_name);
            echo json_encode($result);
            break;

        case 'update_machine_name':
            $machine->updateData($prod, $machineid);
            break;

        case 'delete_machine_name':
            $machine->deleteData($prod, $machineid);
            break;
    }
}
