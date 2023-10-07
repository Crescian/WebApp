<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/human_resources_model/hr_applicant_account_model.class.php';
    $hr_conn = $conn->db_conn_human_resources(); //* human_resources Database connection
    $applicantAcct = new ApplicantAccount();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_applicant_account_table':
            $searchValue = $_POST['search']['value'];
            echo $applicantAcct->loadApplicantAccountData($hr_conn, $searchValue);
            break;

        case 'delete_applicant_account':
            $applicantid = trim($_POST['applicantid']);
            $applicantAcct->deleteApplicantAccount($hr_conn, $applicantid);
            break;
    }
}
