<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_daily_room_inspection_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $dailyRoom = new PhdDailyRoom();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date_created = date("Y-m-d");

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

    function scanExisting($inField, $dailyroomid, $PHD)
    {
        $sqlstringScanMainPlant = "SELECT $inField FROM phd_dr_inspection_header WHERE dailyroomid = :dailyroomid";
        $result_stmt = $PHD->prepare($sqlstringScanMainPlant);
        $result_stmt->bindParam(':dailyroomid', $dailyroomid);
        $result_stmt->execute();
        $result_res = $result_stmt->fetch(PDO::FETCH_ASSOC);
        return $result_res;
    }

    switch ($action) {
        case 'load_daily_room_table':
            echo $dailyRoom->fetchData($PHD);
            break;
        case 'generate_checklist':
            echo $dailyRoom->generateData($PHD);
            break;
        case 'preview_checklist':
            $dailyroomid = trim($_POST['dailyroomid']);
            echo $dailyRoom->previewData($PHD, $dailyroomid);
            break;
        case 'save_header':
            $prepared_by = trim($_POST['prepared_by']);
            $designation = trim($_POST['designation']);
            $notedBy = trim($_POST['notedBy']);
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($notedBy, $BannerWebLive);
            echo $dailyRoom->saveHeaderData($PHD, $preparedBySignature, $notedBySignature, $prepared_by, $designation, $notedBy, $date_created);
            break;
        case 'save_details':
            $generateRefno = trim($_POST['generateRefno']);
            $strRoom = trim($_POST['strRoom']);
            $strCategory = trim($_POST['strCategory']);
            $strAircon = trim($_POST['strAircon']);
            $strlight = trim($_POST['strlight']);
            $strDoor = trim($_POST['strDoor']);
            $strOutlet = trim($_POST['strOutlet']);
            $strRemarks = trim($_POST['strRemarks']);
            $dailyroomid = trim($_POST['dailyroomid']);

            $strBtnActivate = substr(trim($_POST['strBtnActivate']), 0, 4);
            $dr_time_activated = $strBtnActivate == '' ? NULL : date_format(date_create($strBtnActivate), "Y-m-d H:i:s");
            if ($strBtnActivate == '') {
                $details_date_created = NULL;
                $performedBy = NULL;
            } else {
                $performedBy = trim($_POST['performedBy']);
                $details_date_created = date("Y-m-d");
            }
            echo $dailyRoom->saveDetailsData($PHD, $generateRefno, $strRoom, $strCategory, $strAircon, $strlight, $strDoor, $strOutlet, $strRemarks, $dailyroomid, $dr_time_activated, $details_date_created, $performedBy);
            break;
        case 'delete_details':
            $dailyid = trim($_POST['dailyid']);
            echo $dailyRoom->deleteData($PHD, $dailyid);
            break;
        case 'update_header':
            $logged_user = trim($_POST['logged_user']);
            $designation = trim($_POST['designation']);
            $dailyroomid = trim($_POST['dailyroomid']);
            $result_res_main = fetchSignature($logged_user, $BannerWebLive);
            $result_res_admin = fetchSignature($logged_user, $BannerWebLive);
            $result_res_warehouse = fetchSignature($logged_user, $BannerWebLive);
            $result_res_main1 = scanExisting('dr_prepared_main', $dailyroomid, $PHD);
            $result_res_main2 = scanExisting('dr_prepared_main2', $dailyroomid, $PHD);
            $result_res_main3 = scanExisting('dr_prepared_main3', $dailyroomid, $PHD);
            $result_res_admin_lobby1 = scanExisting('dr_prepared_admin_lobby', $dailyroomid, $PHD);
            $result_res_admin_lobby2 = scanExisting('dr_prepared_admin_lobby2', $dailyroomid, $PHD);
            $result_res_admin_lobby3 = scanExisting('dr_prepared_admin_lobby3', $dailyroomid, $PHD);
            $result_res_warehouse_2_31 = scanExisting('dr_prepared_warehouse_2_3', $dailyroomid, $PHD);
            $result_res_warehouse_2_32 = scanExisting('dr_prepared_warehouse_2_32', $dailyroomid, $PHD);
            $result_res_warehouse_2_33 = scanExisting('dr_prepared_warehouse_2_33', $dailyroomid, $PHD);
            echo $dailyRoom->updateHeaderData($PHD, $logged_user, $designation, $dailyroomid, $result_res_main, $result_res_admin, $result_res_warehouse, $date_created, $result_res_main1, $result_res_main2, $result_res_main3, $result_res_admin_lobby1, $result_res_admin_lobby2, $result_res_admin_lobby3, $result_res_warehouse_2_31, $result_res_warehouse_2_32, $result_res_warehouse_2_33);
            break;
        case 'update_details':
            $strPrepared = trim($_POST['strPrepared']);
            $strDetails = trim($_POST['strDetails']);
            $strCategory = trim($_POST['strCategory']);
            $strBtnActivate = substr(trim($_POST['strBtnActivate']), 0, 4);
            $strBtnActivate = $strBtnActivate == '' ? NULL : date_format(date_create($strBtnActivate), "Y-m-d H:i:s");
            $strAircon = trim($_POST['strAircon']);
            $strlight = trim($_POST['strlight']);
            $strDoor = trim($_POST['strDoor']);
            $strOutlet = trim($_POST['strOutlet']);
            $strRemarks = trim($_POST['strRemarks']);
            $dailyroomid = trim($_POST['dailyroomid']);
            $performedBy = trim($_POST['performedBy']);
            echo $dailyRoom->updateDetailsData($PHD, $strPrepared, $strDetails, $strCategory, $strBtnActivate, $strAircon, $strlight, $strDoor, $strOutlet, $strRemarks, $performedBy, $dailyroomid);
            break;
        case 'load-noted-by':
            $dailyroomId = trim($_POST['dailyroomId']);
            echo $dailyRoom->previewDataNoted($PHD, $dailyroomId);
            break;
    }
}
