<?php
class InfoSecServerAccess
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

    private function employeeSignature($php_fetch_bannerweb_api, $employeeName)
    {
        $sqlstring = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = '{$employeeName}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            return $row['employee_signature'];
        }
    }

    private function serverType($serverIp)
    {
        switch ($serverIp) {
            case '192.107.17.49':
                $serverName = '<span class="badge bg-dark col-sm-12">Banner New Database</span>';
                break;
            case '192.107.17.161':
                $serverName = '<span class="badge bg-info col-sm-12">Packaging Database</span>';
                break;
            case '192.107.17.220':
                $serverName = '<span class="badge bg-primary col-sm-12">Bannerdata Database</span>';
                break;
            case '192.107.16.41':
                $serverName = '<span class="badge bg-danger col-sm-12">Payroll Database</span>';
                break;
            case '192.107.16.248':
                $serverName = '<span class="badge bg-warning col-sm-12">CMS/Canteen Database</span>';
                break;
        }
        return $serverName;
    }

    public function loadServerAccessData($php_fetch_info_sec_api, $searchValue, $access_status)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'server_date_added',
            1 => 'server_ip_address',
            2 => 'server_user_ip_address',
            3 => 'server_user_mac_address',
            4 => 'server_user_name',
            5 => 'server_user_location',
            6 => 'server_user_purpose',
            7 => 'server_date_requested'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT * FROM info_sec_server_access_revoke_request WHERE 1 = 1 AND server_access_status = '{$access_status}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_info_sec_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (TO_CHAR(server_date_added, 'YYYY-MM-DD') ILIKE '%{$searchValue}%' OR server_ip_address ILIKE '%{$searchValue}%' OR server_user_ip_address ILIKE '%{$searchValue}%'
            OR server_user_mac_address ILIKE '%{$searchValue}%' OR server_user_name ILIKE '%{$searchValue}%' OR server_user_location ILIKE '%{$searchValue}%' OR server_user_purpose ILIKE '%{$searchValue}%'
            OR TO_CHAR(server_date_requested, 'YYYY-MM-DD') ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_info_sec_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_info_sec_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['server_date_added'] == '' ? '---- - -- - --' : date_format(date_create($row['server_date_added']), 'Y-m-d'),
                self::serverType($row['server_ip_address']),
                $row['server_user_ip_address'],
                $row['server_user_mac_address'] == '' ? '--:--:--:--:--:--' : $row['server_user_mac_address'],
                $row['server_user_name'],
                $row['server_user_location'],
                $row['server_user_purpose'],
                date_format(date_create($row['server_date_requested']), 'Y-m-d'),
                [$row['serveraccessid'], $row['approved_by_acknowledge'], $row['noted_by_acknowledge'], $row['received_by'], $row['received_by_acknowledge']]
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

    public function saveReceiveRequest($php_update_info_sec_api, $php_fetch_bannerweb_api, $serveraccessid, $receive_by, $date_received)
    {
        $received_by_sign = self::employeeSignature($php_fetch_bannerweb_api, $receive_by);
        $sqlstring = "UPDATE info_sec_server_access_revoke_request SET received_by = '{$receive_by}', received_by_sign = '{$received_by_sign}', received_by_acknowledge = 'true', received_by_date = '{$date_received}'
            WHERE serveraccessid = '{$serveraccessid}'";
        self::sqlQuery($sqlstring, $php_update_info_sec_api);
    }
}
