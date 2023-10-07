<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_rsm_monitoring_model.class.php';
    $WHPO = $conn->db_conn_whpo(); //* Physical Security Database connection
    $ITRsmMonitoring = new ITRsmMonitoring();
    $action = trim($_POST['action']);
    switch ($action) {
        case 'load_table_rsm':
            $statusVal = trim($_POST['statusVal']) ?? 'On Hold';
            $searchValue = $_POST['search']['value'];
            echo $ITRsmMonitoring->load_table_rsm($WHPO, $statusVal, $searchValue);
            break;
        case 'load_rsm_count':
            echo $ITRsmMonitoring->load_rsm_count($WHPO);
            break;
    }
}
