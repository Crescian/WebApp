<?php
class PhdTimeSynchronization
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
    public function fetchData($PHD, $php_fetch_phd_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_time_sync_log_header;";
        // $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        // foreach ($data_result['data'] as $row) {
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_time_sync_log_details WHERE remarks <> '' AND timesyncheader_id = '{$row['timesyncheaderid']}') AS prepared_count 
                    FROM phd_time_sync_log_details WHERE timesyncheader_id = '{$row['timesyncheaderid']}';";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute();
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            // $data_result = self::sqlQuery($sqlstringCount, $php_fetch_phd_api);
            // foreach ($data_result['data'] as $rowDetails) {
            $totalCount = $fetchCount_row['total_count'];
            $preparedCount = $fetchCount_row['prepared_count'];
            // }
            $nestedData = array();
            $nestedData[] = $row['timesync_header'];
            $nestedData[] = array($row['timesyncheaderid'], $totalCount, $preparedCount);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }

    public function loadDataTimeSynchronizationTableBody($PHD, $php_fetch_phd_api)
    {
        $surveillance = array();
        // $sqlstring = "SELECT * FROM phd_surveillance_name ORDER BY surveillanceid ASC";
        $sqlstring = "SELECT * FROM phd_surveillance_name;";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // foreach ($result_stmt->fetchAll() as $row) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $surveillance[] = $row;
        }
        return json_encode($surveillance);
        $PHD = null; //* ======== Close Connection ========
    }

    public function generateDataSurveillanceRefNo($PHD, $php_fetch_phd_api, $inField, $inTable)
    {
        $currYear = date('y');
        $fetchRefNo = "SELECT " . $inField . " FROM " . $inTable . ";";
        // $fetchRefNo_stmt = $PHD->prepare($fetchRefNo);
        // $fetchRefNo_stmt->execute();
        // $fetchRefNo_row = $fetchRefNo_stmt->fetch(PDO::FETCH_ASSOC);

        $data_result = self::sqlQuery($fetchRefNo, $php_fetch_phd_api);
        foreach ($data_result['data'] as $fetchRefNo_row) {
            $getYear =  substr($fetchRefNo_row[$inField], 5, 2);
            if ($currYear != $getYear) {
                $surveillance_ref_no = '0001-' . $currYear;
            } else {
                $currCount = substr($fetchRefNo_row[$inField], 0, 4);
                $counter = intval($currCount) + 1;
                $surveillance_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
            }
        }
        return $surveillance_ref_no;
        $PHD = null; //* ======== Close Connection ========
    }

    public function saveDataHeader($PHD, $php_update_phd_api, $php_insert_phd_api, $BannerWebLive, $timesync_header, $date_created, $timesync_ref_no, $perform_by, $checked_by, $noted_by, $performBySignature_row, $checkedBySignature_row, $NotedBySignature_row, $currentDate)
    { //* ======== Update Time Sync RefNo ========
        $updateRefno = "UPDATE phd_time_sync_log_refno SET timesyncrefno = '{$timesync_ref_no}';";
        // $data_result = self::sqlQuery($updateRefno, $php_update_phd_api);
        $updateRefno_stmt = $PHD->prepare($updateRefno);
        $updateRefno_stmt->execute();

        //* ======== Insert Header Details ========
        $sqlstring = "INSERT INTO phd_time_sync_log_header(timesync_header,date_created,prepared_by,prepared_by_sign,checked_by,checked_by_sign,noted_by,noted_by_sign,timesync_ref_no) 
                VALUES('{$timesync_header}','{$date_created}','{$perform_by}','{$performBySignature_row}','{$checked_by}','{$checkedBySignature_row}','{$noted_by}','{$NotedBySignature_row}','{$timesync_ref_no}') RETURNING timesyncheaderid;";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        // $result_stmt->execute([$timesync_header, $date_created, $perform_by, $performBySignature_row, $checked_by, $checkedBySignature_row, $noted_by, $NotedBySignature_row, $timesync_ref_no]);
        echo $timesyncheaderid = $PHD->lastInsertId();

        // * ======== Save Notification ========
        $sqlstringNotif = "INSERT INTO bpi_notification_module(prepared_by, table_name, table_database, table_field_id, table_field_id_name, app_id,prepared_by_date, remarks, checked_by, noted_by,field1, field2) VALUES ('{$perform_by}', 'phd_time_sync_log_header', 'physical_security', '{$timesyncheaderid}', 'timesyncheaderid', 6, '$currentDate', '$timesync_header', '$checked_by', '$noted_by', 'timesync_ref_no', 'date_created');";
        $stmtNotif = $BannerWebLive->prepare($sqlstringNotif);
        $stmtNotif->execute();
        // $stmtNotif->execute([$perform_by, 'phd_time_sync_log_header', 'physical_security', $timesyncheaderid, 'timesyncheaderid', 6, $currentDate, $timesync_header, $checked_by, $noted_by, 'timesync_ref_no', 'date_created']);
        $PHD = null; //* ======== Close Connection ========
        $BannerWebLive = null;
    }

    public function saveDataDetails($PHD, $logged_user, $timesyncheader_id, $surveillance_name, $real_time, $actual_time, $time_gap, $remarks, $date_created, $timesync_ref_no)
    {
        if (strlen($real_time) > 11) {
        } else {
            $real_time = null;
            $actual_time = null;
            $timesync_ref_no = null;
            $date_created = null;
            $logged_user = null;
            $remarks = null;
        }
        $sqlstring = "INSERT INTO phd_time_sync_log_details(timesyncheader_id,surveillance_no,real_time,actual_time,time_gap,remarks,date_created,timesync_ref_no,prepared_by)
                VALUES(?,?,?,?,?,?,?,?,?);";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$timesyncheader_id, $surveillance_name, $real_time, $actual_time, $time_gap, $remarks, $date_created, $timesync_ref_no, $logged_user]);
        $PHD = null; //* ======== Close Connection ========
    }

    public function deleteData($PHD, $php_update_phd_api, $timesyncheaderid)
    {
        $sqlstring = "DELETE FROM phd_time_sync_log_header WHERE timesyncheaderid = '{$timesyncheaderid}';";
        $data_result = self::sqlQuery($sqlstring, $php_update_phd_api);
        // $result_stmt = $PHD->prepare($sqlstring)->execute([$timesyncheaderid]);
        $PHD = null; //* ======== Close Connection ========
    }

    public function previewData($PHD, $php_fetch_phd_api, $timesyncheaderid)
    {
        $surveillance = array();
        $sqlstring = "SELECT * FROM phd_time_sync_log_details WHERE timesyncheader_id = '{$timesyncheaderid}' ORDER BY timesyncdetailsid;";
        // $sqlstring = "SELECT * FROM phd_time_sync_log_details WHERE timesyncheader_id = ?";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute([$timesyncheaderid]);
        // foreach ($result_stmt->fetchAll() as $row) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $surveillance[] = $row;
        }
        return json_encode($surveillance);
        $PHD = null; //* ======== Close Connection ========
    }

    public function updateData($PHD, $php_update_phd_api, $logged_user, $prepare, $time_id, $real_time, $actual_time, $time_gap, $remarks, $date_created, $timesync_ref_no)
    {
        if ($prepare == 'null') {
            if ($remarks == '') {
                $date_created = null;
            }
            $sqlstring = "UPDATE phd_time_sync_log_details SET real_time='" . $real_time . "', actual_time = '" . $actual_time . "',
                            time_gap = '" . $time_gap . "',remarks = '" . $remarks . "',date_created = '" . $date_created . "',
                            timesync_ref_no = '" . $timesync_ref_no . "',prepared_by = '" . $logged_user . "' WHERE timesyncdetailsid = '" . $time_id . "';";
            // $result_stmt = $PHD->prepare($sqlstring);
            // $result_stmt->execute();
            $data_result = self::sqlQuery($sqlstring, $php_update_phd_api);
        } else {
            $sqlstring = "UPDATE phd_time_sync_log_details SET remarks = '" . $remarks . "' WHERE timesyncdetailsid = '" . $time_id . "';";
            // $result_stmt = $PHD->prepare($sqlstring);
            // $result_stmt->execute();
            $data_result = self::sqlQuery($sqlstring, $php_update_phd_api);
            echo $real_time . '/' . $actual_time;
        }
    }

    public function loadDataBy($PHD, $php_fetch_phd_api, $timesyncheaderid)
    {
        $surveillance = array();
        $sqlstring = "SELECT * FROM phd_time_sync_log_header WHERE timesyncheaderid = '" . $timesyncheaderid . "';";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // $result_res = $result_stmt->fetchAll();
        // foreach ($result_res as $row) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $surveillance['result'] = $row['noted_by'];
        }
        return json_encode($surveillance);
    }

    public function previewDataCheckedBy($PHD, $php_fetch_phd_api, $timesyncheaderid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_time_sync_log_header WHERE timesyncheaderid = '" . $timesyncheaderid . "';";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // foreach ($result_stmt->fetchAll() as $row) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List['result'] = $row['checked_by'];
        }
        return json_encode($itemData_List);
    }
}
