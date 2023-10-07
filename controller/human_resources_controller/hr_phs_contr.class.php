<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/human_resources_model/hr_phs_model.class.php';
    $phs = new PersonalHistoryStatement();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');

    switch ($action) {
        case 'load_personal_history_statement_applicant':
            $searchValue = $_POST['search']['value'];
            echo $phs->loadPhsApplicantTableData($php_fetch_human_resources, $searchValue);
            break;

        case 'save_referral':
            $applicantid = trim($_POST['applicantid']);
            $referred_by = trim($_POST['referred_by']);
            $referred_by_relationship = trim($_POST['referred_by_relationship']);
            $phs->saveReferralApplicant($php_update_human_resources, $applicantid, $referred_by, $referred_by_relationship);
            break;

        case 'preview_phs_details':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsApplicantData($php_fetch_human_resources, $applicantid);
            break;

        case 'preview_phs_spouse_children':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsThreeFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_children', 'applicant_child_name', 'applicant_child_birth_date', 'applicant_child_address');
            break;

        case 'preview_phs_siblings':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsThreeFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_siblings', 'applicant_sibling_name', 'applicant_sibling_occupation', 'applicant_sibling_employer');
            break;

        case 'preview_phs_extraculicular':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsThreeFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_extraculicular', 'applicant_extra_title', 'applicant_extra_place', 'applicant_extra_date');
            break;

        case 'preview_phs_employment':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsSixFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_employment_history', 'applicant_employ_gap_explanation', 'applicant_employ_comp_address', 'applicant_employ_position_held', 'applicant_employ_date_from', 'applicant_employ_date_to', 'applicant_employ_reason_leaving');
            break;

        case 'preview_phs_char_ref':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsFourFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_character_references', 'applicant_char_ref_name', 'applicant_char_ref_relationship', 'applicant_char_ref_occupation', 'applicant_char_ref_contact_no');
            break;

        case 'preview_phs_banner_relative':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsTwoFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_employed_banner', 'applicant_employed_banner_name', 'applicant_employed_banner_relationship');
            break;

        case 'preview_phs_organization':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsThreeFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_organizations', 'applicant_organization_name', 'applicant_organization_address', 'applicant_organization_contact_no');
            break;

        case 'preview_phs_financial':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsTwoFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_financial_obligation', 'applicant_financial_bank_name', 'applicant_financial_type_loan');
            break;

        case 'preview_phs_prev_residences':
            $applicantid = trim($_POST['applicantid']);
            echo $phs->previewPhsFourFieldData($php_fetch_human_resources, $applicantid, 'hr_applicant_previous_residences', 'applicant_residences_address', 'applicant_residences_date_from', 'applicant_residences_date_to', 'applicant_residences_own_rent');
            break;

        case 'update_phs_details':
            $applicantid = trim($_POST['applicantid']);
            $bloodtype = trim($_POST['bloodtype']);
            $sss_number = trim($_POST['sss_number']);
            $tin_number = trim($_POST['tin_number']);
            $philhealth_number = trim($_POST['philhealth_number']);
            $pagibig_number = trim($_POST['pagibig_number']);
            $phs->updatePhsApplicantData($php_update_human_resources, $applicantid, $bloodtype, $sss_number, $tin_number, $philhealth_number, $pagibig_number);
            break;

        case 'remove_phs_record':
            $applicantid = trim($_POST['applicantid']);
            break;
    }
}
