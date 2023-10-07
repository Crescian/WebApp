<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_assign_cpu_model.class.php';
    $ITAssignCpu = new ITAssignCpu();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadTableAssignCPU':
            $search = $_POST['search']['value'];
            echo $ITAssignCpu->loadTableAssignCPU($php_fetch_itasset_api,  $search);
            break;
        case 'loadInputData':
            echo $ITAssignCpu->loadInputData($php_fetch_bannerweb_api);
            break;
        case 'loadEmployee':
            $deptCode = trim($_POST['deptCode']);
            echo $ITAssignCpu->loadEmployee($php_fetch_bannerweb_api, $deptCode);
            break;
        case 'newAssignCPU':
            $cpuControlNumber = trim($_POST['cpuControlNumber']);
            $employee = trim($_POST['employee']);
            $description = trim($_POST['description']);
            $location = trim($_POST['location']);
            $dateToday = trim($_POST['dateToday']);
            $activePc = trim($_POST['activePc']);
            echo $ITAssignCpu->newAssignCPU($php_insert_itasset_api, $activePc, $php_update_itasset_api, $cpuControlNumber, $employee, $description, $location, $dateToday);
            break;
        case 'fetchByIdAssignCPU':
            $cpuID = trim($_POST['cpuID']);
            echo $ITAssignCpu->fetchByIdAssignCPU($php_fetch_itasset_api, $php_fetch_bannerweb_api, $cpuID);
            break;
        case 'editAssignCPU':
            $cpuControlNumber = trim($_POST['cpuControlNumber']);
            $employee = trim($_POST['employee']);
            $description = trim($_POST['description']);
            $location = trim($_POST['location']);
            $dateToday = trim($_POST['dateToday']);
            echo $ITAssignCpu->editAssignCPU($php_update_itasset_api, $cpuControlNumber, $employee, $description, $location, $dateToday);
            break;
        case 'setTypeControlNumber':
            $type = trim($_POST['type']);
            echo $ITAssignCpu->setTypeControlNumber($php_fetch_itasset_api, $type);
            break;
        case 'deleteCPU':
            $id = trim($_POST['id']);
            echo $ITAssignCpu->deleteCPU($php_update_itasset_api, $id);
            break;
    }
}
