<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/perso_monitoring_model/perso_file_deletion_model.php';
    $perso_conn = $conn->db_conn_personalization(); //* email_logs Database connection
    $persoFileDeletion = new PersoFileDeletion();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_file_deleted_list_table':
            $searchValue = $_POST['search']['value'];
            // echo $persoFileDeletion->loadFileDeletedTable($php_fetch_perso_api, $searchValue);
            echo $persoFileDeletion->loadFileDeletedTable($perso_conn, $searchValue);
            break;

        case 'load_holiday_table_data':
            $searchValue = $_POST['search']['value'];
            echo $persoFileDeletion->loadHolidayTable($php_fetch_perso_api, $searchValue);
            break;

        case 'load_for_certification_data':
            $file_company = trim($_POST['file_company']);
            $file_date_from = trim($_POST['file_date_from']);
            $file_date_to = trim($_POST['file_date_to']);
            $file_chk_delivery = trim($_POST['file_chk_delivery']);
            echo $persoFileDeletion->loadForCertificationTable($php_fetch_perso_api, $file_company, $file_date_from, $file_date_to, $file_chk_delivery);
            break;

        case 'load_file_received_count':
            echo $persoFileDeletion->loadFileReceivedCertifiedCount($php_fetch_info_sec_api, 'info_sec_sftp_file_retention', 'sftp_deleted', 'false');
            break;

        case 'load_file_certified_count':
            echo $persoFileDeletion->loadFileReceivedCertifiedCount($php_fetch_perso_api, 'bpi_perso_file_deletion', 'file_certified', 'false');
            break;

        case 'load_company_select_values':
            echo $persoFileDeletion->loadCompanySelectValue($php_fetch_perso_api);
            break;

        case 'load_signatory_select_values':
            echo $persoFileDeletion->loadSignatorySelectValue($php_fetch_bannerweb_api);
            break;

        case 'gen_referrence_no':
            echo $persoFileDeletion->loadReferenceNo($php_fetch_perso_api);
            break;

        case 'save_holiday_date':
            $holiday_date = trim($_POST['holiday_date']);
            echo $persoFileDeletion->saveHolidayDate($php_fetch_perso_api, $php_insert_perso_api, $holiday_date);
            break;

        case 'remove_holiday_date':
            $holidayid = trim($_POST['holidayid']);
            $persoFileDeletion->removeHolidayDate($php_update_perso_api, $holidayid);
            break;

        case 'load_referrence_no':
            $file_company = trim($_POST['file_company']);
            $date_from = trim($_POST['date_from']);
            $date_to = trim($_POST['date_to']);
            $chkReceivedDate = trim($_POST['chkReceivedDate']);
            $chkDeletionDate = trim($_POST['chkDeletionDate']);
            echo $persoFileDeletion->loadDeletionReferenceNo($php_fetch_perso_api, $file_company, $date_from, $date_to, $chkReceivedDate, $chkDeletionDate);
            break;

        case 'update_reference_no':
            $reference_no = trim($_POST['reference_no']);
            $persoFileDeletion->updateReferenceNo($php_update_perso_api, $reference_no);
            break;

        case 'save_file_certification':
            $filedeletionid = trim($_POST['filedeletionid']);
            $prepared_by = trim($_POST['prepared_by']);
            $checked_by = trim($_POST['checked_by']);
            $noted_by = trim($_POST['noted_by']);
            $reference_no = trim($_POST['reference_no']);
            $persoFileDeletion->saveFileCertification($php_fetch_bannerweb_api, $php_update_perso_api, $filedeletionid, $prepared_by, $checked_by, $noted_by, $reference_no);
            break;

        case 'quarterly_record_check':
            $file_company = trim($_POST['file_company']);
            $date_from = trim($_POST['date_from']);
            $date_to = trim($_POST['date_to']);
            echo $persoFileDeletion->quarterlyRecordCheck($php_fetch_perso_api, $file_company, $date_from, $date_to);
            break;

        case 'save_manual_file_entry':
            $manual_file_company = trim($_POST['manual_file_company']);
            $manual_file_received_date = trim($_POST['manual_file_received_date']);
            $manual_file_filename = trim($_POST['manual_file_filename']);
            $manual_file_file_size = trim($_POST['manual_file_file_size']);
            $manual_file_for_deletion_chk = trim($_POST['manual_file_for_deletion_chk']);
            $manual_file_deletion_date = trim($_POST['manual_file_deletion_date']);
            echo $persoFileDeletion->saveManualFileEntry($php_fetch_perso_api, $php_insert_perso_api, $php_fetch_info_sec_api, $php_insert_info_sec_api, $php_update_info_sec_api, $manual_file_company, $manual_file_received_date, $manual_file_filename, $manual_file_file_size, $manual_file_for_deletion_chk, $manual_file_deletion_date);
            break;

        case 'check_record':
            $dateFilter = trim($_POST['dateFilter']);
            echo $persoFileDeletion->checkRecordChecklist($php_fetch_info_sec_api, $dateFilter);
            break;


        case 'sync_data':
            $persoFileDeletion->syncData($php_fetch_bannerweb_api, $php_fetch_perso_api, $php_update_perso_api);
            break;
    }
}
