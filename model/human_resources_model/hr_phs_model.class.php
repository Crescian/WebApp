<?php
class PersonalHistoryStatement
{
    private function sqlQuery($sqlstring, $connection)
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

    public function loadPhsApplicantTableData($php_fetch_human_resources, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'applicant_date_entry',
            1 => 'applicant_fname',
            2 => 'applicant_fname'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT * FROM hr_applicant_info WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(applicant_date_entry, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR applicant_fname ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['applicant_date_entry'] == '' ? '---- - -- - --' : date_format(date_create($row['applicant_date_entry']), 'Y-m-d h:i:s A'),
                $row['applicant_sname'] . ' ' . $row['applicant_fname'] . ', ' . $row['applicant_mname'],
                $row['applicant_referred_by'] == '' ? '-' : $row['applicant_referred_by'],
                [$row['applicantid'], $row['applicant_referred_by'] == '' ? '-' : $row['applicant_referred_by']]
            );
        }
        //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
    }

    public function saveReferralApplicant($php_update_human_resources, $applicantid, $referred_by, $referred_by_relationship)
    {
        $sqlstring = "UPDATE hr_applicant_info SET applicant_referred_by = '{$referred_by}' , applicant_referred_by_relationship = '{$referred_by_relationship}' WHERE applicantid = '{$applicantid}';";
        self::sqlQuery($sqlstring, $php_update_human_resources);
    }

    public function previewPhsApplicantData($php_fetch_human_resources, $applicantid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT *,encode(applicant_image, 'escape') AS applicant_image FROM hr_applicant_info WHERE applicantid = '{$applicantid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List['applicant_image'] = $row['applicant_image'];
            $itemData_List['applicant_sname'] = $row['applicant_sname'];
            $itemData_List['applicant_fname'] = $row['applicant_fname'];
            $itemData_List['applicant_extname'] = $row['applicant_extname'];
            $itemData_List['applicant_mname'] = $row['applicant_mname'];
            $itemData_List['applicant_maiden_sname'] = $row['applicant_maiden_sname'];
            $itemData_List['applicant_maiden_fname'] = $row['applicant_maiden_fname'];
            $itemData_List['applicant_maiden_mname'] = $row['applicant_maiden_mname'];
            $itemData_List['applicant_maiden_sname'] = $row['applicant_maiden_sname'];
            $itemData_List['applicant_maiden_fname'] = $row['applicant_maiden_fname'];
            $itemData_List['applicant_maiden_mname'] = $row['applicant_maiden_mname'];
            $itemData_List['applicant_maiden_sname'] = $row['applicant_maiden_sname'];
            $itemData_List['applicant_maiden_fname'] = $row['applicant_maiden_fname'];
            $itemData_List['applicant_maiden_mname'] = $row['applicant_maiden_mname'];
            $itemData_List['applicant_maiden_sname'] = $row['applicant_maiden_sname'];
            $itemData_List['applicant_maiden_fname'] = $row['applicant_maiden_fname'];
            $itemData_List['applicant_maiden_mname'] = $row['applicant_maiden_mname'];
            $itemData_List['applicant_alias'] = $row['applicant_alias'];
            $itemData_List['applicant_gender'] = $row['applicant_gender'];
            $itemData_List['applicant_citizenship'] = $row['applicant_citizenship'];
            $itemData_List['applicant_religion'] = $row['applicant_religion'];
            $itemData_List['applicant_bloodtype'] = $row['applicant_bloodtype'];
            $itemData_List['applicant_height'] = $row['applicant_height'];
            $itemData_List['applicant_weight'] = $row['applicant_weight'];
            $itemData_List['applicant_eye_color'] = $row['applicant_eye_color'];
            $itemData_List['applicant_hair_color'] = $row['applicant_hair_color'];
            $itemData_List['applicant_birth_date'] = $row['applicant_birth_date'];
            $itemData_List['applicant_age'] = $row['applicant_age'];
            $itemData_List['applicant_birth_place'] = $row['applicant_birth_place'];
            $itemData_List['applicant_contact_no'] = $row['applicant_contact_no'];
            $itemData_List['applicant_email_address'] = $row['applicant_email_address'];
            $itemData_List['applicant_civil_status'] = $row['applicant_civil_status'];
            $itemData_List['applicant_present_unit'] = $row['applicant_present_unit'];
            $itemData_List['applicant_present_lot'] = $row['applicant_present_lot'];
            $itemData_List['applicant_present_street'] = $row['applicant_present_street'];
            $itemData_List['applicant_present_sub_village'] = $row['applicant_present_sub_village'];
            $itemData_List['applicant_present_region'] = $row['applicant_present_region'];
            $itemData_List['applicant_present_province'] = $row['applicant_present_province'];
            $itemData_List['applicant_present_city'] = $row['applicant_present_city'];
            $itemData_List['applicant_present_barangay'] = $row['applicant_present_barangay'];
            $itemData_List['applicant_present_zipcode'] = $row['applicant_present_zipcode'];
            $itemData_List['applicant_permanent_unit'] = $row['applicant_permanent_unit'];
            $itemData_List['applicant_permanent_lot'] = $row['applicant_permanent_lot'];
            $itemData_List['applicant_permanent_street'] = $row['applicant_permanent_street'];
            $itemData_List['applicant_permanent_sub_village'] = $row['applicant_permanent_sub_village'];
            $itemData_List['applicant_permanent_region'] = $row['applicant_permanent_region'];
            $itemData_List['applicant_permanent_province'] = $row['applicant_permanent_province'];
            $itemData_List['applicant_permanent_city'] = $row['applicant_permanent_city'];
            $itemData_List['applicant_permanent_barangay'] = $row['applicant_permanent_barangay'];
            $itemData_List['applicant_permanent_zipcode'] = $row['applicant_permanent_zipcode'];
            $itemData_List['applicant_sss_no'] = $row['applicant_sss_no'];
            $itemData_List['applicant_philhealth_no'] = $row['applicant_philhealth_no'];
            $itemData_List['applicant_tin_no'] = $row['applicant_tin_no'];
            $itemData_List['applicant_pagibig_no'] = $row['applicant_pagibig_no'];
            $itemData_List['applicant_spouse_fname'] = $row['applicant_spouse_fname'];
            $itemData_List['applicant_spouse_mname'] = $row['applicant_spouse_mname'];
            $itemData_List['applicant_spouse_sname'] = $row['applicant_spouse_sname'];
            $itemData_List['applicant_spouse_extname'] = $row['applicant_spouse_extname'];
            $itemData_List['applicant_spouse_occupation'] = $row['applicant_spouse_occupation'];
            $itemData_List['applicant_spouse_employer'] = $row['applicant_spouse_employer'];
            $itemData_List['applicant_father_fname'] = $row['applicant_father_fname'];
            $itemData_List['applicant_father_mname'] = $row['applicant_father_mname'];
            $itemData_List['applicant_father_sname'] = $row['applicant_father_sname'];
            $itemData_List['applicant_father_extname'] = $row['applicant_father_extname'];
            $itemData_List['applicant_father_occupation'] = $row['applicant_father_occupation'];
            $itemData_List['applicant_father_employer'] = $row['applicant_father_employer'];
            $itemData_List['applicant_mother_fname'] = $row['applicant_mother_fname'];
            $itemData_List['applicant_mother_mname'] = $row['applicant_mother_mname'];
            $itemData_List['applicant_mother_sname'] = $row['applicant_mother_sname'];
            $itemData_List['applicant_mother_occupation'] = $row['applicant_mother_occupation'];
            $itemData_List['applicant_mother_employer'] = $row['applicant_mother_employer'];
            $itemData_List['applicant_tertiary_school'] = $row['applicant_tertiary_school'];
            $itemData_List['applicant_tertiary_address'] = $row['applicant_tertiary_address'];
            $itemData_List['applicant_tertiary_date_from'] = $row['applicant_tertiary_date_from'] == '' ? '-' : date_format(date_create($row['applicant_tertiary_date_from']), 'M Y');
            $itemData_List['applicant_tertiary_date_to'] = $row['applicant_tertiary_date_to'] == '' ? '-' : date_format(date_create($row['applicant_tertiary_date_to']), 'M Y');
            $itemData_List['applicant_sr_high_school'] = $row['applicant_sr_high_school'];
            $itemData_List['applicant_sr_high_address'] = $row['applicant_sr_high_address'];
            $itemData_List['applicant_sr_high_date_from'] = $row['applicant_sr_high_date_from'] == '' ? '-' : date_format(date_create($row['applicant_sr_high_date_from']), 'M Y');
            $itemData_List['applicant_sr_high_date_to'] = $row['applicant_sr_high_date_to'] == '' ? '-' : date_format(date_create($row['applicant_sr_high_date_to']), 'M Y');
            $itemData_List['applicant_jr_high_school'] = $row['applicant_jr_high_school'];
            $itemData_List['applicant_jr_high_address'] = $row['applicant_jr_high_address'];
            $itemData_List['applicant_jr_high_date_from'] = $row['applicant_jr_high_date_from'] == '' ? '-' : date_format(date_create($row['applicant_jr_high_date_from']), 'M Y');
            $itemData_List['applicant_jr_high_date_to'] = $row['applicant_jr_high_date_to'] == '' ? '-' : date_format(date_create($row['applicant_jr_high_date_to']), 'M Y');
            $itemData_List['applicant_elem_school'] = $row['applicant_elem_school'];
            $itemData_List['applicant_elem_address'] = $row['applicant_elem_address'];
            $itemData_List['applicant_elem_date_from'] = $row['applicant_elem_date_from'] == '' ? '-' : date_format(date_create($row['applicant_elem_date_from']), 'M Y');
            $itemData_List['applicant_elem_date_to'] = $row['applicant_elem_date_to'] == '' ? '-' : date_format(date_create($row['applicant_elem_date_to']), 'M Y');
            $itemData_List['applicant_special_skills'] = $row['applicant_special_skills'];
            $itemData_List['applicant_awards_received'] = $row['applicant_awards_received'];
            $itemData_List['applicant_emergency_name'] = $row['applicant_emergency_name'];
            $itemData_List['applicant_emergency_relationship'] = $row['applicant_emergency_relationship'];
            $itemData_List['applicant_emergency_address'] = $row['applicant_emergency_address'];
            $itemData_List['applicant_emergency_cell_no'] = $row['applicant_emergency_cell_no'];
            $itemData_List['applicant_relative_telecom'] = $row['applicant_relative_telecom'];
            $itemData_List['applicant_relative_name'] = $row['applicant_relative_name'];
            $itemData_List['applicant_relative_relationship'] = $row['applicant_relative_relationship'];
            $itemData_List['applicant_relative_telecompany'] = $row['applicant_relative_telecompany'];
            $itemData_List['applicant_relative_sale_cell_cards'] = $row['applicant_relative_sale_cell_cards'];
            $itemData_List['applicant_relative_sale_name'] = $row['applicant_relative_sale_name'];
            $itemData_List['applicant_relative_sale_relationship'] = $row['applicant_relative_sale_relationship'];
            $itemData_List['applicant_relative_sale_card_type'] = $row['applicant_relative_sale_card_type'];
            $itemData_List['applicant_relative_banner'] = $row['applicant_relative_banner'];
            $itemData_List['applicant_employ_banner'] = $row['applicant_employ_banner'];
            $itemData_List['applicant_employ_banner_date_from'] = $row['applicant_employ_banner_date_from'] == '' ? '-' : date_format(date_create($row['applicant_employ_banner_date_from']), 'M Y');
            $itemData_List['applicant_employ_banner_date_to'] = $row['applicant_employ_banner_date_to'] == '' ? '-' : date_format(date_create($row['applicant_employ_banner_date_to']), 'M Y');
            $itemData_List['applicant_employ_banner_position'] = $row['applicant_employ_banner_position'];
            $itemData_List['applicant_employ_banner_seperation'] = $row['applicant_employ_banner_seperation'];
            $itemData_List['applicant_convicted_crime'] = $row['applicant_convicted_crime'];
            $itemData_List['applicant_convicted_details'] = $row['applicant_convicted_details'];
            $itemData_List['applicant_active_organizations'] = $row['applicant_active_organizations'];
            $itemData_List['applicant_existing_financial'] = $row['applicant_existing_financial'];
        }
        return json_encode($itemData_List);
    }

    public function previewPhsTwoFieldData($php_fetch_human_resources, $applicantid, $inTable, $inField1, $inField2)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM {$inTable} WHERE applicant_id = '{$applicantid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row[$inField1],
                $row[$inField2]
            );
        }
        return json_encode($itemData_List);
    }

    public function previewPhsThreeFieldData($php_fetch_human_resources, $applicantid, $inTable, $inField1, $inField2, $inField3)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM {$inTable} WHERE applicant_id = '{$applicantid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row[$inField1],
                $row[$inField2],
                $row[$inField3]
            );
        }
        return json_encode($itemData_List);
    }

    public function previewPhsFourFieldData($php_fetch_human_resources, $applicantid, $inTable, $inField1, $inField2, $inField3, $inField4)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM {$inTable} WHERE applicant_id = '{$applicantid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row[$inField1],
                $row[$inField2],
                $row[$inField3],
                $row[$inField4]
            );
        }
        return json_encode($itemData_List);
    }

    public function previewPhsSixFieldData($php_fetch_human_resources, $applicantid, $inTable, $inField1, $inField2, $inField3, $inField4, $inField5, $inField6)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM {$inTable} WHERE applicant_id = '{$applicantid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_human_resources);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row[$inField1],
                $row[$inField2],
                $row[$inField3],
                $row[$inField4],
                $row[$inField5],
                $row[$inField6]
            );
        }
        return json_encode($itemData_List);
    }

    public function updatePhsApplicantData($php_update_human_resources, $applicantid, $bloodtype, $sss_number, $tin_number, $philhealth_number,  $pagibig_number)
    {
        $sqlstring = "UPDATE hr_applicant_info SET applicant_bloodtype = '{$bloodtype}', applicant_sss_no = '{$sss_number}', applicant_philhealth_no = '{$philhealth_number}'
        , applicant_tin_no = '{$tin_number}', applicant_pagibig_no = '{$pagibig_number}' WHERE applicantid = '{$applicantid}';";
        self::sqlQuery($sqlstring, $php_update_human_resources);
    }
}
