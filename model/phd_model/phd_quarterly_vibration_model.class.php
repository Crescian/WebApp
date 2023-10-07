<?php
class PhdQuarterlyVibration
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
        $sqlstring = "SELECT * FROM phd_quarterly_vs_header;";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $qvsid = $row['qvsid'];
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_quarterly_vs_details WHERE qvs_activated_time IS NOT NULL AND qvs_id = '{$qvsid}') AS prepared_count 
                    FROM phd_quarterly_vs_details WHERE qvs_id = '{$qvsid}' ;";
            // $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            // $fetchCount_stmt->execute([$row['qvsid'], $row['qvsid']]);
            // $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);
            $data_result = self::sqlQuery($sqlstringCount, $php_fetch_phd_api);
            foreach ($data_result['data'] as $fetchCount_row) {
                $nestedData = array();
                $nestedData[] = date_format(date_create($row['qvs_date']), 'Y-m-d');
                $nestedData[] = $row['qvs_title'];
                $nestedData[] = $row['qvs_perform'];
                $nestedData[] = $row['qvs_checked'];
                $nestedData[] = $row['qvs_noted'];
                $nestedData[] = array($qvsid, $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
                $itemData_List['data'][] = $nestedData;
            }
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function generateData($PHD, $php_fetch_phd_api)
    {
        $sqlstring = "SELECT checklist_name,location_name,particular_name FROM phd_checklist_assign
                            INNER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_checklist_assign.phdlocation_id
                            INNER JOIN phd_particular_assign ON phd_particular_assign.phdlocation_id = phd_location.phdlocationid
                            INNER JOIN phd_particular ON phd_particular.phdparticularid = phd_particular_assign.phdparticular_id
                            WHERE checklist_name ILIKE '%Quarterly Vibration Checklist%' ORDER BY phdchklistassignid ASC;";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // foreach ($result_stmt->fetchAll() as $row) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
    }
    public function generateDataReference($PHD, $php_fetch_phd_api)
    {
        $sqlstring = "SELECT * FROM phd_quarterly_vs_refno;";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // $result_Res = $result_stmt->fetch(PDO::FETCH_ASSOC);
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $refno = $row['qvs_refno'];
        }

        $currYear = date('y');
        $getYear =  substr($refno, 5, 2);

        if ($currYear != $getYear) {
            $ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($refno, 0, 4);
            $counter = intval($currCount) + 1;
            $ref_noResult = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        echo $ref_noResult;
    }
    public function saveDataHeader($PHD, $BannerWebLive, $qvsTitle, $dateToday, $performedBy, $checkedBy, $notedBy, $refno, $result_Res, $result_check_Res, $result_noted_Res)
    {
        $sqlstring = "INSERT INTO phd_quarterly_vs_header(qvsrefno,qvs_title,qvs_date,qvs_perform,qvs_perform_sign,qvs_checked,qvs_checked_sign,qvs_noted,qvs_noted_sign)
                VALUES(?,?,?,?,?,?,?,?,?) RETURNING qvsid;";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$refno, $qvsTitle, $dateToday, $performedBy, $result_Res, $checkedBy, $result_check_Res, $notedBy, $result_noted_Res]);
        $phdqvsid = $PHD->lastInsertId();

        // * ======== Save Notification ========
        $sqlstringNotif = "INSERT INTO bpi_notification_module(prepared_by, table_name, table_database, table_field_id, table_field_id_name, app_id,prepared_by_date, remarks, checked_by, noted_by,field1, field2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmtNotif = $BannerWebLive->prepare($sqlstringNotif);
        $stmtNotif->execute([$performedBy, 'phd_quarterly_vs_header', 'physical_security', $phdqvsid, 'qvsid', 6, $dateToday, $qvsTitle, $checkedBy, $notedBy, 'qvsrefno', 'qvs_date']);
        $PHD = null; //* ======== Close Connection ========
        $BannerWebLive = null;
        echo $phdqvsid;
    }
    public function saveDataDetails($PHD, $php_insert_phd_api, $logged_user, $refno, $qvsid, $strLocationName, $strParticular, $strAction, $strTimeStampFormat)
    {
        $sqlstringdetail = "INSERT INTO phd_quarterly_vs_details(qvs_id,qvs_particular,qvs_action_code,qvs_action_time,qvs_refno,qvs_location_name,qvs_prepared_by)
            VALUES('{$qvsid}','{$strParticular}','{$strAction}','{$strTimeStampFormat}','{$refno}','{$strLocationName}','{$logged_user}');";
        // $result_stmt_detail = $PHD->prepare($sqlstringdetail);
        // $result_stmt_detail->execute([$qvsid, $strParticular, $strAction, $strTimeStampFormat, $refno, $strLocationName, $logged_user]);
        $data_result = self::sqlQuery($sqlstringdetail, $php_insert_phd_api);

        //* ========== Update Ref No ==========
        // $sqlstringUpRefno = "UPDATE phd_quarterly_vs_refno SET qvs_refno = ?";
        // $result_stmt_refno = $PHD->prepare($sqlstringUpRefno);
        // $result_stmt_refno->execute([$refno]);
    }
    public function deleteData($PHD, $php_update_phd_api, $id)
    {
        $sqlstring = "DELETE FROM phd_quarterly_vs_header WHERE qvsid = '{$id}';";
        $data_result = self::sqlQuery($sqlstring, $php_update_phd_api);
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute([$id]);
    }
    public function previewData($PHD, $php_fetch_phd_api, $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_quarterly_vs_details WHERE qvs_id = '" . $id . "'  ORDER BY qvsdetailid;";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // foreach ($result_stmt->fetchAll() as $row) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
    }
    public function updateData($PHD, $php_update_phd_api, $strQvsId, $strAction, $strTimeStampFormat, $refno, $strPrepared, $strActivate, $strVerify, $logged_user)
    {
        $sqlstring = "UPDATE phd_quarterly_vs_details SET ";
        if ($strPrepared != 'null' and $strVerify == 'null') {
            $sqlstring .= "qvs_activated_time = '" . $strActivate . "',qvs_verified_by = '" . $logged_user . "' WHERE qvsdetailid = '" . $strQvsId . "';";
            $data_result = self::sqlQuery($sqlstring, $php_update_phd_api);
            // $result_stmt = $PHD->prepare($sqlstring);
            // $result_stmt->execute();
        } else {
            $sqlstring .= "qvs_action_code = '" . $strAction . "',qvs_action_time = '" . $strTimeStampFormat . "',qvs_refno = '" . $refno . "',qvs_prepared_by = '" . $logged_user . "' WHERE qvsdetailid = '" . $strQvsId . "';";
            // $result_stmt = $PHD->prepare($sqlstring);
            // $result_stmt->execute();
            $data_result = self::sqlQuery($sqlstring, $php_update_phd_api);
        }
    }
    public function previewDataCheckedBy($PHD, $php_fetch_phd_api, $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_quarterly_vs_header WHERE qvsid = '" . $id . "';";
        // $result_stmt = $PHD->prepare($sqlstring);
        // $result_stmt->execute();
        // foreach ($result_stmt->fetchAll() as $row) {
        $data_result = self::sqlQuery($sqlstring, $php_fetch_phd_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List['result'] = $row['qvs_checked'];
        }
        return json_encode($itemData_List);
    }
}
