<?php
date_default_timezone_set('Asia/Manila');
class ITRepairMain
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
        $BannerWebLive = null; //* ======== Close Connection ========
    }

    public function loadTableRequestData($php_fetch_it_repair_api, $statusVal, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'queue_number',
            1 => 'prepared_by',
            2 => 'prepared_by_date',
            3 => 'request_type',
            4 => 'item',
            5 => 'description',
            6 => 'purpose',
        );
        //* =========== Fetch Total Record Data ===========
        $sqlstring = "SELECT * FROM tblit_request WHERE status = '{$statusVal}'";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (queue_number ILIKE '%{$searchValue}%' OR prepared_by ILIKE '%{$searchValue}%'
                OR request_type ILIKE '%{$searchValue}%' OR item ILIKE '%{$searchValue}%' OR description ILIKE '%{$searchValue}%' 
                OR purpose ILIKE '%{$searchValue}%' OR TO_CHAR(prepared_by_date, 'YYYY-MM-DD HH24:MI:Ss' ) ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        // //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['queue_number'],
                $row['prepared_by'],
                $row['prepared_by_date'],
                $row['request_type'],
                $row['item'],
                $row['description'],
                $row['purpose'],
                [
                    "status" => $row['status'],
                    "noted_by_acknowledge" => $row['noted_by_acknowledge'],
                    "sender" => $row['queue_number'],
                    "repaired_by" => $row['repaired_by'],
                    "id" => $row['request_id'],
                    "request" => $row['request_type']
                ]
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

    public function loadRequestCount($php_fetch_it_repair_api)
    {
        $sqlstring = "SELECT status, COUNT(*) AS count FROM tblit_request GROUP BY status;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
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
    public function loadRequestDetails($php_fetch_it_repair_api, $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM tblit_request WHERE request_id = '{$id}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        foreach ($data_result['data'] as $rowData) {
            $itemData_List['queue_number'] = $rowData['queue_number'];
            $itemData_List['item'] = $rowData['item'];
            $itemData_List['request_type'] = $rowData['request_type'];
            $itemData_List['description'] = $rowData['description'];
            $itemData_List['purpose'] = $rowData['purpose'];
            $itemData_List['prepared_by'] = $rowData['prepared_by'];
            $itemData_List['approved_by'] = $rowData['approved_by'];
            $itemData_List['noted_by'] = $rowData['noted_by'];
            $itemData_List['prepared_by_date'] = $rowData['prepared_by_date'];
            $itemData_List['date_needed'] = $rowData['date_needed'];
            $itemData_List['request_id'] = $rowData['request_id'];
            $itemData_List['repaired_by_date'] = $rowData['repaired_by_date'];
            $itemData_List['status'] = $rowData['status'];
        }
        return json_encode($itemData_List);
    }

    public function loadRequestCancel($php_update_it_repair_api, $id)
    {
        $sqlstring = "UPDATE tblit_request SET status = 'Cancelled' WHERE request_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }

    public function loadRequestReApprove($php_update_it_repair_api, $php_update_bannerweb_api, $id)
    {
        $sqlstring = "UPDATE tblit_request SET status = 'Pending' WHERE request_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);

        $sqlstringUp = "UPDATE bpi_notification_module SET cancel_status = false WHERE table_field_id = '{$id}';";
        self::sqlQuery($sqlstringUp, $php_update_bannerweb_api);
    }

    public function loadRequestProcess($php_fetch_bannerweb_api, $php_update_it_repair_api,  $logged_user, $id)
    {
        $technician_assign = self::fetchSignature($logged_user, $php_fetch_bannerweb_api);
        $sqlstring = "UPDATE tblit_request SET remarks = 'process waiting to purchasing and deliver the item for receive',status = 'Ongoing',repaired_by = '{$logged_user}',repaired_by_acknowledge = 'true', repaired_by_sign = '{$technician_assign}' WHERE request_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }

    public function loadRequestAccomplish($php_update_it_repair_api, $php_update_bannerweb_api, $id, $sender, $request, $logged_user)
    {
        $date = date("Y-m-d");
        if ($request == 'Hardware') {
            $remarks = ',remarks = null';
        }
        $sqlstring = "UPDATE tblit_request SET status = 'For Received', repaired_by_date = '{$date}'$remarks WHERE request_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);

        $sqlstringUp = "UPDATE bpi_notification_module SET repair_by = '{$logged_user}',repair_by_acknowledge = true WHERE table_field_id = '{$id}';";
        self::sqlQuery($sqlstringUp, $php_update_bannerweb_api);

        $sql_convo = "DELETE FROM tblit_conversation WHERE sender = '{$sender}';";
        self::sqlQuery($sql_convo, $php_update_it_repair_api);
    }
    public function update_approval_details($php_update_it_repair_api,  $request_id, $date_requested, $date_needed, $request_type, $item, $description, $purpose)
    {
        $sqlstring = "UPDATE tblit_request SET prepared_by_date = '{$date_requested}', date_needed = '{$date_needed}',request_type = '{$request_type}',item = '{$item}',description = '{$description}',purpose = '{$purpose}' WHERE request_id = '{$request_id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);
    }
    public function accomplishWithReason($php_update_it_repair_api, $php_update_bannerweb_api, $id, $reasonRemarks, $logged_user)
    {
        $date = date("Y-m-d");
        $sqlstring = "UPDATE tblit_request SET status = 'For Received', repaired_by_date = '{$date}', remarks = '{$reasonRemarks}' WHERE request_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);

        $sqlstringUp = "UPDATE bpi_notification_module SET repair_by_acknowledge = true, repair_by = '{$logged_user}' WHERE table_field_id = '{$id}';";
        self::sqlQuery($sqlstringUp, $php_update_bannerweb_api);
    }
    public function cancelhWithReason($php_update_it_repair_api, $php_update_bannerweb_api, $id, $reasonCancelRemarks, $logged_user)
    {
        $sqlstring = "UPDATE tblit_request SET status = 'Cancelled', remarks = '{$reasonCancelRemarks}' WHERE request_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_it_repair_api);

        $sqlstringUp = "UPDATE bpi_notification_module SET cancel_status = true WHERE table_field_id = '{$id}';";
        self::sqlQuery($sqlstringUp, $php_update_bannerweb_api);
    }
    public function toastNotification($php_fetch_it_repair_api)
    {
        $data = array();
        $sqlstring = "SELECT * FROM tblit_request WHERE status = 'Cancelled' ORDER BY request_id desc LIMIT 1;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        foreach ($data_result['data'] as $row) {
            $data['request_id'] = $row['request_id'];
            $data['item'] = $row['item'];
            $data['status'] = $row['status'];
            $data['prepared_by'] = $row['prepared_by'];
        }
        return json_encode($data);
    }
}
