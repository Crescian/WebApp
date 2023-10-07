<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_monthly_pir_alarm_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $pirAlarm = new PhdMonthlyPirAlarm();
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
    function scanExisting($inField, $pirid, $PHD)
    {
        $sqlstringScanMainPlant = "SELECT $inField FROM phd_monthly_pir_alarm_header WHERE pirid = :pirid";
        $result_stmt = $PHD->prepare($sqlstringScanMainPlant);
        $result_stmt->bindParam(':pirid', $pirid);
        $result_stmt->execute();
        $result_res = $result_stmt->fetch(PDO::FETCH_ASSOC);
        return $result_res;
    }

    switch ($action) {
        case 'loadPirTable':
            echo $pirAlarm->fetchData($PHD);
            break;
        case 'loadCmsFunction':
            echo $pirAlarm->loadDataCms($BannerWebLive);
            break;
        case 'generate_checklist':
            echo $pirAlarm->generateData($PHD);
            break;
        case 'save_header':
            $title = 'Monthly Duress Checklist For The Month Of ' . date('F');
            $prepared_by = trim($_POST['prepared_by']);
            $checkedBy = trim($_POST['checkedBy']);
            $notedBy = trim($_POST['notedBy']);
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Checked Signature ========
            $checkedBySignature = fetchSignature($checkedBy, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($notedBy, $BannerWebLive);
            echo $pirAlarm->saveDataHeader($PHD, $title, $prepared_by, $checkedBy, $notedBy, $preparedBySignature, $checkedBySignature, $notedBySignature, $date_created);
            break;
        case 'save_detail':
            $performedBy = trim($_POST['performedBy']);
            $strLocation = trim($_POST['strLocation']);
            $strMotion = trim($_POST['strMotion']);
            $strNoMotion = trim($_POST['strNoMotion']);
            $strDual = trim($_POST['strDual']);
            $generateRefno = trim($_POST['generateRefno']);
            $pirid = trim($_POST['pirid']);

            $strbtnActivate = trim($_POST['strbtnActivate']);
            $strbtnActivate = substr(trim($_POST['strbtnActivate']), 0, 4);
            $time_activated = $strbtnActivate == '' ? NULL : date_format(date_create($strbtnActivate), "Y-m-d H:i:s");
            if ($strbtnActivate == '') {
                $performedBy = null;
                $date = null;
            } else {
                $performedBy = trim($_POST['performedBy']);
                $date = date("Y-m-d");
            }
            echo $pirAlarm->saveDataDetail($PHD, $performedBy, $strLocation, $strMotion, $strNoMotion, $strDual, $generateRefno, $pirid, $time_activated, $date);
            break;
        case 'preview_details':
            $pirid = trim($_POST['pirid']);
            echo $pirAlarm->previewData($PHD, $pirid);
            break;
        case 'update_header':
            $prepared_by = trim($_POST['prepared_by']);
            $pirid = trim($_POST['pirid']);
            $result_res_sign = fetchSignature($prepared_by, $BannerWebLive);
            $result_res2 = scanExisting('performed2', $pirid, $PHD);
            $result_res3 = scanExisting('performed3', $pirid, $PHD);
            echo $pirAlarm->updateDataHeader($PHD, $prepared_by, $pirid, $result_res_sign, $result_res2, $result_res3, $date_created);
            break;
        case 'update_detail':
            $strMotion = trim($_POST['strMotion']);
            $strNoMotion = trim($_POST['strNoMotion']);
            $strDual = trim($_POST['strDual']);
            $userPrepared = trim($_POST['userPrepared']);
            $strDetail = trim($_POST['strDetail']);
            $performedBy = trim($_POST['performedBy']);

            $strbtnActivate = trim($_POST['strbtnActivate']);
            $strBtnActivate = substr($strbtnActivate, 0, 4);
            $strBtnActivateFinal = date_format(date_create($strBtnActivate), "Y-m-d H:i:s");
            echo $strBtnActivateFinal;
            echo $pirAlarm->updateDataDetail($PHD, $strMotion, $strNoMotion, $strDual, $userPrepared, $strDetail, $performedBy, $strbtnActivate, $strBtnActivate, $strBtnActivateFinal, $date_created);
            break;
        case 'delete_details':
            $pirid = trim($_POST['pirid']);
            echo $pirAlarm->deleteData($PHD, $pirid);
            break;
        case 'preview_checked_by':
            $pirid = trim($_POST['pirid']);
            echo $pirAlarm->previewDataCheckedBy($PHD, $pirid);
            break;
    }
}
