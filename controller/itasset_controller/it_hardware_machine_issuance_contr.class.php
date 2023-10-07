<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_hardware_machine_issuance_model.class.php';
    $WHPO = $conn->db_conn_whpo(); //* Physical Security Database connection
    $ITHarwareMachineIssued = new ITHarwareMachineIssued();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadTableMachineIssuance':
            $search = $_POST['search']['value'];
            $filterValue = trim($_POST['filterValue']);
            $machine = trim($_POST['machine']);
            echo $ITHarwareMachineIssued->loadTableMachineIssuance($php_fetch_itasset_api, $filterValue, $machine, $search);
            break;
        case 'loadInputData':
            echo $ITHarwareMachineIssued->loadInputData($php_fetch_bannerweb_api, $php_fetch_itasset_api);
            break;
        case 'newMachineIssuance':
            $machine = trim($_POST['machine']);
            $item = trim($_POST['item']);
            $barcodeNumber = trim($_POST['barcodeNumber']);
            $issuer = trim($_POST['issuer']);
            $dateIssued = trim($_POST['dateIssued']);
            echo $ITHarwareMachineIssued->newMachineIssuance($php_fetch_itasset_api, $php_insert_itasset_api, $WHPO, $machine, $item, $barcodeNumber, $issuer, $dateIssued);
            break;
        case 'actionMachineIssuance':
            $id = trim($_POST['id']);
            $status = trim($_POST['status']);
            echo $ITHarwareMachineIssued->actionMachineIssuance($php_update_itasset_api, $id, $status);
            break;
        case 'getDescription':
            $barcode = trim($_POST['barcode']);
            echo $ITHarwareMachineIssued->getDescription($WHPO, $barcode);
            break;
    }
}
