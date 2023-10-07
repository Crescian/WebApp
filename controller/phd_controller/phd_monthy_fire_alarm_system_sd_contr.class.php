<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_monthy_fire_alarm_system_sd_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $monthlyFireSd = new PhdMonthlyFireSd();
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
    function scanExisting($inField, $firealarmsmokeid, $PHD)
    {
        $sqlstringScanMainPlant = "SELECT $inField FROM phd_monthly_fire_alarm_smoke_header WHERE firealarmsmokeid = :firealarmsmokeid";
        $result_stmt = $PHD->prepare($sqlstringScanMainPlant);
        $result_stmt->bindParam(':firealarmsmokeid', $firealarmsmokeid);
        $result_stmt->execute();
        $result_res = $result_stmt->fetch(PDO::FETCH_ASSOC);
        return $result_res;
    }

    switch ($action) {
        case 'load_fire_table':
            echo $monthlyFireSd->fetchData($PHD);
            break;
        case 'generate_checklist':
            echo $monthlyFireSd->generateData($PHD);
            break;
        case 'loadCmsFunction':
            echo $monthlyFireSd->loadDataCms($BannerWebLive);
            break;
        case 'save_header':
            $title = 'Monthly Fire Alarm System Checklist For The Month Of ' . date('F');
            $prepared_by = trim($_POST['prepared_by']);
            $checkedBy = trim($_POST['checkedBy']);
            $notedBy = trim($_POST['notedBy']);
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Checked Signature ========
            $checkedBySignature = fetchSignature($checkedBy, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($notedBy, $BannerWebLive);
            echo $monthlyFireSd->saveDataHeader($PHD, $title, $prepared_by, $checkedBy, $notedBy, $preparedBySignature, $checkedBySignature, $notedBySignature, $date_created);
            break;
        case 'save_detail':
            $performedBy = trim($_POST['performedBy']);
            $strLocation = trim($_POST['strLocation']);
            $strZone = trim($_POST['strZone']);
            $strUnits = trim($_POST['strUnits']);
            $strCategory = trim($_POST['strCategory']);
            $strWorking = trim($_POST['strWorking']);
            $strRemark = trim($_POST['strRemark']);
            $fireid = trim($_POST['fireid']);

            $time_activated = trim($_POST['strbtnActivate']) == '' ? NULL : trim($_POST['strbtnActivate']);

            if ($time_activated == null) {
                $performedBy = null;
                $date = null;
                $preparedBySignature = null;
            } else {
                $date = date("Y-m-d");
                //* ======== Fetch Employee Prepared Signature ========
                $preparedBySignature = fetchSignature($performedBy, $BannerWebLive);
            }
            echo $monthlyFireSd->saveDataDetail($PHD, $performedBy, $strLocation, $strZone, $strUnits, $strCategory, $strWorking, $strRemark, $fireid, $time_activated, $date, $preparedBySignature);
            break;
        case 'update_header':
            $prepared_by = trim($_POST['prepared_by']);
            $fireid = trim($_POST['fireid']);
            $result_res_sign = fetchSignature($prepared_by, $BannerWebLive);
            $result_res2 = scanExisting('prepared_by2', $fireid, $PHD);
            $result_res3 = scanExisting('prepared_by3', $fireid, $PHD);
            echo $monthlyFireSd->updateDataHeader($PHD, $prepared_by, $fireid, $result_res_sign, $result_res2, $result_res3, $date_created);
            break;
        case 'update_detail':
            $strDetails = trim($_POST['strDetails']);
            $strPrepared = trim($_POST['strPrepared']);
            $strbtnActivate = trim($_POST['strbtnActivate']);
            $strLocation = trim($_POST['strLocation']);
            $strUnits = trim($_POST['strUnits']);
            $strZone = trim($_POST['strZone']);
            $strCategory = trim($_POST['strCategory']);
            $strWorking = trim($_POST['strWorking']);
            $strRemark = trim($_POST['strRemark']);
            $performedBy = trim($_POST['performedBy']);
            $performedBySignature = fetchSignature($performedBy, $BannerWebLive);
            echo $monthlyFireSd->updateDataDetail($PHD, $strDetails, $strPrepared, $strbtnActivate, $strLocation, $strUnits, $strZone, $strCategory, $strWorking, $strRemark, $performedBy, $performedBySignature);
            break;
        case 'preview_detail':
            $fireid = trim($_POST['fireid']);
            echo $monthlyFireSd->previewData($PHD, $fireid);
            break;
        case 'delete_detail':
            $fireid = trim($_POST['fireid']);
            echo $monthlyFireSd->deleteData($PHD, $fireid);
            break;
        case 'preview_checked_by':
            $fireid = trim($_POST['fireid']);
            echo $monthlyFireSd->previewDataCheckedBy($PHD, $fireid);
            break;
    }
}
