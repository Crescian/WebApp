<?php
class PhdSurveillanceEvent
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_event_monitoring_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_event_monitoring_details WHERE prepared_by IS NOT NULL AND eventheader_id = ?) AS prepared_count 
                    FROM phd_event_monitoring_details WHERE eventheader_id = ? ";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute([$row['eventheaderid'], $row['eventheaderid']]);
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = $row['event_header'];
            $nestedData[] = $row['prepared_by1'];
            $nestedData[] = $row['prepared_by2'] == '' ? '-' : $row['prepared_by2'];
            $nestedData[] = $row['prepared_by3'] == '' ? '-' : $row['prepared_by3'];
            $nestedData[] = $row['noted_by'];
            $nestedData[] = array($row['eventheaderid'], $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadDataMonitoringBody($PHD)
    {
        $sqlstring = "SELECT * FROM phd_surveillance_name ORDER BY surveillanceid ASC";
        // $sqlstring = "SELECT * FROM phd_surveillance_name";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        // print_r($itemData_List);
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function generateDataSurveillanceRefno($PHD, $inField, $inTable)
    {
        $itemData_List = array();

        $fetchRefNo = "SELECT " . $inField . " FROM " . $inTable . "";
        $fetchRefNo_stmt = $PHD->prepare($fetchRefNo);
        $fetchRefNo_stmt->execute();
        $fetchRefNo_row = $fetchRefNo_stmt->fetch(PDO::FETCH_ASSOC);

        $currYear = date('y');
        $getYear =  substr($fetchRefNo_row[$inField], 5, 2);
        if ($currYear != $getYear) {
            $surveillance_ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($fetchRefNo_row[$inField], 0, 4);
            $counter = intval($currCount) + 1;
            $surveillance_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        $itemData_List['surveillance_ref_no'] = $surveillance_ref_no;
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataHeader($PHD, $BannerWebLive, $currentDate, $event_header, $event_ref_no, $prepared_by, $noted_by, $preparedBySignature, $notedBySignature)
    {
        $itemData_List = array();
        //* ======== Insert Header Details ========
        $sqlstring = "INSERT INTO phd_event_monitoring_header(event_header,date_created,prepared_by1,prepared_by1_sign,prepared_by1_date,noted_by,noted_by_sign,event_ref_no) 
                VALUES(?,?,?,?,?,?,?,?) RETURNING eventheaderid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$event_header, $currentDate, $prepared_by, $preparedBySignature, $currentDate, $noted_by, $notedBySignature, $event_ref_no]);
        //* ======== Notify Assigned Approver ========
        //TODO insert here for the notification of employee assigned for checking and noted..............
        //* ======== Update Event RefNo ========
        $updateRefno = "UPDATE phd_event_monitoring_refno SET event_ref_no = ?";
        $updateRefno_stmt = $PHD->prepare($updateRefno);
        $updateRefno_stmt->execute([$event_ref_no]);

        $itemData_List['eventheaderid'] = $PHD->lastInsertId();
        $lastInsertId = $PHD->lastInsertId();

        // * ======== Save Notification ========
        $sqlstringNotif = "INSERT INTO bpi_notification_module(prepared_by, table_name, table_database, table_field_id, table_field_id_name, app_id,prepared_by_date, remarks, noted_by,field1, field2, approved_by_acknowledge) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtNotif = $BannerWebLive->prepare($sqlstringNotif);
        $stmtNotif->execute([$prepared_by, 'phd_event_monitoring_header', 'physical_security', $lastInsertId, 'eventheaderid', 6, $currentDate, $event_header, $noted_by, 'event_ref_no', 'date_created', 'true']);
        $PHD = null; //* ======== Close Connection ========
        $BannerWebLive = null;

        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataDetails($PHD, $eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_min_days, $event_ref_no, $event_comments, $date_created, $prepared_by)
    {
        $sqlstring = "INSERT INTO phd_event_monitoring_details(eventheader_id,surveillance_name,event_time_start,event_time_end,event_date_from,event_date_to,event_total_days,event_min_days,event_ref_no,date_created,event_comments,prepared_by) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_min_days, $event_ref_no, $date_created, $event_comments, $prepared_by]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function previewDataEmployeeHeader($PHD, $eventheaderid)
    {
        $itemData_List = array();

        $sqlstring = "SELECT noted_by FROM phd_event_monitoring_header WHERE eventheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$eventheaderid]);
        $result_row = $result_stmt->fetch(PDO::FETCH_ASSOC);

        $itemData_List['noted_by'] = $result_row['noted_by'];
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function previewDataEventMonitoringTableBody($PHD, $eventheaderid)
    {
        $sqlstring = "SELECT eventheader_id,phd_event_monitoring_details.surveillance_name,TO_CHAR(event_time_start, 'HH24:MI:SS') AS event_time_start,TO_CHAR(event_time_end, 'HH24:MI:SS') AS event_time_end,event_date_from,event_date_to,
                event_total_days,event_min_days,event_comments,date_created,prepared_by FROM phd_event_monitoring_details
                INNER JOIN phd_surveillance_name ON phd_surveillance_name.surveillance_name = phd_event_monitoring_details.surveillance_name
                WHERE eventheader_id = ? ORDER BY surveillanceid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$eventheaderid]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
        $PHD = null; //* ======== Close Connection ========
    }
    public function updateDataEventHeader($PHD, $currentDate, $eventheaderid, $prepared_by, $noted_by, $preparedBySignature, $notedBySignature)
    {
        $chkSql = "SELECT prepared_by2,prepared_by3 FROM phd_event_monitoring_header WHERE eventheaderid = ?";
        $chkSql_stmt = $PHD->prepare($chkSql);
        $chkSql_stmt->execute([$eventheaderid]);
        $chkSql_row = $chkSql_stmt->fetch(PDO::FETCH_ASSOC);
        if ($chkSql_row['prepared_by2'] == '') {
            $prepared_field = 'prepared_by2 = ? , prepared_by2_sign = ?, prepared_by2_date = ?';
        } else {
            $prepared_field = 'prepared_by3 = ? , prepared_by3_sign = ?, prepared_by3_date = ?';
        }
        //* ======== Update Header ========
        $sqlstring = "UPDATE phd_event_monitoring_header SET " . $prepared_field . ", 
                noted_by = ?,noted_by_sign = ? WHERE eventheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$prepared_by, $preparedBySignature, $currentDate, $noted_by, $notedBySignature, $eventheaderid]);
        $PHD = null; //* ======== Close Connection ========
        echo 'save';
    }
    public function updateDataEventDetails($PHD, $eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_comments, $date_created, $prepared_by)
    {
        $sqlstring = "UPDATE phd_event_monitoring_details SET event_time_start = ?, event_time_end = ?, event_date_from = ?,
                    event_date_to = ?,event_total_days = ?,event_comments = ?,date_created = ?,prepared_by = ? 
                    WHERE eventheader_id = ? AND surveillance_name = ? AND prepared_by ISNULL";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_comments, $date_created, $prepared_by, $eventheader_id, $surveillance_name]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteDataEventMonitoring($PHD, $eventheaderid)
    {
        $sqlstring = "DELETE FROM phd_event_monitoring_header WHERE eventheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring)->execute([$eventheaderid]);
        $PHD = null; //* ======== Close Connection ========
    }
}
