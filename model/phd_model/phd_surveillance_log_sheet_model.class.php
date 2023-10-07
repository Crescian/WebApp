<?php
class PhdSurveillanceLogShhet
{
    public function fetchDataTimeSync($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_time_sync_log_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['timesync_header'];
            $nestedData[] = $row['timesyncheaderid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataEventMonitoring($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_event_monitoring_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $fetchCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_event_monitoring_details WHERE prepared_by IS NOT NULL AND eventheader_id = :eventheaderid) AS prepared_count 
                    FROM phd_event_monitoring_details WHERE eventheader_id = :eventheaderid";
            $fetchCount_stmt = $PHD->prepare($fetchCount);
            $fetchCount_stmt->bindParam(':eventheaderid', $row['eventheaderid']);
            $fetchCount_stmt->execute();
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = $row['event_header'];
            $nestedData[] = $row['prepared_by1'];
            $nestedData[] = $row['prepared_by2'] == '' ? '-' : $row['prepared_by2'];
            $nestedData[] = $row['prepared_by3'] == '' ? '-' : $row['prepared_by3'];
            $nestedData[] = $row['noted_by'];
            $nestedData[] = array($fetchCount_row['total_count'], $fetchCount_row['prepared_count'], $row["eventheaderid"]);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function generateRefno($PHD, $inTable, $inField)
    {
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
    public function saveDataTimeSyncHeader($PHD, $timesync_header, $perform_by, $checked_by, $noted_by, $timesync_ref_no, $performBySignature_row, $checkedBySignature_row, $NotedBySignature_row, $date_created)
    {
        //* ======== Notify Assigned Approver ========
        //TODO insert here for the notification of employee assigned for checking and noted..............
        //* ======== Update Time Sync RefNo ========
        $updateRefno = "UPDATE phd_time_sync_log_refno SET timesyncrefno = :timesync_ref_no";
        $updateRefno_stmt = $PHD->prepare($updateRefno);
        $updateRefno_stmt->bindParam(':timesync_ref_no', $timesync_ref_no);
        $updateRefno_stmt->execute();
        //* ======== Insert Header Details ========
        $sqlstring = "INSERT INTO phd_time_sync_log_header(timesync_header,date_created,performed_by,performed_by_sign,checked_by,checked_by_sign,noted_by,noted_by_sign,timesync_ref_no) 
                VALUES(?,?,?,?,?,?,?,?,?) RETURNING timesyncheaderid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$timesync_header, $date_created, $perform_by, $performBySignature_row, $checked_by, $checkedBySignature_row, $noted_by, $NotedBySignature_row, $timesync_ref_no]);
        echo $timesyncheaderid = $PHD->lastInsertId();
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataTimeSyncDetails($PHD, $timesyncheader_id, $surveillance_name, $real_time, $actual_time, $time_gap, $remarks, $timesync_ref_no, $date_created)
    {
        $sqlstring = "INSERT INTO phd_time_sync_log_details(timesyncheader_id,surveillance_no,real_time,actual_time,time_gap,remarks,date_created,timesync_ref_no) 
                VALUES(?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$timesyncheader_id, $surveillance_name, $real_time, $actual_time, $time_gap, $remarks, $date_created, $timesync_ref_no]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteDataTimeSync($PHD, $timesyncheaderid)
    {
        $sqlstring = "DELETE FROM phd_time_sync_log_header WHERE timesyncheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring)->execute([$timesyncheaderid]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadTimeSynchronizationTable($PHD)
    {
        $sqlstring = "SELECT * FROM phd_surveillance_name ORDER BY surveillanceid ASC";
        // $sqlstring = "SELECT * FROM phd_surveillance_name";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        // $surveillanceCount = 0;
        // foreach ($result_stmt->fetchAll() as $row) {
        //     $surveillanceCount++;
        //     echo '<tr>';
        //     echo '<td style="width:25%;"><input type="text" name="surveillance_name[]" class="form-control fw-bold surveillance_name" value="' . $row['surveillance_name'] . '" disabled></td>';
        //     echo '<td><input type="time" step="1" name="surveillance_real_time[]" class="form-control fw-bold surveillance_real_time" id="surveillance_real_time' . $surveillanceCount . '" onchange="getTimeGap(' . $surveillanceCount . ');"></td>';
        //     echo '<td><input type="time" step="1" name="surveillance_actual_time[]" class="form-control fw-bold surveillance_actual_time" id="surveillance_actual_time' . $surveillanceCount . '" onchange="getTimeGap(' . $surveillanceCount . ');"></td>';
        //     echo '<td><input type="text" name="surveillance_time_gap[]" class="form-control fw-bold text-center surveillance_time_gap" id="surveillance_time_gap' . $surveillanceCount . '" disabled></td>';
        //     echo '<td><input type="text" name="surveillance_remarks[]" class="form-control fw-bold surveillance_remarks" placeholder="Remarks..."></td>';
        //     echo '</tr>';
        // }
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadEventMonitoringTableBody($PHD)
    {
        $sqlstring = "SELECT * FROM phd_surveillance_name ORDER BY surveillanceid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);

        // $eventCount = 0;
        // $rowCount = 0;
        // foreach ($result_stmt->fetchAll() as $row) {
        //     $eventCount++;
        //     echo '<tr>';
        //     echo '<td style="width:4%;text-align:center;vertical-align:middle;">' . $eventCount . '</td>';
        //     echo '<td style="display:none;"><input type="text" name="event_surveillance_name[]" class="form-control fw-bold event_surveillance_name" value="' . $row['surveillance_name'] . '" disabled></td>';
        //     echo '<td style="width:15%;vertical-align:middle;">' . $row['surveillance_name'] . '</td>';
        //     echo '<td style="width:10%;vertical-align:middle;"><input type="time" name="event_time_start[]" class="form-control fw-bold event_time_start" id="event_time_start' . $rowCount . '"></td>';
        //     echo '<td style="width:10%;vertical-align:middle;"><input type="time" name="event_time_end[]" class="form-control fw-bold event_time_end" id="event_time_end' . $rowCount . '"></td>';
        //     echo '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_from[]" class="form-control fw-bold event_date_from" id="event_date_from' . $rowCount . '" onchange="getEventDateDiff(' . $rowCount . ');"></td>';
        //     echo '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_to[]" class="form-control fw-bold event_date_to" id="event_date_to' . $rowCount . '" onchange="getEventDateDiff(' . $rowCount . ');"></td>';
        //     echo '<td style="width:10%;vertical-align:middle;"><input type="number" name="event_no_days[]" class="form-control fw-bold text-center event_no_days" id="event_no_days' . $rowCount . '"></td>';
        //     echo '<td style="display:none;"><input type="text" name="event_min_days[]" class="form-control fw-bold text-center event_min_days" value="90" disabled></td>';
        //     echo '<td style="width:6%;text-align:center;vertical-align:middle;">90</td>';
        //     echo '<td style="vertical-align:middle;"><input type="text" name="event_comments[]" class="form-control fw-bold text-center event_comments" id="event_comments' . $rowCount . '"></td>';
        //     echo '</tr>';
        //     $rowCount++;
        // }
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataEventHeader($PHD, $event_header, $event_ref_no, $prepared_by, $noted_by, $preparedBySignature, $notedBySignature, $date_created)
    {
        //* ======== Insert Header Details ========
        $sqlstring = "INSERT INTO phd_event_monitoring_header(event_header,date_created,prepared_by1,prepared_by1_sign,prepared_by1_date,noted_by,noted_by_sign,event_ref_no) 
                VALUES(?,?,?,?,?,?,?,?) RETURNING eventheaderid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$event_header, $date_created, $prepared_by, $preparedBySignature, $date_created, $noted_by, $notedBySignature, $event_ref_no]);
        //* ======== Notify Assigned Approver ========
        //TODO insert here for the notification of employee assigned for checking and noted..............
        //* ======== Update Event RefNo ========
        $updateRefno = "UPDATE phd_event_monitoring_refno SET event_ref_no = ?";
        $updateRefno_stmt = $PHD->prepare($updateRefno);
        $updateRefno_stmt->execute($event_ref_no);

        $itemData_List['eventheaderid'] = $PHD->lastInsertId();
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataEventDetails($PHD, $eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_min_days, $event_ref_no, $event_comments, $prepared_by, $date_created)
    {
        $sqlstring = "INSERT INTO phd_event_monitoring_details(eventheader_id,surveillance_name,event_time_start,event_time_end,event_date_from,event_date_to,event_total_days,event_min_days,event_ref_no,date_created,event_comments,prepared_by) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_min_days, $event_ref_no, $date_created, $event_comments, $prepared_by]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteDataEventMonitoring($PHD, $eventheaderid)
    {
        $sqlstring = "DELETE FROM phd_event_monitoring_header WHERE eventheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring)->execute([$eventheaderid]);
        $PHD = null; //* ======== Close Connection ========
    }
}
