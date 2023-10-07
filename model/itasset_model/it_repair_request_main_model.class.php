<?php
date_default_timezone_set('Asia/Manila');
class ITRepairMainRequest
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

    public function loadRepairTable($php_fetch_it_repair_api,  $statusVal, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'queue_number',
            1 => 'item',
            2 => 'remarks',
            3 => 'location',
            4 => 'prepared_by',
            5 => 'date_requested',
            6 => 'datetime_repair',
            7 => 'datetime_accomplish'
        );
        //* =========== Fetch Total Record Data ===========
        $sqlstring = "SELECT * FROM tblit_repair WHERE status = '{$statusVal}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        // //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (queue_number ILIKE '%{$searchValue}%' OR item ILIKE '%{$searchValue}%' OR remarks ILIKE '%{$searchValue}%' 
                OR location ILIKE '%{$searchValue}%' OR prepared_by ILIKE '%{$searchValue}%' OR TO_CHAR(date_requested, 'YYYY-MM-DD HH24:MI:Ss' ) ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        // //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        // * ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['queue_number'],
                $row['item'],
                $row['remarks'],
                $row['location'],
                $row['prepared_by'],
                $row['date_requested'] == null ? '--:--:-- --:--' : date_format(date_create($row['date_requested']), 'Y-m-d h:i:s A'),
                $row['datetime_repair'] == null ? '--:--:-- --:--' : date_format(date_create($row['datetime_repair']), 'Y-m-d h:i:s A'),
                $row['datetime_accomplish'] == null ? '--:--:-- --:--' : date_format(date_create($row['datetime_accomplish']), 'Y-m-d h:i:s A'),
                [
                    "status" => $row['status'],
                    "sender" => $row['queue_number'],
                    "requested" => $row['prepared_by'],
                    "id" => $row['repair_id']
                ]
            );
        }
        // //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
    }

    public function loadRepairCount($php_fetch_it_repair_api)
    {
        $sqlstring = "SELECT status, COUNT(*) AS count FROM tblit_repair GROUP BY status;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        $row_count = array_sum(array_map("count", $data_result));
        if ($row_count > 0) foreach ($data_result['data'] as $row) $itemData_List[$row['status']] = sprintf("%03d", $row['count']);
        $itemData_List ??= [null];
        return json_encode($itemData_List);
    }

    public function loadDetails($php_fetch_it_repair_api,  $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM tblit_repair WHERE repair_id = '{$id}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List['queue_number'] = $row['queue_number'];
            $itemData_List['area'] = $row['area'];
            $itemData_List['location'] = $row['location'];
            $itemData_List['ip_address'] = $row['ip_address'];
            $itemData_List['item'] = $row['item'];
            $itemData_List['prepared_by'] = $row['prepared_by'];
            $itemData_List['repaired_by'] = $row['repaired_by'];
            $itemData_List['date_requested'] = $row['date_requested'];
            $itemData_List['remarks'] = $row['remarks'];
            $itemData_List['action_taken'] = $row['action_taken'];
            $itemData_List['prepared_by_date'] = $row['prepared_by_date'];
            $itemData_List['datetime_repair'] = $row['datetime_repair'];
            $itemData_List['datetime_accomplish'] = $row['datetime_accomplish'];
        }
        return json_encode($itemData_List);
    }

    public function saveAcknowledge($php_update_it_repair_api,  $id, $priority)
    {
        $sqlstring = "UPDATE tblit_repair SET status = 'Pending', priority = '{$priority}' WHERE repair_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }

    public function saveCancel($php_update_it_repair_api,  $id)
    {
        $sqlstring = "UPDATE tblit_repair SET status = 'Cancelled' WHERE repair_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }

    public function saveRepair($php_update_it_repair_api,  $id)
    {
        $sqlstring = "UPDATE tblit_repair SET status = 'Ongoing', datetime_repair = LOCALTIMESTAMP(0) WHERE repair_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }

    public function saveAccomplish($php_fetch_bannerweb_api, $php_update_it_repair_api, $php_insert_it_repair_api, $actionTaken, $id, $sender, $logged_name, $date_created)
    {
        $approved_sign = self::fetchSignature('Oliver Razalan', $php_fetch_bannerweb_api);
        $sqlstring = "UPDATE tblit_repair SET repaired_by = '{$logged_name}', action_taken = '{$actionTaken}',approved_by = 'Oliver Razalan', approved_by_sign = '{$approved_sign}', approved_by_date = '{$date_created}' WHERE repair_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);

        $sqlstring = "INSERT INTO tblit_action_taken(repair_id,action_taken,action_taken_date)VALUES('{$id}', '{$actionTaken}', '{$date_created}');";
        self::sqlQuery($sqlstring, $php_insert_it_repair_api);

        $sql_convo = "DELETE FROM tblit_conversation WHERE sender = '{$sender}';";
        self::sqlQuery($sql_convo, $php_update_it_repair_api);
    }
    public function getDoneStatus($php_fetch_it_repair_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM tblit_repair WHERE status = 'For Received' AND duration is NULL ORDER BY repair_id ASC;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $itemData_List[] = $row;
            }
        }
        return json_encode($itemData_List);
    }
    public function setDuration($php_update_it_repair_api,  $id, $totalMinutes)
    {
        $sqlstring = "UPDATE tblit_repair SET duration = '{$totalMinutes}' WHERE repair_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }
    public function loadDept($php_fetch_bannerweb_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT (emp_fn || ' ' || emp_sn) AS fullname FROM prl_employee WHERE dept_code = 'ITD';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                // foreach ($rowData as $row) {
                $itemData_List[$row['fullname']] = $row['fullname'];
            }
        }
        return json_encode($itemData_List);
    }
    public function proceedAccomplish($php_fetch_bannerweb_api, $php_fetch_it_repair_api, $php_update_it_repair_api, $php_update_bannerweb_api, $php_insert_bannerweb_api, $id, $logged_name, $date_created)
    {
        $acknowledge_sign = self::fetchSignature($logged_name, $php_fetch_bannerweb_api);
        $sqlstring_fetch_details = "SELECT * FROM tblit_repair WHERE repair_id = '{$id}';";
        $data_result = self::sqlQuery($sqlstring_fetch_details, $php_fetch_it_repair_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $date = date_format(date_create($row['date_requested']), 'Y-m-d');
                $purpose = $row['remarks'];
                $prepared_by = $row['prepared_by'];
            }
        }
        $sqlstringNotif = "INSERT INTO bpi_notification_module(table_name, table_database, table_field_id, table_field_id_name, app_id, prepared_by,prepared_by_date, repair_by, field1, field2, field3, field4, field5, remarks) 
        VALUES ('tblit_repair', 'it_repair_request', '{$id}', 'repair_id', 8, '{$prepared_by}', '{$date}', '{$logged_name}', 'queue_number', 'item', 'area', 'location', 'remarks', '{$purpose}');";
        $data_result = self::sqlQuery($sqlstringNotif, $php_insert_bannerweb_api);

        $sqlstringUp = "UPDATE bpi_notification_module SET repair_by_acknowledge = true WHERE table_field_id = '{$id}'";
        $data_result = self::sqlQuery($sqlstringUp, $php_update_bannerweb_api);

        $sqlstring = "UPDATE tblit_repair SET status = 'For Received', datetime_accomplish = LOCALTIMESTAMP(0), repaired_by_acknowledge = 'true', repaired_by_sign = '{$acknowledge_sign}', repaired_by_date = '{$date_created}' WHERE repair_id = '{$id}';";
        $data_result = self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }
}
