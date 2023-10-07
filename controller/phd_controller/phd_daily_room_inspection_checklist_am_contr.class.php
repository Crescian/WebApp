<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_daily_room_inspection_checklist_am_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $dailyRoomAm = new PhdDailyRoomAm();
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
            echo $dailyRoomAm->fetchData($PHD);
            break;
        case 'save_header':
            $drTitle = 'Daily Room Inspection For The Month Of ' . date('F');
            $prepared_by = trim($_POST['prepared_by']);
            $designation = trim($_POST['designation']);
            $noted_by = trim($_POST['notedBy']);
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($noted_by, $BannerWebLive);
            echo $dailyRoomAm->saveDataHeader($PHD, $drTitle, $prepared_by, $designation, $noted_by, $preparedBySignature, $notedBySignature, $date_created);
            break;
        case 'generate_details':
            echo $dailyRoomAm->generateData($PHD);
            break;
        case 'save_detail':
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
            echo $dailyRoomAm->saveDetailsData($PHD, $generateRefno, $strRoom, $strCategory, $strAircon, $strlight, $strDoor, $strOutlet, $strRemarks, $dailyroomid, $dr_time_activated, $details_date_created, $performedBy);
            break;
        case 'preview_details':
            $dailyroomid = trim($_POST['dailyroomid']);
            echo $dailyRoomAm->previewData($PHD, $dailyroomid);
            break;
        case 'delete_details':
            $dailyid = trim($_POST['dailyid']);
            echo $dailyRoomAm->deleteData($PHD, $dailyid);
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
            echo $dailyRoomAm->updateDataHeader($PHD, $logged_user, $designation, $dailyroomid, $result_res_main, $result_res_admin, $result_res_warehouse, $result_res_main1, $result_res_main2, $result_res_main3, $result_res_admin_lobby1, $result_res_admin_lobby2, $result_res_admin_lobby3, $result_res_warehouse_2_31, $result_res_warehouse_2_32, $result_res_warehouse_2_33, $date_created);
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
            $date = date("Y-m-d H:i:s");
            echo $dailyRoomAm->updateDataDetail($PHD, $strPrepared, $strDetails, $strCategory, $strBtnActivate, $strAircon, $strlight, $strDoor, $strOutlet, $strRemarks, $dailyroomid, $date);
            break;
        case 'load-noted-by':
            $dailyroomid = trim($_POST['dailyroomid']);
            echo $dailyRoomAm->previewDataNoted($PHD, $dailyroomid);
            break;
    }
}
