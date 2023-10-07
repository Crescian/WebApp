<?php
date_default_timezone_set('Asia/Manila');
class ITUserAccess
{
    public function sqlQuery($sqlstring, $connection)
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
    public function fetchSignature($emp_name, $php_fetch_bannerweb_api)
    {
        $empSignature = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = '{$emp_name}';";
        $data_result = self::sqlQuery($empSignature, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $empSignature_row = $row['employee_signature'];
        }
        return $empSignature_row;
    }
    public function generate_defective_refno($php_fetch_itasset_api, $inField, $inTable)
    {
        $currYear = date('y');
        $fetchRefNo = "SELECT " . $inField . " FROM " . $inTable . ";";
        $data_result = self::sqlQuery($fetchRefNo, $php_fetch_itasset_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $fetchRefNo_row) {
                $getYear =  substr($fetchRefNo_row[$inField], 5, 2);
            }
        }
        if ($currYear != $getYear) {
            $defective_ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($fetchRefNo_row[$inField], 0, 4);
            $counter = intval($currCount) + 1;
            $defective_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        return $defective_ref_no;
    }
    public function saveUserAccess($php_fetch_itasset_api, $php_fetch_bannerweb_api, $php_update_itasset_api, $php_insert_itasset_api, $control_no, $date_needed, $access, $priority, $domainAccount, $mail_account, $file_storage_access, $in_house_access, $purpose, $preparedBy, $approvedBy, $notedBy)
    {
        $prepared_by_signature = self::fetchSignature($preparedBy, $php_fetch_bannerweb_api);
        $approved_by_signature = self::fetchSignature($approvedBy, $php_fetch_bannerweb_api);
        $noted_by_signature = self::fetchSignature($notedBy, $php_fetch_bannerweb_api);
        $sqlstringValidate = "SELECT control_no FROM tblit_user_access_request;";
        $data_result = self::sqlQuery($sqlstringValidate, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $control_no_validate = $row['control_no'];
        }
        if ($control_no_validate == $control_no) {
            echo 'Exist';
        } else {
            $updateRefno = "UPDATE tblit_control_no SET user_access_control_no = '{$control_no}';";
            self::sqlQuery($updateRefno, $php_update_itasset_api);
            $sqlstring = "INSERT INTO tblit_user_access_request(control_no,date_need,access,priority,domain_account,mail_account,file_storage_access,in_house_access,purpose,prepared_by,prepared_by_sign,approved_by,approved_by_sign,noted_by,noted_by_sign)
        VALUES('{$control_no}','{$date_needed}','{$access}','{$priority}','{$domainAccount}','{$mail_account}','{$file_storage_access}','{$in_house_access}','{$purpose}','{$preparedBy}','{$prepared_by_signature}','{$approvedBy}','{$approved_by_signature}','{$notedBy}','{$noted_by_signature}');";
            self::sqlQuery($sqlstring, $php_insert_itasset_api);
        }
    }
    public function loadDepartmentHead($php_fetch_bannerweb_api)
    {
        $sqlstring = "SELECT (emp_fn || ' ' || emp_mi || '. ' || emp_sn || emp_ext) AS full_name, pos_code FROM prl_employee WHERE job_level in('Level-08', 'Level-07') ORDER BY full_name;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $data["deptHead"][$row["pos_code"]] = $row["full_name"];
        }
        return json_encode($data);
    }
    public function loadGetPosCode($php_fetch_bannerweb_api, $user_department)
    {
        $sqlstring = "SELECT pos_code FROM prl_employee WHERE dept_code = '{$user_department}' AND job_level in ('Level-08', 'Level-07');";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $resultData_List['pos_code'] = $row['pos_code'];
        }
        return json_encode($resultData_List);
    }
    public function loadControlNo($php_fetch_itasset_api)
    {
        $sqlstring = "SELECT control_no FROM tblit_user_access_request;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $resultData_List[] = $row['control_no'];
            }
        }
        return json_encode($resultData_List);
    }
    public function previewControlPreview($php_fetch_itasset_api, $control_no)
    {
        $data = array();
        $sqlstring = "SELECT * FROM tblit_user_access_request WHERE control_no = '{$control_no}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $data['date_need'] = $row['date_need'];
                $data['access'] = $row['access'];
                $data['priority'] = $row['priority'];
                $data['domain_account'] = $row['domain_account'];
                $data['mail_account'] = $row['mail_account'];
                $data['file_storage_access'] = $row['file_storage_access'];
                $data['in_house_access'] = $row['in_house_access'];
                $data['purpose'] = $row['purpose'];
                $data['prepared_by'] = $row['prepared_by'];
                $data['approved_by'] = $row['approved_by'];
                $data['noted_by'] = $row['noted_by'];
            }
        }
        return json_encode($data);
    }
    public function update($php_fetch_itasset_api, $control_no, $date_needed, $access, $priority, $domainAccount, $mail_account, $file_storage_access, $in_house_access, $purpose, $preparedBy, $approvedBy, $notedBy)
    {
        $sqlstring = "UPDATE tblit_user_access_request 
        SET date_need = '{$date_needed}',access = '{$access}', priority = '{$priority}', domain_account = '{$domainAccount}',mail_account = '{$mail_account}', 
        file_storage_access = '{$file_storage_access}', in_house_access = '{$in_house_access}', purpose = '{$purpose}', prepared_by = '{$preparedBy}', approved_by = '{$approvedBy}', noted_by = '{$notedBy}' 
        WHERE control_no = '{$control_no}';";
        self::sqlQuery($sqlstring, $php_fetch_itasset_api);
    }
    public function loadEmployee($php_fetch_bannerweb_api)
    {
        $sqlstring = "SELECT (emp_fn || ' ' || emp_mi || '. ' || emp_sn || emp_ext) as fullname FROM prl_employee 
				ORDER BY (emp_fn || ' ' || emp_mi || '. ' || emp_sn || emp_ext) ASC;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $data[] = $row['fullname'];
        }
        return json_encode($data);
    }
}
