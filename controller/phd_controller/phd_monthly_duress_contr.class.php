<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_monthly_duress_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $duress = new PhdDuress();
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
    function scanExisting($inField, $duressid, $PHD)
    {
        $sqlstringScanMainPlant = "SELECT $inField FROM phd_monthly_duress_header WHERE duressid = :duressid";
        $result_stmt = $PHD->prepare($sqlstringScanMainPlant);
        $result_stmt->bindParam(':duressid', $duressid);
        $result_stmt->execute();
        $result_res = $result_stmt->fetch(PDO::FETCH_ASSOC);
        return $result_res;
    }


    switch ($action) {
        case 'loadDuressTable':
            echo $duress->fetchData($PHD);
            break;
        case 'load_cms':
            echo $duress->cmsData($BannerWebLive);
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
            echo $duress->saveDataHeader($PHD, $title, $prepared_by, $checkedBy, $notedBy, $preparedBySignature, $checkedBySignature, $notedBySignature, $date_created);
            break;
        case 'generate_checklist':
            echo $duress->generateData($PHD);
            break;
        case 'save_detail':
            $duressid = trim($_POST['duressid']);
            $strLocation = trim($_POST['strLocation']);
            $strActive = trim($_POST['strActive']);
            $strOutsource = trim($_POST['strOutsource']);
            $strResponse = trim($_POST['strResponse']);

            $strbtnActivate = trim($_POST['strbtnActivate']);
            $strbtnActivate = substr(trim($_POST['strbtnActivate']), 0, 4);
            $time_activated = $strbtnActivate == '' ? NULL : date_format(date_create($strbtnActivate), "Y-m-d H:i:s");

            $strbtnVerified = trim($_POST['strbtnVerified']);
            $strbtnVerified = substr(trim($_POST['strbtnVerified']), 0, 4);
            $time_verified = $strbtnVerified == '' ? NULL : date_format(date_create($strbtnVerified), "Y-m-d H:i:s");

            if ($time_activated == NULL) {
                $performedBy = null;
                $generateRefno = null;
                $date = null;
            } else {
                $performedBy = trim($_POST['performedBy']);
                $generateRefno = trim($_POST['generateRefno']);
                $date = date("Y-m-d");
            }

            if ($time_verified == NULL) {
                $verifiedBy = null;
            } else {
                $verifiedBy = trim($_POST['performedBy']);
            }
            echo $duress->saveDataDetails($PHD, $verifiedBy, $duressid, $strLocation, $strActive, $strOutsource, $strResponse, $time_activated, $time_verified, $generateRefno, $performedBy, $date);
            break;
        case 'preview_details':
            $duressid = trim($_POST['duressid']);
            echo $duress->previewData($PHD, $duressid);
            break;
        case 'delete_details':
            $duressid = trim($_POST['duressid']);
            echo $duress->deleteData($PHD, $duressid);
            break;
        case 'update_header':
            $prepared_by = trim($_POST['prepared_by']);
            $duressid = trim($_POST['duressid']);
            $result_res_sign = fetchSignature($prepared_by, $BannerWebLive);
            $result_res2 = scanExisting('performed2', $duressid, $PHD);
            $result_res3 = scanExisting('performed3', $duressid, $PHD);
            echo $duress->updateDataHeader($PHD, $prepared_by, $result_res_sign, $result_res2, $result_res3, $duressid, $date_created);
            break;
        case 'update_detail':
            $strActive = trim($_POST['strActive']);
            $strOutsource = trim($_POST['strOutsource']);
            $strResponse = trim($_POST['strResponse']);
            $duressid = trim($_POST['duressid']);
            $userPrepare = trim($_POST['userPrepare']);
            $performedBy = trim($_POST['performedBy']);
            $strbtnActivate = trim($_POST['strbtnActivate']);
            $strbtnActivate = substr($strbtnActivate, 0, 4);
            $strBtnActivateFinal = date_format(date_create($strbtnActivate), "Y-m-d H:i:s");
            $strbtnVerified = trim($_POST['strbtnVerified']);
            $strbtnVerified = substr($strbtnVerified, 0, 4);
            $strbtnVerifiedFinal = date_format(date_create($strbtnVerified), "Y-m-d H:i:s");
            echo $duress->updateDataDetail($PHD, $performedBy, $strActive, $strOutsource, $strResponse, $duressid, $userPrepare, $strbtnActivate, $strBtnActivateFinal, $strbtnVerified, $strbtnVerifiedFinal, $date_created);
            break;
        case 'preview_checked_by':
            $duressid = trim($_POST['duressid']);
            echo $duress->previewDataCheckedBy($PHD, $duressid);
            break;
    }
}
