<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_paging_system_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $pagingSystem = new PhdPagingSystem();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

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

    function setSelected($selectValue, $rowValue)
    {
        if ($selectValue == $rowValue) {
            $setSelected = 'selected';
        }
        return $setSelected;
    }
    switch ($action) {
        case 'load_paging_monitoring_list_table':
            echo $pagingSystem->fetchData($PHD);
            break;
        case 'load_paging_checklist':
            echo $pagingSystem->loadDataPaging($PHD);
            break;
        case 'save_paging_monitoring_header':
            $prepared_by = trim($_POST['prepared_by']);
            $checked_by = trim($_POST['checked_by']);
            $noted_by = trim($_POST['noted_by']);
            $paging_header = 'Paging Monitoring as of ' . date('m-d-Y');
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Checked Signature ========
            $checkedBySignature = fetchSignature($checked_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($noted_by, $BannerWebLive);
            echo $pagingSystem->saveDataHeader($PHD, $prepared_by, $checked_by, $noted_by, $paging_header, $preparedBySignature, $checkedBySignature, $notedBySignature, $currentDate);
            break;
        case 'save_paging_details':
            $pagingheader_id = trim($_POST['pagingheader_id']);
            $paging_category_name = trim($_POST['paging_category_name']);
            $paging_location_name = trim($_POST['paging_location_name']);
            $paging_status_ok = trim($_POST['paging_status_ok']);
            $paging_status_defective = trim($_POST['paging_status_defective']);
            $paging_remarks = trim($_POST['paging_remarks']);
            $paging_ref_no = trim($_POST['paging_ref_no']);
            if ($paging_status_ok > 0 || $paging_status_defective > 0) {
                $details_date_created = $currentDate;
                $prepared_by = trim($_POST['prepared_by']);
            } else {
                $details_date_created = NULL;
                $prepared_by = NULL;
            }
            echo $pagingSystem->saveDataDetails($PHD, $pagingheader_id, $paging_category_name, $paging_location_name, $paging_status_ok, $paging_status_defective, $paging_remarks, $paging_ref_no, $details_date_created, $prepared_by);
            break;
        case 'preview_employee_header':
            $pagingheaderid = trim($_POST['pagingheaderid']);
            echo $pagingSystem->previewDataHeader($PHD, $pagingheaderid);
            break;
        case 'preview_paging_details':
            $pagingheader_id = trim($_POST['pagingheaderid']);
            echo $pagingSystem->previewDataDetails($PHD, $pagingheader_id);
            break;
        case 'update_paging_monitoring_header':
            $pagingheaderid = trim($_POST['pagingheaderid']);
            $prepared_by = trim($_POST['prepared_by']);
            $checked_by = trim($_POST['checked_by']);
            $noted_by = trim($_POST['noted_by']);
            //* ======== Fetch Employee Perform Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Checked Signature ========
            $checkedBySignature = fetchSignature($checked_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($noted_by, $BannerWebLive);
            echo $pagingSystem->updateDataHeader($PHD, $pagingheaderid, $prepared_by, $checked_by, $noted_by, $preparedBySignature, $checkedBySignature, $notedBySignature, $currentDate);
            break;
        case 'update_paging_details':
            $pagingheaderid = trim($_POST['pagingheaderid']);
            $paging_location_name = trim($_POST['paging_location_name']);
            $paging_category_name = trim($_POST['paging_category_name']);
            $paging_status_ok = trim($_POST['paging_status_ok']);
            $paging_status_defective = trim($_POST['paging_status_defective']);
            if ($paging_status_ok > 0 || $paging_status_defective > 0) {
                $paging_remarks = trim($_POST['paging_remarks']);
                $prepared_by = trim($_POST['prepared_by']);
                $date_created = $currentDate;
            } else {
                $paging_remarks = NULL;
                $prepared_by = NULL;
                $date_created = NULL;
            }
            echo $pagingSystem->updateDataDetails($PHD, $pagingheaderid, $paging_location_name, $paging_category_name, $paging_status_ok, $paging_status_defective, $paging_remarks, $prepared_by, $date_created);
            break;
        case 'delete_paging_monitoring':
            $pagingheaderid = trim($_POST['pagingheaderid']);
            echo $pagingSystem->deleteData($PHD, $pagingheaderid);
            break;
    }
}
