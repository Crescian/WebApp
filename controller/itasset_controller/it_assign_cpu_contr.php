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
    }
}
