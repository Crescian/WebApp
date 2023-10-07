<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_quarterly_vibration_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $quarterlyVibration = new PhdQuarterlyVibration();
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
        case 'loadQuarterTable':
            echo $quarterlyVibration->fetchData($PHD, $php_fetch_phd_api);
            break;
        case 'generate_particular':
            echo $quarterlyVibration->generateData($PHD, $php_fetch_phd_api);
            break;
        case 'generate_reference':
            echo $quarterlyVibration->generateDataReference($PHD, $php_fetch_phd_api);
            break;
        case 'save_header':
            $qvsTitle = 'Quarterly Vibration Checklist For The Month Of ' . date('F');
            $dateToday = date("Y-m-d");
            $performedBy = trim($_POST['performedBy']);
            $checkedBy = trim($_POST['checkedBy']);
            $notedBy = trim($_POST['notedBy']);
            $refno = trim($_POST['refno']);
            //* ======== Fetch Employee Perform Signature ========
            $result_Res = fetchSignature($performedBy, $BannerWebLive);
            //* ======== Fetch Employee Checked Signature ========
            $result_check_Res = fetchSignature($checkedBy, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $result_noted_Res = fetchSignature($notedBy, $BannerWebLive);
            echo $quarterlyVibration->saveDataHeader($PHD, $BannerWebLive, $qvsTitle, $dateToday, $performedBy, $checkedBy, $notedBy, $refno, $result_Res, $result_check_Res, $result_noted_Res);
            break;
        case 'save_detail':
            $qvsid = trim($_POST['qvsid']);
            $strLocationName = trim($_POST['strLocation']);
            $strParticular = trim($_POST['strParticular']);
            $strAction = trim($_POST['strAction']);
            $strTimeStampFormat = trim($_POST['strTimeStampFormat']);

            $strTime = substr(trim($_POST['strTime']), 0, 3);
            if ($strTimeStampFormat == '') {
                $strTimeStampFormat = NULL;
                $logged_user = NULL;
                $refno = NULL;
            } else {
                $logged_user = trim($_POST['logged_user']);
                $refno = trim($_POST['refno']);
            }
            echo $quarterlyVibration->saveDataDetails($PHD, $php_insert_phd_api, $logged_user, $refno, $qvsid, $strLocationName, $strParticular, $strAction, $strTimeStampFormat);
            break;
        case 'delete_quarter':
            $id = trim($_POST['id']);
            echo $quarterlyVibration->deleteData($PHD, $php_update_phd_api, $id);
            break;
        case 'preview-data':
            $id = trim($_POST['id']);
            echo $quarterlyVibration->previewData($PHD, $php_fetch_phd_api, $id);
            break;
        case 'update_detail':
            $strQvsId = trim($_POST['strQvsId']);
            $strAction = trim($_POST['strAction']);
            $strTimeStampFormat = trim($_POST['strTimeStampFormat']);
            $refno = trim($_POST['refno']);
            $strPrepared = trim($_POST['strPrepared']);
            $strActivate = trim($_POST['strActivate']);
            $strVerify = trim($_POST['strVerify']);
            $logged_user = trim($_POST['logged_user']);

            if ($strTimeStampFormat == '') {
                $strTimeStampFormat = NULL;
                $logged_user = NULL;
                $refno = NULL;
            } else {
                $logged_user = trim($_POST['logged_user']);
                $refno = trim($_POST['refno']);
            }

            echo $quarterlyVibration->updateData($PHD, $php_update_phd_api, $strQvsId, $strAction, $strTimeStampFormat, $refno, $strPrepared, $strActivate, $strVerify, $logged_user);
            break;
        case 'preview-checked-by':
            $id = trim($_POST['id']);
            echo $quarterlyVibration->previewDataCheckedBy($PHD, $php_fetch_phd_api, $id);
            break;
    }
}
