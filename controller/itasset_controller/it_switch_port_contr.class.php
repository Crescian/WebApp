<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_switch_port_model.class.php';
    $ITSwitchAndPort = new ITSwitchAndPort();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_table_switch':
            $searchValue = $_POST['search']['value'];
            echo $ITSwitchAndPort->load_table_switch($php_fetch_itasset_api, $searchValue);
            break;
        case 'loadSwitch':
            echo $ITSwitchAndPort->loadSwitch($php_fetch_itasset_api);
            break;
        case 'switchAssign':
            $switch = trim($_POST['switch']);
            $port = trim($_POST['port']);
            echo $ITSwitchAndPort->switchAssign($php_insert_itasset_api, $switch, $port);
            break;
        case 'getThePort':
            $letter = trim($_POST['letter']);
            echo $ITSwitchAndPort->getThePort($php_fetch_itasset_api, $letter);
            break;
        case 'saveAssign':
            $lan_cable = trim($_POST['lan_cable']);
            $location = trim($_POST['location']);
            $switchLocation = trim($_POST['switchLocation']);
            $portLocation = trim($_POST['portLocation']);
            echo $ITSwitchAndPort->saveAssign($php_insert_itasset_api, $lan_cable, $location, $switchLocation, $portLocation);
            break;
        case 'editFunction':
            $id = trim($_POST['id']);
            echo $ITSwitchAndPort->editFunction($php_fetch_itasset_api, $id);
            break;
        case 'updateAssign':
            $status = trim($_POST['status']);
            $id = trim($_POST['id']);
            $lan_cable = trim($_POST['lan_cable']);
            $location = trim($_POST['location']);
            $switch = trim($_POST['switch']);
            $port = trim($_POST['port']);
            $portReplica = trim($_POST['portReplica']);
            echo $ITSwitchAndPort->updateAssign($php_update_itasset_api, $php_insert_itasset_api, $lan_cable, $location, $switch, $port, $portReplica, $status, $id);
            break;
    }
}
