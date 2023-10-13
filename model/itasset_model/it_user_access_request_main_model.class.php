<?php
date_default_timezone_set('Asia/Manila');
class ITUserAccessMain
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
    public function loadTableRequestData($php_fetch_itasset_api, $statusVal, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'control_no',
            1 => 'prepared_by',
            2 => 'date_request',
            3 => 'purpose'
        );
        //* =========== Fetch Total Record Data ===========
        $sqlstring = "SELECT * FROM tblit_user_access_request WHERE status = '{$statusVal}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (control_no ILIKE '%{$searchValue}%' OR prepared_by ILIKE '%{$searchValue}%'
                OR access ILIKE '%{$searchValue}%' OR priority ILIKE '%{$searchValue}%' 
                OR purpose ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        // //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                'UAF-' . $row['control_no'],
                $row['prepared_by'],
                $row['date_request'],
                $row['mail_account'] . '.<br> ' . $row['file_storage_access'] . '.<br> ' . $row['in_house_access'],
                $row['purpose'],
                [
                    "id" => $row['useraccessid'],
                    "noted_by_acknowledge" => $row['noted_by_acknowledge'],
                    "control_no" => $row['control_no'],
                    "status" => $row['status']
                ]
                // Include it in the same array
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
    public function loadRequestCount($php_fetch_itasset_api)
    {
        $sqlstring = "SELECT status, COUNT(*) AS count FROM tblit_user_access_request GROUP BY status;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $data[$row['status']] = sprintf("%03d", $row['count']);
            }
        }
        $data ??= [null];
        return json_encode($data);
    }
    public function acknowledgeRequest($php_update_itasset_api, $data)
    {
        $sqlstring = "UPDATE tblit_user_access_request SET status = 'Process' WHERE useraccessid = '{$data}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);
    }
    public function cancelRequest($php_update_itasset_api, $data)
    {
        $sqlstring = "UPDATE tblit_user_access_request SET status = 'Cancelled' WHERE useraccessid = '{$data}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);
    }
    public function reapproveRequest($php_update_itasset_api, $data)
    {
        $sqlstring = "UPDATE tblit_user_access_request SET status = 'Pending' WHERE useraccessid = '{$data}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);
    }
    public function accomplishRequest($php_update_itasset_api, $php_update_bannerweb_api, $logged_user, $date_created, $data)
    {
        $sqlstring = "UPDATE tblit_user_access_request SET status = 'Received',date_accomplished = '{$date_created}' WHERE useraccessid = '{$data}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);

        $sqlstring = "UPDATE bpi_notification_module SET repair_by = '{$logged_user}', repair_by_acknowledge = 'true', repair_by_date = '{$date_created}' WHERE table_field_id = '{$data}';";
        self::sqlQuery($sqlstring, $php_update_bannerweb_api);
    }
    public function detailsRequest($php_fetch_itasset_api, $data)
    {
        $itemData_List = array();
        $sqlstrin = "SELECT * FROM tblit_user_access_request WHERE useraccessid = '{$data}';";
        $data_result = self::sqlQuery($sqlstrin, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            // $itemData_List[''] = $row['control_no'];
            $itemData_List[] =
                [
                    'control_no' => $row['control_no'],
                    'access' => $row['access'],
                    'priority' => $row['priority'],
                    'purpose' => $row['purpose'],
                    'mail_account' => $row['mail_account'],
                    'file_storage_access' => $row['file_storage_access'],
                    'in_house_access' => $row['in_house_access'],
                    'domain_account' => $row['domain_account'],
                    'date_request' => $row['date_request'],
                    'date_need' => $row['date_need'],
                    'prepared_by' => $row['prepared_by'],
                    'approved_by' => $row['approved_by'],
                    'noted_by' => $row['noted_by'],
                    'date_accomplished' => $row['date_accomplished']
                ];
        }
        return json_encode($itemData_List);
    }
    public function update_approval_details($php_update_itasset_api, $access, $priority, $purpose, $mail_account, $file_storage_access, $in_house_access, $domain, $prepared_by_date, $date_needed, $request_id)
    {
        $sqlstring = "UPDATE tblit_user_access_request SET access = '{$access}', priority = '{$priority}',purpose = '{$purpose}',mail_account = '{$mail_account}',file_storage_access = '{$file_storage_access}',in_house_access = '{$in_house_access}',domain_account = '{$domain}',date_request = '{$prepared_by_date}',date_need = '{$date_needed}' WHERE useraccessid = '{$request_id}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);
    }
}
