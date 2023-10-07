<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/ims_express_model/ims_msd_forms_model.class.php';
    $bannerWeb = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
    $imsExpress = $conn->db_conn_ims_express(); //* IMS Express Database connection
    $imsMsdForm = new ImsMsdForms();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_doc_registration':
            $searchValue = $_POST['search']['value'];
            echo $imsMsdForm->loadDocRegistration($imsExpress, $searchValue);
            break;

        case 'acknowledge_doc_reg':
            $docregisteredid = trim($_POST['docregisteredid']);
            $emp_fullname = trim($_POST['emp_fullname']);
            $imsMsdForm->acknowledgeDocument($imsExpress, $bannerWeb, $docregisteredid, $emp_fullname);
            break;

        case 'done_doc_reg':
            $docregisteredid = trim($_POST['docregisteredid']);
            $date_received = trim($_POST['date_received']);
            $imsMsdForm->doneDocument($imsExpress, $docregisteredid, $date_received);
            break;

        case 'save_effective_date':
            $docregisteredid = trim($_POST['docregisteredid']);
            $doc_effective_date = trim($_POST['doc_effective_date']);
            $imsMsdForm->saveEffectiveDocument($imsExpress, $docregisteredid, $doc_effective_date);
            break;

        case 'document_acknowledge':
            $docregisteredid = trim($_POST['docregisteredid']);
            $imsMsdForm->acknowledgeRegisteredDocument($imsExpress, $docregisteredid);
            break;
    }
}
