<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_authorization_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $authroizedModule = new PhdAuthorizedModule();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date_created = date("Y-m-d");

    switch ($action) {
        case 'load_checked_by_table':
            echo $authroizedModule->fetchCheckByData($PHD);
            break;
        case 'load_noted_by_table':
            echo $authroizedModule->fetchNotedByData($PHD);
            break;
        case 'save_checked_noted_by_employee':
            $employee_name = trim($_POST['employee_name']);
            $inTable = trim($_POST['inTable']);
            $inField = trim($_POST['inField']);
            echo $authroizedModule->saveDataCheckAndNoted($PHD, $employee_name, $inTable, $inField);
            break;
        case 'remove_checked_by_employee':
            $phdcheckedbyid = trim($_POST['phdcheckedbyid']);
            echo $authroizedModule->deleteDataChecked($PHD, $phdcheckedbyid);
            break;
        case 'remove_noted_by_employee':
            $phdnotedbyid = trim($_POST['phdnotedbyid']);
            echo $authroizedModule->deleteDataNoted($PHD, $phdnotedbyid);
            break;
    }
}
