<?php
// ini_set('max_execution_time', '0'); //* for infinite time of execution 
date_default_timezone_set('Asia/Manila');
class PersoFileDeletion
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

    private function fetchSignature($php_fetch_bannerweb_api, $emp_name)
    {
        $sqlstring = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = '{$emp_name}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            return $row['employee_signature'];
        }
    }

    private function checkHoliday($php_fetch_perso_api, $received_date)
    {
        $received_day = date('d', strtotime($received_date));
        $received_month = date('m', strtotime($received_date));
        $currentYear = date('Y');

        $sqlstring = "SELECT COUNT(*) AS row_count FROM bpi_perso_holiday_entry WHERE TO_CHAR(holiday_date,'DD') = '{$received_day}' AND TO_CHAR(holiday_date,'MM') = '{$received_month}' AND TO_CHAR(holiday_date,'YYYY') = '{$currentYear}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            if ($row['row_count'] > 0) {
                $received_date = date('Y-m-d', strtotime($received_date . '+ 1 days'));
            } else {
                $received_date = date('Y-m-d', strtotime($received_date));
            }
        }
        return $received_date;
    }

    private function CalculateBusinessDaysFromInputDate($php_fetch_perso_api, $received_date, $bussinessDays)
    {
        for ($x = 1; $x <= $bussinessDays; $x++) {
            switch (date('D', strtotime($received_date))) {
                case 'Sun':
                    $received_date = self::checkHoliday($php_fetch_perso_api, date('Y-m-d', strtotime($received_date . '+ 1 days')));
                    break;
                case 'Mon':
                case 'Tue':
                case 'Wed':
                case 'Thu':
                case 'Fri':
                    $received_date = self::checkHoliday($php_fetch_perso_api, date('Y-m-d', strtotime($received_date . '+ 1 days')));
                    break;
                case 'Sat':
                    $received_date = self::checkHoliday($php_fetch_perso_api, date('Y-m-d', strtotime($received_date . '+ 2 days')));
                    break;
            }
        }

        if (date('D', strtotime($received_date)) == 'Sun') {
            $received_date = date('Y-m-d', strtotime($received_date . '- 1 days'));
        }
        return $received_date;
    }

    public function loadFileDeletedTable($php_fetch_perso_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'file_received_date',
            1 => 'file_company',
            2 => 'file_filename',
            3 => 'file_filesize',
            4 => 'file_delivery_date',
            5 => 'file_deleted_date'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT filedeletionid,file_company,file_filename,file_filesize,file_received_date,file_delivery_date,file_deleted_date,file_certified FROM bpi_perso_file_deletion WHERE file_certified = true";
        // $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        // $result_total_record = array_sum(array_map("count", $data_result));
        $result_stmt = $php_fetch_perso_api->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(file_received_date, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR file_company ILIKE '%{$searchValue}%' OR file_filename ILIKE '%{$searchValue}%'
            OR file_filesize ILIKE '%{$searchValue}%' OR TO_CHAR(file_delivery_date, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR TO_CHAR(file_deleted_date, 'YYYY-MM-DD') ILIKE '%{$searchValue}%')";
            // $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
            $result_stmt = $php_fetch_perso_api->prepare($sqlstring);
            $result_stmt->execute();
        }
        // $result_total_record_filtered = array_sum(array_map("count", $data_result));
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        // $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $result_stmt = $php_fetch_perso_api->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        // foreach ($data_result['data'] as $row) {
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List[] = array(
                $row['file_received_date'],
                $row['file_company'],
                $row['file_filename'],
                $row['file_filesize'],
                $row['file_delivery_date'],
                $row['file_deleted_date'],
                $row['filedeletionid']
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

    public function loadHolidayTable($php_fetch_perso_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'holiday_day',
            1 => 'holiday_month'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT holidayid, TO_CHAR(holiday_date,'Month') AS holiday_month,TO_CHAR(holiday_date,'DD') AS holiday_day FROM bpi_perso_holiday_entry WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(holiday_date,'DD') ILIKE '%{$searchValue}%' OR TO_CHAR(holiday_date,'Month') ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);

        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['holiday_month'],
                $row['holiday_day'],
                $row['holidayid']
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

    public function loadForCertificationTable($php_fetch_perso_api, $file_company, $file_date_from, $file_date_to, $file_chk_delivery)
    {
        $inField1 = $file_chk_delivery == 'true' ? 'file_delivery_date' : 'file_received_date';
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'file_filename',
            1 => 'file_filesize',
            2 => 'file_received_date',
            3 => 'file_delivery_date',
            4 => 'file_deleted_date'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT file_filename,file_filesize,file_received_date,file_delivery_date,file_deleted_date,filedeletionid FROM bpi_perso_file_deletion WHERE file_company = '{$file_company}' AND {$inField1} BETWEEN '{$file_date_from}' AND '{$file_date_to}' AND file_certified = false";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['file_filename'],
                $row['file_filesize'],
                $row['file_received_date'],
                $row['file_delivery_date'],
                $row['file_deleted_date'],
                '<input type="checkbox" class="rowChkBox">',
                $row['filedeletionid']
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

    public function loadFileReceivedCertifiedCount($inConnection, $inTable, $inField, $inFieldValue)
    {
        $sqlstring = "SELECT COUNT(*) as total_count FROM {$inTable} WHERE {$inField} = {$inFieldValue};";
        $data_result = self::sqlQuery($sqlstring, $inConnection);
        foreach ($data_result['data'] as $row) {
            return json_encode($row['total_count']);
        }
    }

    public function loadCompanySelectValue($php_fetch_perso_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT file_company FROM bpi_perso_file_deletion;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[$row['file_company']] = $row['file_company'];
        }
        return json_encode($itemData_List);
    }

    public function loadSignatorySelectValue($php_fetch_bannerweb_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT (emp_fn || ' ' || emp_sn) AS emp_fullname FROM prl_employee WHERE pos_code = 'DPR' OR pos_code = 'EAM';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[$row['emp_fullname']] = $row['emp_fullname'];
        }
        return json_encode($itemData_List);
    }

    public function loadReferenceNo($php_fetch_perso_api)
    {
        $sqlstring = "SELECT * FROM bpi_perso_file_del_ref_no;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $referrence_no = $row['file_reference_no'];
            $currYear = date('y');
            $getYear =  substr($referrence_no, 5, 2);
            if ($currYear != $getYear) {
                $ref_no = '0001-' . $currYear;
            } else {
                $currCount = substr($referrence_no, 0, 4);
                $counter = intval($currCount) + 1;
                $ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
            }
            return json_encode($ref_no);
        }
    }

    public function updateReferenceNo($php_update_perso_api, $reference_no)
    {
        $sqlstring = "UPDATE bpi_perso_file_del_ref_no SET file_reference_no = '{$reference_no}';";
        self::sqlQuery($sqlstring, $php_update_perso_api);
    }

    public function saveFileCertification($php_fetch_bannerweb_api, $php_update_perso_api, $filedeletionid, $prepared_by, $checked_by, $noted_by, $reference_no)
    {
        $prepared_by_sign = self::fetchSignature($php_fetch_bannerweb_api, $prepared_by);
        $checked_by_sign = self::fetchSignature($php_fetch_bannerweb_api, $checked_by);
        $noted_by_sign = self::fetchSignature($php_fetch_bannerweb_api, $noted_by);

        $sqlstring = "UPDATE bpi_perso_file_deletion SET prepared_by = '{$prepared_by}', prepared_by_sign = '{$prepared_by_sign}', checked_by = '{$checked_by}', checked_by_sign = '{$checked_by_sign}', noted_by = '{$noted_by}', noted_by_sign = '{$noted_by_sign}',
        file_reference_no = '{$reference_no}', file_certified = true WHERE filedeletionid = '{$filedeletionid}';";
        self::sqlQuery($sqlstring, $php_update_perso_api);
    }

    public function loadDeletionReferenceNo($php_fetch_perso_api, $file_company, $date_from, $date_to, $chkReceivedDate, $chkDeletionDate)
    {
        $inField1 = ($chkReceivedDate == 'true') ? 'file_received_date' : (($chkDeletionDate == 'true') ? 'file_deleted_date' : 'file_delivery_date');
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT file_reference_no FROM bpi_perso_file_deletion WHERE file_company ILIKE '%{$file_company}%' AND {$inField1} BETWEEN '{$date_from}' AND '{$date_to}' AND file_certified = true;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[$row['file_reference_no']] = $row['file_reference_no'];
        }
        return json_encode($itemData_List);
    }

    public function quarterlyRecordCheck($php_fetch_perso_api, $file_company, $date_from, $date_to)
    {
        $sqlstring = "SELECT COUNT(*) AS row_count FROM bpi_perso_file_deletion WHERE file_company ILIKE '%{$file_company}%' AND TO_CHAR(file_received_date, 'MM YYYY') BETWEEN '{$date_from}' AND '{$date_to}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            if ($row['row_count'] > 0) {
                return json_encode('existing');
            } else {
                return json_encode('no record');
            }
        }
    }

    public function saveManualFileEntry($php_fetch_perso_api, $php_insert_perso_api, $php_fetch_info_sec_api, $php_insert_info_sec_api, $php_update_info_sec_api, $manual_file_company, $manual_file_received_date, $manual_file_filename, $manual_file_file_size, $manual_file_for_deletion_chk, $manual_file_deletion_date)
    {
        if (str_contains($manual_file_filename, '\\')) {
            $file_received_compare = str_replace('\\', '\\\\', $manual_file_filename);
        } else {
            $file_received_compare = $manual_file_filename;
        }

        if ($manual_file_for_deletion_chk == 'true') {
            $sqlFetch = "SELECT COUNT(*) AS row_count,sftp_retentionid,sftp_file_received_date_time FROM info_sec_sftp_file_retention WHERE sftp_company_name ILIKE '%{$manual_file_company}%' AND sftp_filename ILIKE '%{$file_received_compare}%' AND TO_CHAR(sftp_file_received_date_time, 'YYYY-MM-DD') = '{$manual_file_received_date}' AND sftp_category = 'Manual' GROUP BY sftp_retentionid,sftp_file_received_date_time;";
            $data_result_fetch = self::sqlQuery($sqlFetch, $php_fetch_info_sec_api);
            foreach ($data_result_fetch['data'] as $row_fetch) {
                if ($row_fetch['row_count'] == 0) {
                    return json_encode('no record');
                } else {
                    $sftp_retentionid = $row_fetch['sftp_retentionid'];
                    $sqlstring = "UPDATE info_sec_sftp_file_retention SET sftp_file_deleted_date_time = '{$manual_file_deletion_date}', sftp_deleted = true WHERE sftp_retentionid = '{$sftp_retentionid}';";
                    self::sqlQuery($sqlstring, $php_update_info_sec_api);

                    //* Save File to Perso File Deletion Database for Generation of Client File Deletion
                    $data_received_date = date_format(date_create($row_fetch['sftp_file_received_date_time']), 'Y-m-d');
                    $delivery_date = self::CalculateBusinessDaysFromInputDate($php_fetch_perso_api, $data_received_date, '3');

                    $sqlFileDeletion = "INSERT INTO bpi_perso_file_deletion(file_company,file_filename,file_filesize,file_received_date,file_delivery_date,file_deleted_date) 
                    VALUES('{$manual_file_company}','{$manual_file_filename}','{$manual_file_file_size}','{$data_received_date}','{$delivery_date}','{$manual_file_deletion_date}');";
                    self::sqlQuery($sqlFileDeletion, $php_insert_perso_api);
                    return json_encode('save');
                }
            }
        } else {
            $chkExist = "SELECT COUNT(*) AS row_count FROM info_sec_sftp_file_retention WHERE sftp_company_name ILIKE '%{$manual_file_company}%' AND sftp_filename ILIKE '%{$file_received_compare}%' AND TO_CHAR(sftp_file_received_date_time, 'YYYY-MM-DD') = '{$manual_file_received_date}';";
            $data_result_exist = self::sqlQuery($chkExist, $php_fetch_info_sec_api);
            foreach ($data_result_exist['data'] as $row_exist) {
                if ($row_exist['row_count'] == 0) {
                    $sqlstring = "INSERT INTO info_sec_sftp_file_retention(sftp_company_name,sftp_filename,sftp_file_received_date_time,sftp_filesize,sftp_category) VALUES('{$manual_file_company}','{$manual_file_filename}','{$manual_file_received_date}','{$manual_file_file_size}','Manual');";
                    self::sqlQuery($sqlstring, $php_insert_info_sec_api);
                    return json_encode('save');
                } else {
                    return json_encode('existing');
                }
            }
        }
    }

    public function saveHolidayDate($php_fetch_perso_api, $php_insert_perso_api, $holiday_date)
    {
        $chkExist = "SELECT COUNT(*) AS row_count FROM bpi_perso_holiday_entry WHERE holiday_date = '{$holiday_date}';";
        $data_result_exist = self::sqlQuery($chkExist, $php_fetch_perso_api);
        foreach ($data_result_exist['data'] as $row_exist) {
            if ($row_exist['row_count'] == 0) {
                $sqlstring = "INSERT INTO bpi_perso_holiday_entry(holiday_date) VALUES('{$holiday_date}');";
                self::sqlQuery($sqlstring, $php_insert_perso_api);
                return json_encode('save');
            } else {
                return json_encode('existing');
            }
        }
    }

    public function removeHolidayDate($php_update_perso_api, $holidayid)
    {
        $sqlstring = "DELETE FROM bpi_perso_holiday_entry WHERE holidayid = '{$holidayid}';";
        self::sqlQuery($sqlstring, $php_update_perso_api);
    }

    public function checkRecordChecklist($php_fetch_info_sec_api, $dateFilter)
    {
        $chkExist = "SELECT * FROM info_sec_sftp_file_retention WHERE TO_CHAR(sftp_file_received_date_time, 'YYYY-MM-DD') = '{$dateFilter}' AND sftp_deleted = false;";
        $data_result_exist = self::sqlQuery($chkExist, $php_fetch_info_sec_api);
        $row_count = array_sum(array_map("count", $data_result_exist));
        if ($row_count == 0) {
            return json_encode('no record');
        } else {
            return json_encode('record found');
        }
    }


    public function syncData($php_fetch_bannerweb_api, $php_fetch_perso_api, $php_update_perso_api)
    {
        $sqlFetch = "SELECT noted_by FROM bpi_perso_file_deletion WHERE noted_by = 'Esperidion Castro';";
        $data_result = self::sqlQuery($sqlFetch, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $prepared_by = $row['noted_by'];
            $prepared_by_sign = self::fetchSignature($php_fetch_bannerweb_api, $prepared_by);

            $sqlstring = "UPDATE bpi_perso_file_deletion SET noted_by_sign = '{$prepared_by_sign}' WHERE noted_by = '{$prepared_by}' AND file_certified = true;";
            self::sqlQuery($sqlstring, $php_update_perso_api);
        }
    }
}
