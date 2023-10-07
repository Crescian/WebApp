<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_defective_items_model.class.php';
    $WHPO = $conn->db_conn_whpo(); //* Physical Security Database connection
    $ITDefectiveItems = new ITDefectiveItems();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'generate_defective_refno':
            $inField = trim($_POST['inField']);
            $inTable = trim($_POST['inTable']);
            echo $ITDefectiveItems->generate_defective_refno($php_fetch_itasset_api, $inField, $inTable);
            break;
        case 'loadTableDefective':
            $search = $_POST['search']['value'];
            $filter = trim($_POST['filter']);
            echo $ITDefectiveItems->loadTableDefective($php_fetch_itasset_api, $filter, $search);
            break;
        case 'recycleDefective':
            $filter = trim($_POST['filter']);
            $control_no = trim($_POST['control_no']);
            echo $ITDefectiveItems->recycleDefective($php_update_itasset_api, $control_no, $filter);
            break;
    }
}
