<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_hardware_issued_model.class.php';
    $WHPO = $conn->db_conn_whpo(); //* Physical Security Database connection
    $ITHardwareIssue = new ITHarwareIssued();
    $action = trim($_POST['action']);
    
    switch ($action) {
        case 'loadTableHardwareIssuance':
            $search = $_POST['search']['value'];
            $filterValue = trim($_POST['filterValue']);
            $filterValue = !empty($filterValue) ? $filterValue : "Issued', 'Retrieved', 'Returned', 'Defective";
            $controlno = trim($_POST['controlno']);
            echo $ITHardwareIssue->loadTableHardwareIssuance( $php_fetch_itasset_api, $filterValue, $controlno, $search);
            break;
        case 'loadInputData':
            echo $ITHardwareIssue->loadInputData($php_fetch_bannerweb_api, $php_fetch_itasset_api);
            break;
        case 'loadEmployee':
            $deptCode = trim($_POST['deptCode']);
            echo $ITHardwareIssue->loadEmployee($php_fetch_bannerweb_api, $deptCode);
            break;
        case 'loadCpuControlNo':
            $employeeName = trim($_POST['employeeName']);
            echo $ITHardwareIssue->loadCpuControlNo($php_fetch_itasset_api,  $employeeName);
            break;
        case 'newHardwareIssuance':
            $cpuControlNumber = trim($_POST['cpuControlNumber']);
            $barcodeNumber = trim($_POST['barcodeNumber']);
            $issuer = trim($_POST['issuer']);
            $dateIssued = trim($_POST['dateIssued']);
            $item = trim($_POST['item']);
            echo $ITHardwareIssue->newHardwareIssuance($php_fetch_itasset_api, $php_insert_itasset_api,  $WHPO, $cpuControlNumber, $item, $barcodeNumber, $issuer, $dateIssued);
            break;
        case 'actionHardwareIssuance':
            $id = trim($_POST['id']);
            $status = trim($_POST['status']);
            echo $ITHardwareIssue->actionHardwareIssuance($php_update_itasset_api,  $id, $status);
            break;
        case 'getDescription':
            $barcode = trim($_POST['barcode']);
            echo $ITHardwareIssue->getDescription($WHPO, $barcode);
            break;
    }
}
