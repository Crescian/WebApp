<?php
include '../configuration/connection.php';
include '../vendor/tcpdf/tcpdf.php';
date_default_timezone_set('Asia/Manila');
$applicantid = $_GET['ad'];

function sqlQuery($sqlstring, $connection)
{
    $data_base64 = base64_encode($sqlstring);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $connection);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    $json_response = curl_exec($curl);
    //* ====== Close Connection ======
    curl_close($curl);
    return json_decode($json_response, true);
}

$sqlstring = "SELECT *,encode(applicant_image, 'escape') AS applicant_image, (applicant_fname || ' ' || applicant_mname || ' ' || applicant_sname) AS fullname FROM hr_applicant_info WHERE applicantid = '{$applicantid}';";
$data_result = sqlQuery($sqlstring, $php_fetch_human_resources);
foreach ($data_result['data'] as $row) {
    $applicantid = $row['applicantid'];
    $applicant_date_entry = $row['applicant_date_entry'];
    $applicant_image = $row['applicant_image'];
    $applicant_fullname = $row['fullname'];
    $applicant_sname = $row['applicant_sname'];
    $applicant_fname = $row['applicant_fname'];
    $applicant_mname = $row['applicant_mname'];
    $applicant_extname = $row['applicant_extname'];
    $applicant_maiden_fname = $row['applicant_maiden_fname'];
    $applicant_maiden_mname = $row['applicant_maiden_mname'];
    $applicant_maiden_sname = $row['applicant_maiden_sname'];
    $applicant_alias = $row['applicant_alias'];
    $applicant_gender = $row['applicant_gender'];
    $applicant_citizenship = $row['applicant_citizenship'];
    $applicant_religion = $row['applicant_religion'];
    $applicant_bloodtype = $row['applicant_bloodtype'];
    $applicant_height = $row['applicant_height'];
    $applicant_weight = $row['applicant_weight'];
    $applicant_eye_color = $row['applicant_eye_color'];
    $applicant_hair_color = $row['applicant_hair_color'];
    $applicant_birth_date = $row['applicant_birth_date'];
    $applicant_age = $row['applicant_age'];
    $applicant_birth_place = $row['applicant_birth_place'];
    $applicant_contact_no = $row['applicant_contact_no'];
    $applicant_email_address = $row['applicant_email_address'];
    $applicant_present_unit = $row['applicant_present_unit'];
    $applicant_present_lot = $row['applicant_present_lot'];
    $applicant_present_street = $row['applicant_present_street'];
    $applicant_present_sub_village = $row['applicant_present_sub_village'];
    $applicant_present_region = $row['applicant_present_region'];
    $applicant_present_province = $row['applicant_present_province'];
    $applicant_present_city = $row['applicant_present_city'];
    $applicant_present_barangay = $row['applicant_present_sub_village'];
    $applicant_present_zipcode = $row['applicant_present_zipcode'];
    $applicant_permanent_unit = $row['applicant_permanent_unit'];
    $applicant_permanent_lot = $row['applicant_permanent_lot'];
    $applicant_permanent_street = $row['applicant_permanent_street'];
    $applicant_permanent_sub_village = $row['applicant_permanent_sub_village'];
    $applicant_permanent_region = $row['applicant_permanent_region'];
    $applicant_permanent_province = $row['applicant_permanent_province'];
    $applicant_permanent_city = $row['applicant_permanent_city'];
    $applicant_permanent_barangay = $row['applicant_permanent_sub_village'];
    $applicant_permanent_zipcode = $row['applicant_permanent_zipcode'];
    $applicant_sss_no = $row['applicant_sss_no'];
    $applicant_tin_no = $row['applicant_tin_no'];
    $applicant_philhealth_no = $row['applicant_philhealth_no'];
    $applicant_pagibig_no = $row['applicant_pagibig_no'];
    $applicant_civil_status = $row['applicant_civil_status'];
    $applicant_spouse_fname = $row['applicant_spouse_fname'];
    $applicant_spouse_mname = $row['applicant_spouse_mname'];
    $applicant_spouse_sname = $row['applicant_spouse_sname'];
    $applicant_spouse_extname = $row['applicant_spouse_extname'];
    $applicant_spouse_occupation = $row['applicant_spouse_occupation'];
    $applicant_spouse_employer = $row['applicant_spouse_employer'];
    $applicant_father_sname = $row['applicant_father_sname'];
    $applicant_father_fname = $row['applicant_father_fname'];
    $applicant_father_extname = $row['applicant_father_extname'];
    $applicant_father_mname = $row['applicant_father_mname'];
    $applicant_father_occupation = $row['applicant_father_occupation'];
    $applicant_father_employer = $row['applicant_father_employer'];
    $applicant_mother_sname = $row['applicant_mother_sname'];
    $applicant_mother_fname = $row['applicant_mother_fname'];
    $applicant_mother_mname = $row['applicant_mother_mname'];
    $applicant_mother_occupation = $row['applicant_mother_occupation'];
    $applicant_mother_employer = $row['applicant_mother_employer'];
    $applicant_tertiary_school = $row['applicant_tertiary_school'];
    $applicant_tertiary_course = $row['applicant_tertiary_course'];
    $applicant_tertiary_address = $row['applicant_tertiary_address'];
    $applicant_tertiary_date_from = $row['applicant_tertiary_date_from'];
    $applicant_tertiary_date_to = $row['applicant_tertiary_date_to'];
    $applicant_sr_high_school = $row['applicant_sr_high_school'];
    $applicant_sr_high_address = $row['applicant_sr_high_address'];
    $applicant_sr_high_date_from = $row['applicant_sr_high_date_from'];
    $applicant_sr_high_date_to = $row['applicant_sr_high_date_to'];
    $applicant_jr_high_school = $row['applicant_jr_high_school'];
    $applicant_jr_high_address = $row['applicant_jr_high_address'];
    $applicant_jr_high_date_from = $row['applicant_jr_high_date_from'];
    $applicant_jr_high_date_to = $row['applicant_jr_high_date_to'];
    $applicant_elem_school = $row['applicant_elem_school'];
    $applicant_elem_address = $row['applicant_elem_address'];
    $applicant_elem_date_from = $row['applicant_elem_date_from'];
    $applicant_elem_date_to = $row['applicant_elem_date_to'];
    $applicant_special_skills = $row['applicant_special_skills'];
    $applicant_awards_received = $row['applicant_awards_received'];
    $applicant_emergency_name = $row['applicant_emergency_name'];
    $applicant_emergency_relationship = $row['applicant_emergency_relationship'];
    $applicant_emergency_address = $row['applicant_emergency_address'];
    $applicant_emergency_cell_no = $row['applicant_emergency_cell_no'];
    $applicant_relative_telecom = $row['applicant_relative_telecom'];
    $applicant_relative_name = $row['applicant_relative_name'];
    $applicant_relative_relationship = $row['applicant_relative_relationship'];
    $applicant_relative_telecompany = $row['applicant_relative_telecompany'];
    $applicant_relative_sale_cell_cards = $row['applicant_relative_sale_cell_cards'];
    $applicant_relative_sale_name = $row['applicant_relative_sale_name'];
    $applicant_relative_sale_relationship = $row['applicant_relative_sale_relationship'];
    $applicant_relative_sale_card_type = $row['applicant_relative_sale_card_type'];
    $applicant_relative_banner = $row['applicant_relative_banner'];
    $applicant_employ_banner = $row['applicant_employ_banner'];
    $applicant_employ_banner_date_from = $row['applicant_employ_banner_date_from'] == '' ? '-' : $row['applicant_employ_banner_date_from'];
    $applicant_employ_banner_date_to = $row['applicant_employ_banner_date_to'] == '' ? '-' : $row['applicant_employ_banner_date_to'];
    $applicant_employ_banner_position = $row['applicant_employ_banner_position'];
    $applicant_employ_banner_seperation = $row['applicant_employ_banner_seperation'];
    $applicant_convicted_crime = $row['applicant_convicted_crime'];
    $applicant_convicted_details = $row['applicant_convicted_details'];
    $applicant_active_organizations = $row['applicant_active_organizations'];
    $applicant_existing_financial = $row['applicant_existing_financial'];

    $applicant_referred_by = $row['applicant_referred_by'];
    $applicant_referred_by_relationship = $row['applicant_referred_by_relationship'];
}

//* Fetch Spouse Children
$fetch_children = "SELECT * FROM hr_applicant_children WHERE applicant_id = '{$applicantid}'";
$data_result_child = sqlQuery($fetch_children, $php_fetch_human_resources);
//* Fetch Applicant Siblings
$fetch_siblings = "SELECT * FROM hr_applicant_siblings WHERE applicant_id = '{$applicantid}'";
$data_result_siblings = sqlQuery($fetch_siblings, $php_fetch_human_resources);
//* Fetch Applicant Seminars
$fetch_seminar = "SELECT * FROM hr_applicant_extraculicular WHERE applicant_id = '{$applicantid}'";
$data_result_seminar = sqlQuery($fetch_seminar, $php_fetch_human_resources);
//* Fetch Applicant Employment
$fetch_employment = "SELECT * FROM hr_applicant_employment_history WHERE applicant_id = '{$applicantid}'";
$data_result_employment = sqlQuery($fetch_employment, $php_fetch_human_resources);
//* Fetch Applicant Character Reference
$fetch_char_ref = "SELECT * FROM hr_applicant_character_references WHERE applicant_id = '{$applicantid}'";
$data_result_char_ref = sqlQuery($fetch_char_ref, $php_fetch_human_resources);
//* Fetch Applicant Banner Relative
$fetch_banner_relative = "SELECT * FROM hr_applicant_employed_banner WHERE applicant_id = '{$applicantid}'";
$data_result_relative_banner = sqlQuery($fetch_banner_relative, $php_fetch_human_resources);
//* Fetch Applicant Organization
$fetch_organization = "SELECT * FROM hr_applicant_organizations WHERE applicant_id = '{$applicantid}'";
$data_result_organization = sqlQuery($fetch_organization, $php_fetch_human_resources);
//* Fetch Applicant Financial
$fetch_financial = "SELECT * FROM hr_applicant_financial_obligation WHERE applicant_id = '{$applicantid}'";
$data_result_financial = sqlQuery($fetch_financial, $php_fetch_human_resources);
//* Fetch Applicant Previous Residences
$fetch_prev_residences = "SELECT * FROM hr_applicant_previous_residences WHERE applicant_id = '{$applicantid}'";
$data_result_prev_residences = sqlQuery($fetch_prev_residences, $php_fetch_human_resources);


//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page header
    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 86, 12, 39, 10.5, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 159, 12, 40.5, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Human Resources Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'PERSONAL HISTORY STATEMENT', 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Personal History Statement', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 22 mm from bottom - 12.7mm - .5inch
        $this->SetY(-22);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'HRD/XX-022-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);
        //* Page Number
        $numPages = $this->getNumPages();
        if ($numPages < $this->getNumPages()) {
            $this->Cell(207, '', '', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
        } else {
            if ($this->PageNo() >= 1 && $this->PageNo() <= 9) {
                $this->Cell(207, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
            }
            if ($this->PageNo() >= 10) {
                $this->Cell(202, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, '', ''); //* Both number is Double digit
            }
        }
    }

    public function childrenHeader($header_child, $w)
    {
        $num_headers = count($header_child);
        $this->SetFont('helvetica', '', 12);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 0, $header_child[$i], 0, 0, 'C', 0);
        }
        $this->Ln(7);
    }

    public function ChildrenTable($header_child, $data_result_child)
    {
        $w = array(55, 5, 30, 5, 0); //* set column width
        $this->childrenHeader($header_child, $w);
        $this->SetFont('helvetica', '', 10);
        $num_pages = $this->getNumPages(); //* Get current number of pages.
        foreach ($data_result_child['data'] as $row) { //* Data
            $this->startTransaction();
            $this->Cell($w[0], 0, $row['applicant_child_name'], 'B', 0, 'C', 0);
            $this->Cell($w[1], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[2], 0, date_format(date_create($row['applicant_child_birth_date']), 'M d,Y'), 'B', 0, 'C', 0);
            $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[4], 0, $row['applicant_child_address'], 'B', 0, 'C', 0);
            $this->Ln(6);
            //* If old number of pages is less than the new number of pages,we hit an automatic page break, and need to rollback.
            if ($num_pages < $this->getNumPages()) {
                $this->rollbackTransaction(true); //* Undo adding the row.
                //* Add page
                $this->SetMargins(12.5, 12, 12.5);
                $this->AddPage();
                $this->childrenHeader($header_child, $w); //* Draw the header.
                //* Re-do the row.
                $this->SetFont('helvetica', '', 10);
                $this->Cell($w[0], 0, $row['applicant_child_name'], 'B', 0, 'C', 0);
                $this->Cell($w[1], 0, '', 0, 0, 'C', 0);
                $this->Cell($w[2], 0, date_format(date_create($row['applicant_child_birth_date']), 'M d,Y'), 'B', 0, 'C', 0);
                $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
                $this->Cell($w[4], 0, $row['applicant_child_address'], 'B', 0, 'C', 0);
                $this->Ln(6);
            } else {
                $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
            }
        }
    }

    public function TwoFieldTable($headers, $data_result, $inCategory, $inField1, $inField2)
    {
        switch ($inCategory) {
            case 'banner_relative':
                $w = array(12, 86.5, 5, 0); //* set column width
                break;
            default:
                $w = array(12, 90, 5, 0); //* set column width
                break;
        }

        $num_headers = count($headers);
        $this->SetFont('helvetica', '', 12);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 0, $headers[$i], 0, 0, 'C', 0);
        }
        $this->Ln(7);
        $this->SetFont('helvetica', '', 10);
        foreach ($data_result['data'] as $row) { //* Data
            $this->startTransaction();
            $this->Cell($w[0], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[1], 0, $row[$inField1], 'B', 0, 'C', 0);
            $this->Cell($w[2], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[3], 0, $row[$inField2], 'B', 0, 'C', 0);
            $this->Ln(6);
        }
    }

    public function ThreeFieldTable($headers, $data_result, $inCategory, $inField1, $inField2, $inField3)
    {
        switch ($inCategory) {
            case 'seminar':
                $w = array(85, 5, 70, 5, 0); //* set column width
                break;
            case 'organization':
                $w = array(12, 65, 5, 65, 5, 0); //* set column width
                break;
            default:
                $w = array(60, 5, 60, 5, 0); //* set column width
                break;
        }

        $num_headers = count($headers);
        $this->SetFont('helvetica', '', 12);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 0, $headers[$i], 0, 0, 'C', 0);
        }
        $this->Ln(7);
        $this->SetFont('helvetica', '', 10);
        $num_pages = $this->getNumPages(); //* Get current number of pages.
        foreach ($data_result['data'] as $row) { //* Data
            $this->startTransaction();
            switch ($inCategory) {
                case 'organization':
                    $this->Cell($w[0], 0, '', 0, 0, 'C', 0);
                    $this->Cell($w[1], 0, $row[$inField1], 'B', 0, 'C', 0);
                    $this->Cell($w[2], 0, '', 0, 0, 'C', 0);
                    $this->Cell($w[3], 0, $row[$inField2], 'B', 0, 'C', 0);
                    $this->Cell($w[4], 0, '', 0, 0, 'C', 0);
                    $this->Cell($w[5], 0, $row[$inField3], 'B', 0, 'C', 0);
                    $this->Ln(6);
                    //* If old number of pages is less than the new number of pages,we hit an automatic page break, and need to rollback.
                    if ($num_pages < $this->getNumPages()) {
                        $this->rollbackTransaction(true); //* Undo adding the row.
                        //* Add page
                        $this->SetMargins(12.5, 12, 12.5);
                        $this->AddPage();
                        $num_headers = count($headers);
                        $this->SetFont('helvetica', '', 12);
                        for ($i = 0; $i < $num_headers; ++$i) {
                            $this->Cell($w[$i], 0, $headers[$i], 0, 0, 'C', 0);
                        }
                        $this->Ln(7);
                        //* Re-do the row.
                        $this->SetFont('helvetica', '', 10);
                        $this->Cell($w[0], 0, '', 0, 0, 'C', 0);
                        $this->Cell($w[1], 0, $row[$inField1], 'B', 0, 'C', 0);
                        $this->Cell($w[2], 0, '', 0, 0, 'C', 0);
                        $this->Cell($w[3], 0, $row[$inField2], 'B', 0, 'C', 0);
                        $this->Cell($w[4], 0, '', 0, 0, 'C', 0);
                        $this->Cell($w[5], 0, $row[$inField3], 'B', 0, 'C', 0);
                        $this->Ln(6);
                    } else {
                        $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
                    }
                    break;
                default:
                    $this->Cell($w[0], 0, $row[$inField1], 'B', 0, 'C', 0);
                    $this->Cell($w[1], 0, '', 0, 0, 'C', 0);
                    $this->Cell($w[2], 0, $row[$inField2], 'B', 0, 'C', 0);
                    $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
                    switch ($inCategory) {
                        case 'seminar':
                            $this->Cell($w[4], 0, date_format(date_create($row[$inField3]), 'M d,Y'), 'B', 0, 'C', 0);
                            break;
                        default:
                            $this->Cell($w[4], 0, $row[$inField3], 'B', 0, 'C', 0);
                            break;
                    }
                    $this->Ln(6);
                    break;
            }
        }
    }

    public function EmploymentTable($data_result_employment)
    {
        //* MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign = 'T', $fitcell = false)
        foreach ($data_result_employment['data'] as $row) { //* Data
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Explanation on employment gap/s:', 'LTR', 1, 'L');
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(0, 10, $row['applicant_employ_gap_explanation'] . "\n", 'LBR', 'J', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
            $this->Ln(13);
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(60, 10, $row['applicant_employ_comp_address'] . "\n", 'B', 'C', 0, 0, '', '', true, 0, false, true, 10, 'B', false);
            $this->Cell(5, 0, '', 0, 0, 'C');
            $this->MultiCell(45, 10, $row['applicant_employ_position_held'] . "\n", 'B', 'C', 0, 0, '', '', true, 0, false, true, 10, 'B', false);
            $this->Cell(5, 0, '', 0, 0, 'C');
            $this->Cell(20, 10, date_format(date_create($row['applicant_employ_date_from']), 'M Y'), 'B', 0, 'C', 0, '', 0, false, 'T', 'B');
            $this->Cell(3, 0, '', 0, 0, 'C');
            $this->Cell(20, 10, date_format(date_create($row['applicant_employ_date_to']), 'M Y'), 'B', 0, 'C', 0, '', 0, false, 'T', 'B');
            $this->Cell(5, 0, '', 0, 0, 'C');
            $this->Cell(0, 10, $row['applicant_employ_reason_leaving'], 'B', 0, 'C', 0, '', 0, false, 'T', 'B');
            $this->Ln(10);
            $this->SetFont('helvetica', '', 8);
            $this->Cell(60, 0, 'Company/Address', 0, 0, 'C');
            $this->Cell(5, 0, '', 0, 0, 'C');
            $this->Cell(45, 0, 'Position Held', 0, 0, 'C');
            $this->Cell(5, 0, '', 0, 0, 'C');
            $this->Cell(20, 0, 'From(Mo./Yr.)', 0, 0, 'C');
            $this->Cell(3, 0, '', 0, 0, 'C');
            $this->Cell(20, 0, 'To(Mo./Yr.)', 0, 0, 'C');
            $this->Cell(5, 0, '', 0, 0, 'C');
            $this->Cell(0, 0, 'Reason for Leaving', 0, 0, 'C');
            $this->Ln(10);
        }
    }

    public function CharacterRefTable($header_char_ref, $data_result_char_ref)
    {
        $w = array(50, 5, 50, 5, 45, 5, 0); //* set column width
        $num_headers = count($header_char_ref);
        $this->SetFont('helvetica', '', 12);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 0, $header_char_ref[$i], 0, 0, 'C', 0);
        }
        $this->Ln(7);
        $this->SetFont('helvetica', '', 10);
        foreach ($data_result_char_ref['data'] as $row) { //* Data
            $this->startTransaction();
            $this->Cell($w[0], 0, $row['applicant_char_ref_name'], 'B', 0, 'C', 0);
            $this->Cell($w[1], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[2], 0, $row['applicant_char_ref_relationship'], 'B', 0, 'C', 0);
            $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[4], 0, $row['applicant_char_ref_occupation'], 'B', 0, 'C', 0);
            $this->Cell($w[5], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[6], 0, $row['applicant_char_ref_contact_no'], 'B', 0, 'C', 0);
            $this->Ln(7);
        }
    }


    public function PrevResidencesHeader($header_residences, $hw)
    {
        $this->SetFont('helvetica', '', 12);
        $num_headers = count($header_residences);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($hw[$i], 0, $header_residences[$i], 0, 0, 'C');
        }
        $this->Ln(7);
    }

    public function PrevResidencesTable($header_residences, $data_result_prev_residences)
    {
        $hw = array(105, 5, 45, 5, 0); //* set header column width
        $w = array(105, 5, 20, 5, 20, 5, 0); //* set data column width
        $num_pages = $this->getNumPages(); //* Get current number of pages.
        $this->PrevResidencesHeader($header_residences, $hw);
        foreach ($data_result_prev_residences['data'] as $row) { //* Data
            $this->startTransaction();
            $this->SetFont('helvetica', '', 11);
            $this->Cell($w[0], 0, $row['applicant_residences_address'], 'B', 0, 'C');
            $this->Cell($w[1], 0, '', 0, 0, 'L', 0);
            $this->Cell($w[2], 0, date_format(date_create($row['applicant_residences_date_from']), 'M Y'), 'B', 0, 'R');
            $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[4], 0, date_format(date_create($row['applicant_residences_date_to']), 'M Y'), 'B', 0, 'L');
            $this->Cell($w[5], 0, '', 0, 0, 'R', 0);
            $this->Cell($w[6], 0, $row['applicant_residences_own_rent'], 'B', 0, 'C');
            $this->Ln(6);
            $this->SetFont('helvetica', '', 8);
            $this->Cell($w[0], 0, '', 0, 0, 'C');
            $this->Cell($w[1], 0, '', 0, 0, 'L', 0);
            $this->Cell($w[2], 0, 'From (Mo./Yr.)', 0, 0, 'R');
            $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
            $this->Cell($w[4], 0, 'To (Mo./Yr.)', 0, 0, 'L');
            $this->Cell($w[5], 0, '', 0, 0, 'R', 0);
            $this->Cell($w[6], 0, '', 0, 0, 'C');
            $this->Ln(7);
            //* If old number of pages is less than the new number of pages,we hit an automatic page break, and need to rollback.
            if ($num_pages < $this->getNumPages()) {
                $this->rollbackTransaction(true); //* Undo adding the row.
                //* Add page
                $this->SetMargins(12.5, 13, 12.5);
                $this->AddPage();
                $this->PrevResidencesHeader($header_residences, $hw); //* Draw the header.
                //* Re-do the row.
                $this->SetFont('helvetica', '', 11);
                $this->Cell($w[0], 0, $row['applicant_residences_address'], 'B', 0, 'C');
                $this->Cell($w[1], 0, '', 0, 0, 'L', 0);
                $this->Cell($w[2], 0, date_format(date_create($row['applicant_residences_date_from']), 'M Y'), 'B', 0, 'R');
                $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
                $this->Cell($w[4], 0, date_format(date_create($row['applicant_residences_date_to']), 'M Y'), 'B', 0, 'L');
                $this->Cell($w[5], 0, '', 0, 0, 'R', 0);
                $this->Cell($w[6], 0, $row['applicant_residences_own_rent'], 'B', 0, 'C');
                $this->Ln(6);
                $this->SetFont('helvetica', '', 8);
                $this->Cell($w[0], 0, '', 0, 0, 'C');
                $this->Cell($w[1], 0, '', 0, 0, 'L', 0);
                $this->Cell($w[2], 0, 'From (Mo./Yr.)', 0, 0, 'R');
                $this->Cell($w[3], 0, '', 0, 0, 'C', 0);
                $this->Cell($w[4], 0, 'To (Mo./Yr.)', 0, 0, 'L');
                $this->Cell($w[5], 0, '', 0, 0, 'R', 0);
                $this->Cell($w[6], 0, '', 0, 0, 'C');
                $this->Ln(7);
            } else {
                $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
            }
        }
    }
}

//* create new PDF document
//* ====== 191 total width with margin 12.5
$pdf = new MYPDF("P", PDF_UNIT, "LONG", true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 25); //* set page break
$pdf->setPrintHeader(true); //* include header
$pdf->setPrintFooter(true); //* include footer
$pdf->AddPage(); //* Add page
//* -------------------------------- Info --------------------------------
//* RoundedRect(x, y, w, h, radius of circle to be rounded, round_corner = '1111', style = '', border_style = nil, fill_color = nil)
$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
$pdf->RoundedRect(83, 42.5, 49.5, 49.5, 0, '0000', '', 0, 0);
$pdf->Ln(87); //* Next Line
//* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 0, 'Name ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(53, 0, $applicant_sname, 'B', 0, 'C');
$pdf->Cell(5, 0, ',', 'B', 0, 'C');
$pdf->Cell(53, 0, $applicant_fname, 'B', 0, 'C');
$pdf->Cell(15, 0, $applicant_extname, 'B', 0, 'C');
$pdf->Cell(45, 0, $applicant_mname, 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(20, 0, '', 0, 0, 'L');
$pdf->Cell(53, 0, '(Last Name)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(53, 0, '(First Name)', 0, 0, 'C');
$pdf->Cell(15, 0, 'Name Extension(JR/SR/III)', 0, 0, 'C');
$pdf->Cell(45, 0, '(Middle Name)', 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(0, 0, '(If Married Female, please write Full Maiden Name)', 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Maiden Name ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(48, 0, $applicant_maiden_sname, 'B', 0, 'C');
$pdf->Cell(5, 0, ',', 'B', 0, 'C');
$pdf->Cell(48, 0, $applicant_maiden_fname, 'B', 0, 'C');
$pdf->Cell(15, 0, '', 'B', 0, 'C');
$pdf->Cell(45, 0, $applicant_maiden_mname, 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, '(Last Name)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(48, 0, '(First Name)', 0, 0, 'C');
$pdf->Cell(15, 0, 'Name Extension(JR/SR/III)', 0, 0, 'C');
$pdf->Cell(45, 0, '(Middle Name)', 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(31, 0, 'Nickname/Alias ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(35, 0, $applicant_alias, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, 'Sex', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(15, 0, $applicant_gender, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(22, 0, 'Citizenship', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(30, 0, $applicant_citizenship, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(18, 0, 'Religion', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(30, 0, $applicant_religion, 'B', 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(23, 0, 'Blood Type', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(13, 0, $applicant_bloodtype, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(15, 0, 'Height', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(16, 0, $applicant_height, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(15, 0, 'Weight', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(13, 0, $applicant_weight, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(25, 0, 'Color of Eye', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(23, 0, $applicant_eye_color, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(25, 0, 'Color of Hair', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(23, 0, $applicant_hair_color, 'B', 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(26, 0, 'Date of Birth', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(25, 0, date_format(date_create($applicant_birth_date), 'M d,Y'), 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(12, 0, 'Age', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(13, 0, $applicant_age, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(27, 0, 'Place of Birth', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_birth_place, 'B', 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(37, 0, 'Contact Number/s', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(45, 0, $applicant_contact_no, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Email Address', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_email_address, 'B', 0, 'C');
$pdf->Ln(7);
//* ====== Present Address Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 0, 'Present Address', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 0, $applicant_present_unit, 'B', 0, 'C');
$pdf->Cell(40, 0, $applicant_present_lot, 'B', 0, 'C');
$pdf->Cell(0, 0, $applicant_present_street, 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(40, 0, 'Unit/Room No./Floor', 0, 0, 'C');
$pdf->Cell(40, 0, 'Lot/Block/House/Bldg.No', 0, 0, 'C');
$pdf->Cell(0, 0, 'Street', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, $applicant_present_sub_village, 'B', 0, 'C');
$pdf->Cell(56, 0, $applicant_present_region, 'B', 0, 'C');
$pdf->Cell(0, 0, $applicant_present_province, 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, 'Subdivision/Village', 0, 0, 'C');
$pdf->Cell(56, 0, 'Region', 0, 0, 'C');
$pdf->Cell(0, 0, 'Province', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(68, 0, $applicant_present_city, 'B', 0, 'C');
$pdf->Cell(70, 0, $applicant_present_barangay, 'B', 0, 'C');
$pdf->Cell(13, 0, $applicant_present_zipcode, 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(68, 0, 'City/Municipality', 0, 0, 'C');
$pdf->Cell(70, 0, 'Barangay', 0, 0, 'C');
$pdf->Cell(13, 0, 'Zip Code', 0, 0, 'C');
$pdf->Ln(7);
//* ====== Permanent Address Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 0, 'Permanent Address', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 0, $applicant_permanent_unit, 'B', 0, 'C');
$pdf->Cell(40, 0, $applicant_permanent_lot, 'B', 0, 'C');
$pdf->Cell(0, 0, $applicant_permanent_street, 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(40, 0, 'Unit/Room No./Floor', 0, 0, 'C');
$pdf->Cell(40, 0, 'Lot/Block/House/Bldg.No', 0, 0, 'C');
$pdf->Cell(0, 0, 'Street', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, $applicant_permanent_sub_village, 'B', 0, 'C');
$pdf->Cell(56, 0, $applicant_permanent_region, 'B', 0, 'C');
$pdf->Cell(0, 0, $applicant_permanent_province, 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(48, 0, 'Subdivision/Village', 0, 0, 'C');
$pdf->Cell(56, 0, 'Region', 0, 0, 'C');
$pdf->Cell(0, 0, 'Province', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(68, 0, $applicant_permanent_city, 'B', 0, 'C');
$pdf->Cell(70, 0, $applicant_permanent_barangay, 'B', 0, 'C');
$pdf->Cell(13, 0, $applicant_permanent_zipcode, 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(68, 0, 'City/Municipality', 0, 0, 'C');
$pdf->Cell(70, 0, 'Barangay', 0, 0, 'C');
$pdf->Cell(13, 0, 'Zip Code', 0, 0, 'C');
$pdf->Ln(7);
//* ====== Government Identification Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 0, 'SSS Number', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(45, 0, $applicant_sss_no, 'B', 0, 'C');
$pdf->Cell(10.5, 0, '', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 0, 'Tax Identification Number', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_tin_no, 'B', 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 0, 'Philhealth Number', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(45, 0, $applicant_philhealth_no, 'B', 0, 'C');
$pdf->Cell(10.5, 0, '', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 0, 'HDMF Pag-IBIG Number', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_pagibig_no, 'B', 0, 'C');
$pdf->Ln(10);
//* ====== Marital Status Section ======
$pdf->SetFont('helvetica', '', 12);
if ($applicant_civil_status == 'Single') {
    $pdf->Cell(39, 0, 'Civil Status', 0, 0, 'L');
    $pdf->SetFont('zapfdingbats', '', 17);
    $pdf->Cell(1, 0, '   3', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
} else {
    $pdf->Cell(40, 0, 'Civil Status', 0, 0, 'L');
}
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(15, 0, '', 'B', 0, 'C');
$pdf->Cell(40.5, 0, 'Single', 0, 0, 'L');
if ($applicant_civil_status == 'Widowed') {
    $pdf->SetFont('zapfdingbats', '', 17);
    $pdf->Cell(1, 0, '   3', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
}
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(15, 0, '', 'B', 0, 'C');
$pdf->Cell(50, 0, 'Widowed', 0, 0, 'L');
$pdf->Ln(7);
if ($applicant_civil_status == 'Married') {
    $pdf->Cell(39, 0, '', 0, 0, 'L');
    $pdf->SetFont('zapfdingbats', '', 17);
    $pdf->Cell(1, 0, '   3', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
} else {
    $pdf->Cell(40, 0, '', 0, 0, 'L');
}
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(15, 0, '', 'B', 0, 'C');
$pdf->Cell(40.5, 0, 'Married', 0, 0, 'L');
if ($applicant_civil_status == 'Divorced') {
    $pdf->SetFont('zapfdingbats', '', 17);
    $pdf->Cell(1, 0, '   3', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
}
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(15, 0, '', 'B', 0, 'C');
$pdf->Cell(50, 0, 'Divorced', 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(40, 0, 'Name of Spouse', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
if ($applicant_spouse_fname == 'N/A') {
    $pdf->Cell(0, 0, 'N/A', 'B', 0, 'L');
} else {
    $pdf->Cell(0, 0, $applicant_spouse_fname . ' ' . $applicant_spouse_mname . ' ' . $applicant_spouse_sname . ' ' . $applicant_spouse_extname, 'B', 0, 'L');
}
$pdf->Ln(7);
//* ====== Spouse Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(60, 0, 'Current Occupation of Spouse', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(50, 0, $applicant_spouse_occupation, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 0, 'Employer', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_spouse_employer, 'B', 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 0, 'Children/s', 0, 0, 'L');
$pdf->Ln(4);
//* ====== Spouse Children Section ======
$header_child = array('Names', '', 'Date of Birth', '', 'Address'); //* column titles
$pdf->ChildrenTable($header_child, $data_result_child); //* table
$pdf->Ln();
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 0, 'II. FAMILY HISTORY AND INFORMATION', 0, 0, 'L');
$pdf->Ln(8);
//* ====== Fullname Father Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 0, 'Full Name of Father', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(40, 0, $applicant_father_sname, 'B', 0, 'C');
$pdf->Cell(5, 0, ',', 'B', 0, 'C');
$pdf->Cell(45, 0, $applicant_father_fname, 'B', 0, 'C');
$pdf->Cell(15, 0, $applicant_father_extname, 'B', 0, 'C');
$pdf->Cell(0, 0, $applicant_father_mname, 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(40, 0, '(Last Name)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(45, 0, '(First Name)', 0, 0, 'C');
$pdf->Cell(15, 0, 'Name Extension(JR/SR/III)', 0, 0, 'C');
$pdf->Cell(0, 0, '(Middle Name)', 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(25, 0, 'Occupation', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(70, 0, $applicant_father_occupation, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 0, 'Employer', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_father_employer, 'B', 0, 'C');
$pdf->Ln(8);
//* ====== Fullname Mother Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 0, 'Full Name of Mother', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(50, 0, $applicant_mother_sname, 'B', 0, 'C');
$pdf->Cell(5, 0, ',', 'B', 0, 'C');
$pdf->Cell(55, 0, $applicant_mother_fname, 'B', 0, 'C');
$pdf->Cell(0, 0, $applicant_mother_mname, 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 0, '', 0, 0, 'L');
$pdf->Cell(50, 0, '(Last Name)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(55, 0, '(First Name)', 0, 0, 'C');
$pdf->Cell(0, 0, '(Middle Name)', 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(25, 0, 'Occupation', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(70, 0, $applicant_mother_occupation, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 0, 'Employer', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_mother_employer, 'B', 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 0, 'Brothers and Sisters', 0, 0, 'L');
$pdf->Ln(5);
//* ====== Sibling Section ======
$header_siblings = array('Names', '', 'Occupation', '', 'Employer'); //* column titles
$pdf->ThreeFieldTable($header_siblings, $data_result_siblings, 'sibling', 'applicant_sibling_name', 'applicant_sibling_occupation', 'applicant_sibling_employer'); //* table
$pdf->Ln(8);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 0, 'III. EDUCATIONAL ATTAINMENT', 0, 0, 'L');
$pdf->Ln(8);
//* ====== Tertiary Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Tertiary', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(53, 0, $applicant_tertiary_school, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, $applicant_tertiary_address, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_tertiary_date_from == '' ? '-' : date_format(date_create($applicant_tertiary_date_from), 'M Y'), 'B', 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_tertiary_date_to == '' ? '-' : date_format(date_create($applicant_tertiary_date_to), 'M Y'), 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(53, 0, '(Name of School)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, '(School Address)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'From(Mo./Yr.)', 0, 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'To(Mo./Yr.)', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Course/s', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 0, $applicant_tertiary_course, 'B', 0, 'L');
$pdf->Ln(9);
//* ====== Sr. Highschool Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Sr. Highschool', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(53, 0, $applicant_sr_high_school, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, $applicant_sr_high_address, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_sr_high_date_from == '' ? '-' : date_format(date_create($applicant_sr_high_date_from), 'M Y'), 'B', 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_sr_high_date_to == '' ? '-' : date_format(date_create($applicant_sr_high_date_to), 'M Y'), 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(53, 0, '(Name of School)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, '(School Address)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'From(Mo./Yr.)', 0, 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'To(Mo./Yr.)', 0, 0, 'C');
$pdf->Ln(7);
//* ====== Jr. Highschool Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Jr. Highschool', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(53, 0, $applicant_jr_high_school, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, $applicant_jr_high_address, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_jr_high_date_from == '' ? '-' : date_format(date_create($applicant_jr_high_date_from), 'M Y'), 'B', 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_jr_high_date_to == '' ? '-' : date_format(date_create($applicant_jr_high_date_to), 'M Y'), 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(53, 0, '(Name of School)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, '(School Address)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'From(Mo./Yr.)', 0, 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'To(Mo./Yr.)', 0, 0, 'C');
$pdf->Ln(7);
//* ====== Elementary Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Elementary', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(53, 0, $applicant_elem_school, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, $applicant_elem_address, 'B', 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_elem_date_from == '' ? '-' : date_format(date_create($applicant_elem_date_from), 'M Y'), 'B', 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, $applicant_elem_date_to == '' ? '-' : date_format(date_create($applicant_elem_date_to), 'M Y'), 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(53, 0, '(Name of School)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(54, 0, '(School Address)', 0, 0, 'C');
$pdf->Cell(5, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'From(Mo./Yr.)', 0, 0, 'C');
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'To(Mo./Yr.)', 0, 0, 'C');
$pdf->Ln(7);
//* ====== SSeminars/Training/Workshops Attended Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 0, 'Seminars/Training/Workshops Attended', 0, 0, 'L');
$pdf->Ln(5);
//* ====== Seminar Section ======
$header_seminar = array('Title', '', 'Place', '', 'Date'); //* column titles
$pdf->ThreeFieldTable($header_seminar, $data_result_seminar, 'seminar', 'applicant_extra_title', 'applicant_extra_place', 'applicant_extra_date'); //* table
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(29, 0, 'Special Skill/s', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_special_skills, 'B', 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(66, 0, 'Awards/Honors/License Received', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_awards_received, 'B', 0, 'L');
$pdf->Ln(10);
//* ====== Employment Section ======
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(58, 0, 'IV. EMPLOYMENT HISTORY', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 0, '(Please list down your employment history within the last seven years and kindly', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(5);
$pdf->Cell(0, 0, 'explain any employment gaps from one company to other)', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(8);
//* ====== Employment Table Section ======
$pdf->EmploymentTable($data_result_employment); //* table
$pdf->Ln(8);
//* ====== Character References Section ======
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 0, 'V. CHARACTER REFERENCES', 0, 0, 'L');
$pdf->Ln(7);
$header_char_ref = array('Name', '', 'Relationship', '', 'Occupation', '', 'Contact No.'); //* column titles
$pdf->CharacterRefTable($header_char_ref, $data_result_char_ref); //* table
$pdf->Ln(7);
//* ====== Emergency Person Section ======
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 0, 'VI. CONTACT PERSON IN CASE OF EMERGENCY', 0, 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(27, 0, 'Name', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_emergency_name, 'B', 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(27, 0, 'Relationship', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_emergency_relationship, 'B', 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(27, 0, 'Address', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_emergency_address, 'B', 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(27, 0, 'Tel. Number', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_emergency_cell_no, 'B', 0, 'L');
$pdf->Ln(13);
//* ====== Telecom Section ======
//* MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign = 'T', $fitcell = false)
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(145, 0, 'Do you have relatives working with any local telecom?', 0, 0, 'L');
$chk_telecom_yes = $applicant_relative_telecom == true ? 'true' : '';
$chk_telecom_no = $applicant_relative_telecom == false ? 'true' : '';
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_telecom_yes" value="1" checked="' . $chk_telecom_yes . '"/><label for="chk_telecom_yes">Yes</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_telecom_no" value="1" checked="' . $chk_telecom_no . '"/><label for="chk_telecom_no">No</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, 'If yes, please state the following:', 0, 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(15, 0, 'Name', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65, 0, $applicant_relative_name, 'B', 0, 'L');
$pdf->Cell(3, 0, '', 0, 0, 'L');
$pdf->Cell(25, 0, 'Relationship', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_relative_relationship, 'B', 0, 'L');
$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(36, 0, 'Name of Telecom', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_relative_telecompany, 'B', 0, 'L');
$pdf->Ln(10);
//* ====== Cell Card Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(110, 15, 'Are you or any of your relatives up to 2nd consanguinity (parents, spouse, children, siblings) engage in the sale of Globe, Smart, Sun Cell Cards?', 0, 'L', 0, 0, '', '', true, 0, true, false, 15, 'T', false);
$pdf->Cell(35, 0, '', 0, 0, 'L');
$chk_cell_cards_yes = $applicant_relative_sale_cell_cards == true ? 'true' : '';
$chk_cell_cards_no = $applicant_relative_sale_cell_cards == false ? 'true' : '';
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_cell_cards_yes" value="1" checked="' . $chk_cell_cards_yes . '"/><label for="chk_cell_cards_yes">Yes</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_cell_cards_no" value="1" checked="' . $chk_cell_cards_no . '"/><label for="chk_cell_cards_no">No</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->Ln(18);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, 'If yes, please state the following:', 0, 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(15, 0, 'Name', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65, 0, $applicant_relative_sale_name, 'B', 0, 'L');
$pdf->Cell(3, 0, '', 0, 0, 'L');
$pdf->Cell(25, 0, 'Relationship', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_relative_sale_relationship, 'B', 0, 'L');
$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(50, 0, 'Type of Card/s being sold', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_relative_sale_card_type, 'B', 0, 'L');
$pdf->Ln(10);
//* ====== Banner Relative Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(145, 0, 'Do you have relatives employed by Banner Plasticard Inc.?', 0, 0, 'L');
$chk_banner_yes = $applicant_relative_banner == true ? 'true' : '';
$chk_banner_no = $applicant_relative_banner == false ? 'true' : '';
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_banner_yes" value="1" checked="' . $chk_banner_yes . '"/><label for="chk_banner_yes">Yes</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_banner_no" value="1" checked="' . $chk_banner_no . '"/><label for="chk_banner_no">No</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->Ln(7);
$header_relative_banner = array('', 'Name', '', 'Relationship'); //* column titles
$pdf->TwoFieldTable($header_relative_banner, $data_result_relative_banner, 'banner_relative', 'applicant_employed_banner_name', 'applicant_employed_banner_relationship'); //* table
$pdf->Ln(5);
//* ====== Banner Employed Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(145, 0, 'Have you ever been employed by Banner Plasticard Inc.?', 0, 0, 'L');
$chk_employ_banner_yes = $applicant_employ_banner == true ? 'true' : '';
$chk_employ_banner_no = $applicant_employ_banner == false ? 'true' : '';
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_employ_banner_yes" value="1" checked="' . $chk_employ_banner_yes . '"/><label for="chk_employ_banner_yes">Yes</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_employ_banner_no" value="1" checked="' . $chk_employ_banner_no . '"/><label for="chk_employ_banner_no">No</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, 'If yes, please state the following:', 0, 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(32, 0, 'Inclusive Dates', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20, 0, $applicant_employ_banner_date_from, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, 'To', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20, 0, $applicant_employ_banner_date_to, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, 'Position', 0, 0, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_employ_banner_position, 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(42, 0, '', 0, 0, 'L');
$pdf->Cell(20, 0, '(Mo./Yr.)', 0, 0, 'C');
$pdf->Cell(10, 0, '', 0, 0, 'C');
$pdf->Cell(20, 0, '(Mo./Yr.)', 0, 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'C');
$pdf->Cell(45, 0, 'Reason for seperation', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 0, $applicant_employ_banner_seperation, 'B', 0, 'L');
$pdf->Ln(10);
//* ====== Convicted Crime Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(145, 0, 'Have you ever been charged and/or convicted of any crime/s?', 0, 0, 'L');
$chk_convicted_yes = $applicant_convicted_crime == true ? 'true' : '';
$chk_convicted_no = $applicant_convicted_crime == false ? 'true' : '';
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_convicted_yes" value="1" checked="' . $chk_convicted_yes . '"/><label for="chk_convicted_yes">Yes</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_convicted_no" value="1" checked="' . $chk_convicted_no . '"/><label for="chk_convicted_no">No</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, 'If yes, please state the detail/s:', 0, 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->MultiCell(0, 0, $applicant_convicted_details, 'B', 'J', 0, 0, '', '', true, 0, false, true, 0, 'T', false);
//* MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign = 'T', $fitcell = false)
$pdf->Ln(10);
//* ====== Organization Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(145, 0, 'Are you an active member of any private, civic or similar organization/s?', 0, 0, 'L');
$chk_organizations_yes = $applicant_active_organizations == true ? 'true' : '';
$chk_organizations_no = $applicant_active_organizations == false ? 'true' : '';
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_organizations_yes" value="1" checked="' . $chk_organizations_yes . '"/><label for="chk_organizations_yes">Yes</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_organizations_no" value="1" checked="' . $chk_organizations_no . '"/><label for="chk_organizations_no">No</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, 'If yes, please have them enumerated', 0, 0, 'L');
$pdf->Ln(7);
//* ====== Organization Section ======
$header_organization = array('', 'Name of the Organization', '', 'Address', '', 'Contact Number/s'); //* column titles
$pdf->ThreeFieldTable($header_organization, $data_result_organization, 'organization', 'applicant_organization_name', 'applicant_organization_address', 'applicant_organization_contact_no'); //* table
$pdf->Ln(7);
//* ====== Financial Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(145, 10, 'Do you have an existing financial obligation to any commercial banks and/or financial/lending institution? (e.g SSS, Pag-IBIG etc.)', 0, 'L', 0, 0, '', '', true, 0, true, false, 10, 'T', false);
$chk_financial_yes = $applicant_existing_financial == true ? 'true' : '';
$chk_financial_no = $applicant_existing_financial == false ? 'true' : '';
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_financial_yes" value="1" checked="' . $chk_financial_yes . '"/><label for="chk_financial_yes">Yes</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->MultiCell(20, 0, '<input type="checkbox" name="chk_financial_no" value="1" checked="' . $chk_financial_no . '"/><label for="chk_financial_no">No</label>', 0, 'C', 0, 0, '', '', true, 0, true, true, 0, 'T', false);
$pdf->Ln(12);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, '', 0, 0, 'L');
$pdf->Cell(0, 0, 'If yes, please have them enumerated', 0, 0, 'L');
$pdf->Ln(7);
$header_financial = array('', 'Name of the bank and/or financial institution', '', 'Type of loan'); //* column titles
$pdf->TwoFieldTable($header_financial, $data_result_financial, 'financial', 'applicant_financial_bank_name', 'applicant_financial_type_loan'); //* table
$pdf->Ln(5);
//* ====== Previous Residences Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 0, 'List of previous residence/s within the last seven (7) years', 0, 0, 'L');
$pdf->Ln(7);
$header_residences = array('Previous Address/es', '', 'Inclusive Dates', '', 'Own or Rented'); //* column titles
$pdf->PrevResidencesTable($header_residences, $data_result_prev_residences); //* table
$pdf->Ln(7);
//* ====== Certify Section ======
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 30, 'I certify that the foregoing information is true and correct to the best of my own personal knowledged and based on authentic government-issued documents. I also understand and agree that should I be found guilty of intentionally withholding any information through a personal background check performed by the company or its authorized third-party provider. I could be subjected to a company investigation against Banner Plasticard, Inc. Company Rules and Regulations including applicable provision/s of the Labor Code of the Philippines. To this end, I hereby consent to a polygraph test if and when required by the company to ascertain the truth of the foregoing information.', 0, 'J', 0, 0, '', '', true, 0, false, true, 0, 'T', false);
$pdf->Ln(45);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(20, 0, 'Signed at', 0, 0, 'L');
$pdf->Cell(70, 0, '', 'B', 0, 'C');
$pdf->Cell(25, 0, ',Philippines', 0, 0, 'L');
$pdf->Cell(30, 0, '', 0, 0, 'L');
$pdf->Cell(11, 0, 'Date', 0, 0, 'L');
$pdf->Cell(9, 0, date_format(date_create($applicant_date_entry), 'm'), 'B', 0, 'C');
$pdf->Cell(3, 0, '-', 0, 0, 'L');
$pdf->Cell(9, 0, date_format(date_create($applicant_date_entry), 'd'), 'B', 0, 'C');
$pdf->Cell(3, 0, '-', 0, 0, 'L');
$pdf->Cell(9, 0, date_format(date_create($applicant_date_entry), 'y'), 'B', 0, 'C');
$pdf->Ln(25);
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(0, 0, $applicant_fullname, 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(0, 0, 'Applicant\'s signature over printed name', 0, 0, 'C');
$pdf->Ln(7);
//* ====== Thumb Mark Section ======
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LTR', 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LTR', 0, 'C');
$pdf->Ln();
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Ln();
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Ln();
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Ln();
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Ln();
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LR', 0, 'C');
$pdf->Ln();
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LBR', 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, '', 'LBR', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(125, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, 'Left Thumb Mark', 0, 0, 'C');
$pdf->Cell(6, 0, '', 0, 0, 'C');
$pdf->Cell(28, 0, 'Right Thumb Mark', 0, 0, 'C');
//* ====== Recommend Section ======
$pdf->AddPage(); //* Add page
//* RoundedRect(x, y, w, h, radius of circle to be rounded, round_corner = '1111', style = '', border_style = nil, fill_color = nil)
$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
$pdf->RoundedRect(12.5, 16, 191, 60, 0, '0000', '', 0, 0);
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(5, 0, 'I,', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(70, 0, $applicant_referred_by, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(25, 0, 'recommend', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(70, 0, $applicant_fullname, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 0, 'to work', 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(91, 0, 'with Banner Plasticard, Inc. The applicant is my', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(43, 0, $applicant_referred_by_relationship, 'B', 0, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'and. therefore knows them', 0, 0, 'L');
$pdf->Ln(7);
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(186, 0, 'to be hardworking individual, and not convicted of any crime involving moral turpitude.', 0, 0, 'J');
$pdf->Ln(10);
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(186, 0, 'I do not know of any fact that would make him/her undesirable to the company as an employee.', 0, 0, 'J');
$pdf->Ln(17);
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(60, 0, $applicant_referred_by, 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(3, 0, '', 0, 0, 'C');
$pdf->Cell(60, 0, 'Signature over printed name', 0, 0, 'C');
//* ====== Checked Section ======
$pdf->Ln(15);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(60, 0, 'Checked By:', 0, 0, 'L');
$pdf->Ln(16);
$pdf->Cell(60, 0, 'Karen Belano', 'B', 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 0, 'HR Manager', 0, 0, 'L');
$pdf->Ln(4);
$pdf->Cell(10, 0, 'Date', 0, 0, 'L');
$pdf->Cell(6, 0, '', 'B', 0, 'C');
$pdf->Cell(3, 0, '-', 0, 0, 'L');
$pdf->Cell(6, 0, '', 'B', 0, 'C');
$pdf->Cell(3, 0, '-', 0, 0, 'L');
$pdf->Cell(6, 0, '', 'B', 0, 'C');




//* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
// $pdf->setImageScale(7);
// $pdf->Image('@' . base64_decode($applicant_image), 10, null, 50, 50, '', '', '', false, 300, '', false, false, 0, false, false, false);
// $img_base64_encoded = 'data:image/jpeg;base64,' . $applicant_image;
// $img = '<img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_base64_encoded) . '">';
// $img = '<img src="data:image/jpeg;base64,' . $applicant_image . '"';
// $pdf->writeHTML($img, true, false, true, false, '');
// print $img_base64_encoded;
$filename = "PHS - " . $applicant_fullname . " - " . date("F d, Y") . ".pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document