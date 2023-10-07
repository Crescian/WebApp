<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/ims_express_model/ims_document_cotrol_model.class.php';
    $bannerWeb = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
    $imsExpress = $conn->db_conn_ims_express(); //* IMS Express Database connection
    $imsDocControl = new ImsDocumentControl();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_document_menu_tree':
            echo $imsDocControl->loadDocumentTree($imsExpress, 'ims_document_menu', 'docmenuid', 'doc_menu_title', 'doc_menu_parent_id');
            break;

        case 'save_doc_registration':
            $doc_department = trim($_POST['doc_department']);
            $date_requested = trim($_POST['date_requested']);
            $doc_level = trim($_POST['doc_level']);
            $doc_req_type = trim($_POST['doc_req_type']);
            $doc_title = trim($_POST['doc_title']);
            $doc_number = trim($_POST['doc_number']);
            $doc_revision = trim($_POST['doc_revision']);
            $doc_reason_remarks = trim($_POST['doc_reason_remarks']);
            $doc_prepared_by = trim($_POST['doc_prepared_by']);
            $doc_approved_by = trim($_POST['doc_approved_by']);
            $doc_type = trim($_POST['doc_type']);
            $doc_mother_procedure = trim($_POST['doc_mother_procedure']);
            $doc_owner_originator = trim($_POST['doc_owner_originator']);
            $doc_owner_user = trim($_POST['doc_owner_user']);
            $menuid = trim($_POST['menuid']);
            $doc_pdf_file_value = trim($_POST['doc_pdf_file_value']);
            $doc_word_file_value = trim($_POST['doc_word_file_value']);
            $imsDocControl->saveDocRegistration($imsExpress, $bannerWeb, $doc_department, $date_requested, $doc_title, $doc_number, $doc_revision, $doc_req_type, $doc_level, $doc_reason_remarks, $doc_type, $doc_mother_procedure, $doc_owner_originator, $doc_owner_user, $doc_prepared_by, $doc_approved_by, $menuid, $doc_pdf_file_value, $doc_word_file_value);
            break;

        case 'load_select_department':
            echo $imsDocControl->loadDepartment($bannerWeb);
            break;

        case 'load_department_head':
            echo $imsDocControl->loadDepartmentHead($bannerWeb);
            break;

        case 'load_employee':
            $dept_code = trim($_POST['dept_code']);
            echo $imsDocControl->loadEmployee($bannerWeb, $dept_code);
            break;

        case 'load_base64':
            $base64_data = $_POST['data'];
            $base64_array_1 = explode(";", $base64_data);
            $base64_array_2 = explode(",", $base64_array_1[1]);
            $base64_data = base64_decode($base64_array_2[1]);
            $data_base64 = base64_encode($base64_data);
            echo $data_base64;
            break;
    }
}
