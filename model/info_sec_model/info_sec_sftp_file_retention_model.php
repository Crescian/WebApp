<?php
date_default_timezone_set('Asia/Manila');
class InfoSecSftpFileRetention
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

    public function loadSftpTableData($php_fetch_info_sec_api, $searchValue, $inCategory)
    {
        $itemData_List = array();
        $isDeleted = $inCategory == 'Received' ? 'false' : 'true';
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'sftp_file_received_date_time',
            1 => 'sftp_company_name',
            2 => 'sftp_filename',
            3 => 'sftp_filesize',
            4 => 'sftp_retention_count',
            5 => 'sftp_file_deleted_date_time'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT * FROM info_sec_sftp_file_retention WHERE sftp_deleted = '{$isDeleted}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_info_sec_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $file_received_compare = str_replace('\\', '\\\\', $searchValue);
            $sqlstring .= " AND (TO_CHAR(sftp_file_received_date_time, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR sftp_company_name ILIKE '%{$searchValue}%' OR sftp_filename ILIKE '%{$file_received_compare}%'
            OR sftp_filesize ILIKE '%{$searchValue}%' OR CAST(sftp_retention_count AS VARCHAR) ILIKE '%{$searchValue}%' OR TO_CHAR(sftp_file_deleted_date_time, 'YYYY-MM-DD') ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_info_sec_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_info_sec_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['sftp_file_received_date_time'] == '' ? '---- - -- - --' : date_format(date_create($row['sftp_file_received_date_time']), 'Y-m-d h:i:s A'),
                $row['sftp_company_name'],
                $row['sftp_filename'],
                $row['sftp_filesize'],
                $row['sftp_retention_count'] == '' ? '--' : $row['sftp_retention_count'],
                $row['sftp_file_deleted_date_time'] == '' ? '---- - -- - --' : date_format(date_create($row['sftp_file_deleted_date_time']), 'Y-m-d h:i:s A')
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

    public function loadReceivedDeletedCount($php_fetch_info_sec_api, $inCategory)
    {
        $isDeleted = $inCategory == 'Received' ? 'false' : 'true';
        $sqlstring = "SELECT COUNT(*) as total_count FROM info_sec_sftp_file_retention WHERE sftp_deleted = '{$isDeleted}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_info_sec_api);
        foreach ($data_result['data'] as $row) {
            return json_encode($row['total_count']);
        }
    }

    public function saveFileReceived($php_fetch_info_sec_api, $php_insert_info_sec_api, $company, $received_date, $received_time, $file_received, $file_size)
    {
        //* Check if Email already Exist
        if (str_contains($file_received, '\\')) {
            $file_received_compare = str_replace('\\', '\\\\', $file_received);
        } else {
            $file_received_compare = $file_received;
        }
        $chk_exist = "SELECT COUNT(*) as total_count FROM info_sec_sftp_file_retention WHERE sftp_company_name ILIKE '%{$company}%' AND sftp_filename ILIKE '%{$file_received_compare}%' AND sftp_filesize ILIKE '%{$file_size}%';";
        $data_result = self::sqlQuery($chk_exist, $php_fetch_info_sec_api);
        //* if not present in database, then save
        foreach ($data_result['data'] as $row) {
            if ($row['total_count'] == 0) {
                $sqlstring = "INSERT INTO info_sec_sftp_file_retention(sftp_company_name,sftp_filename,sftp_file_received_date_time,sftp_filesize) VALUES('{$company}','{$file_received}','" . $received_date . ' ' . $received_time . "','{$file_size}');";
                self::sqlQuery($sqlstring, $php_insert_info_sec_api);
            }
        }
    }

    public function updateFileReceivedDeleted($php_fetch_info_sec_api, $php_update_info_sec_api, $del_company, $deleted_date, $deleted_time, $del_file_received, $del_file_size)
    {
        if (str_contains($del_file_received, '\\')) {
            $del_file_received_compare = str_replace('\\', '\\\\', $del_file_received);
        } else {
            $del_file_received_compare = $del_file_received;
        }
        $sqlFetch = "SELECT sftp_retentionid,TO_CHAR(sftp_file_received_date_time,'YYYY-MM-DD') AS sftp_file_received_date_time FROM info_sec_sftp_file_retention WHERE sftp_company_name ILIKE '%{$del_company}%' AND sftp_filename ILIKE '%{$del_file_received_compare}%' AND sftp_filesize ILIKE '%{$del_file_size}%' AND sftp_deleted = false;";
        $data_result_fetch = self::sqlQuery($sqlFetch, $php_fetch_info_sec_api);
        foreach ($data_result_fetch['data'] as $row_fetch) {
            $dateReceived = date_create($row_fetch['sftp_file_received_date_time']);
            $currentDate = date_create($deleted_date);
            $diff = date_diff($dateReceived, $currentDate);

            $sqlstring = "UPDATE info_sec_sftp_file_retention SET sftp_file_deleted_date_time = '" . $deleted_date . ' ' . $deleted_time . "', sftp_retention_count = '{$diff->format('%a')}', sftp_deleted = true WHERE sftp_retentionid = '{$row_fetch['sftp_retentionid']}';";
            self::sqlQuery($sqlstring, $php_update_info_sec_api);
        }
    }

    public function savePersoFileDeletion($php_fetch_perso_api, $php_fetch_info_sec_api, $php_insert_perso_api, $filter_date)
    {
        //* Save File to Perso File Deletion Database for Generation of Client File Deletion
        $sqlFetch = "SELECT sftp_company_name,sftp_filename,sftp_filesize,sftp_file_received_date_time,sftp_file_deleted_date_time FROM info_sec_sftp_file_retention WHERE sftp_deleted = true AND TO_CHAR(sftp_file_received_date_time, 'YYYY-MM-DD') = '{$filter_date}';";
        $data_result = self::sqlQuery($sqlFetch, $php_fetch_info_sec_api);
        foreach ($data_result['data'] as $row) {
            $sftp_company_name = $row['sftp_company_name'];
            $sftp_filename = $row['sftp_filename'];
            $sftp_filesize = $row['sftp_filesize'];
            $data_received_date = date_format(date_create($row['sftp_file_received_date_time']), 'Y-m-d');
            $data_deleted_date = date_format(date_create($row['sftp_file_deleted_date_time']), 'Y-m-d');
            $delivery_date = self::CalculateBusinessDaysFromInputDate($php_fetch_perso_api, $data_received_date, '3');

            //* check if record is already inserted  before inserting
            $chkExist = "SELECT COUNT(*) as total_count FROM bpi_perso_file_deletion WHERE file_company ILIKE '%{$sftp_company_name}%' AND file_filename ILIKE '%{$sftp_filename}%' AND file_filesize ILIKE '%{$sftp_filesize}%';";
            $exist_result = self::sqlQuery($chkExist, $php_fetch_perso_api);
            //* save record if not already inserted
            foreach ($exist_result['data'] as $row_exist) {
                if ($row_exist['total_count'] == 0) {
                    $sqlFileDeletion = "INSERT INTO bpi_perso_file_deletion(file_company,file_filename,file_filesize,file_received_date,file_delivery_date,file_deleted_date) 
                    VALUES('{$sftp_company_name}','{$sftp_filename}','{$sftp_filesize}','{$data_received_date}','{$delivery_date}','{$data_deleted_date}');";
                    self::sqlQuery($sqlFileDeletion, $php_insert_perso_api);
                }
            }
        }
    }

    public function updateFileRetention($php_fetch_info_sec_api, $php_update_info_sec_api)
    {
        $sqlFetch = "SELECT sftp_retentionid,TO_CHAR(sftp_file_received_date_time,'YYYY-MM-DD') AS sftp_file_received_date_time FROM info_sec_sftp_file_retention WHERE sftp_deleted = false;";
        $data_result_fetch = self::sqlQuery($sqlFetch, $php_fetch_info_sec_api);
        foreach ($data_result_fetch['data'] as $row_fetch) {
            $dateReceived = date_create($row_fetch['sftp_file_received_date_time']);
            $currentDate = date_create(date('Y-m-d'));
            $diff = date_diff($dateReceived, $currentDate);
            //* Update retention Count
            $sqlstring = "UPDATE info_sec_sftp_file_retention SET sftp_retention_count = '{$diff->format('%a')}' WHERE sftp_retentionid = '{$row_fetch['sftp_retentionid']}';";
            self::sqlQuery($sqlstring, $php_update_info_sec_api);
        }
    }
}
