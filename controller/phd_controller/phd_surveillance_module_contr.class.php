<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_surveillance_module_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $surveillance = new PhdSurveillance();
    $action = trim($_POST['action']);
    $date_created = date("Y-m-d");
    date_default_timezone_set('Asia/Manila');

    function fetchSignature($emp_name, $BannerWebLive)
    {
        $empSignature = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = :emp_name";
        $empSignature_stmt = $BannerWebLive->prepare($empSignature);
        $empSignature_stmt->bindParam(':emp_name', $emp_name);
        $empSignature_stmt->execute();
        $empSignature_row = $empSignature_stmt->fetch(PDO::FETCH_ASSOC);
        return $empSignature_row['employee_signature'];
        $BannerWebLive = null; //* ======== Close Connection ========
    }

    switch ($action) {
        case 'load_surveillance_table':
            echo $surveillance->fetchData($PHD);
            break;
        case 'save_data':
            $surveillance_name = trim($_POST['surveillance_name']);
            echo $surveillance->saveData($PHD, $surveillance_name);
            break;
        case 'delete_data':
            $id = trim($_POST['id']);
            echo $surveillance->deleteData($PHD, $id);
            break;
        case 'preview_data':
            $id = trim($_POST['id']);
            echo $surveillance->previewData($PHD, $id);
            break;
        case 'update_data':
            $id = trim($_POST['id']);
            $surveillance_name = trim($_POST['surveillance_name']);
            echo $surveillance->updateData($PHD, $surveillance_name, $id);
            break;
    }
}
