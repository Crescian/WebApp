<?php
class PhdMonthlyPirAlarm
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_pir_alarm_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_monthly_pir_alarm_details WHERE performed IS NOT NULL AND pir_id = ?) AS prepared_count 
                    FROM phd_monthly_pir_alarm_details WHERE pir_id = ? ";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute([$row['pirid'], $row['pirid']]);
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = date_format(date_create($row['pir_date']), 'Y-m-d');
            $nestedData[] = $row['pir_title'];
            $nestedData[] = $row['performed1'] == '' ? '-' : $row['performed1'];
            $nestedData[] = $row['checked_by'] == '' ? '-' : $row['checked_by'];
            $nestedData[] = $row['noted_by'] == '' ? '-' : $row['noted_by'];
            $nestedData[] = array($row['pirid'], $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadDataCms($BannerWebLive)
    {
        $sqlstring = "SELECT empno,CONCAT(emp_fn,' ',emp_sn) AS fullname FROM prl_employee WHERE dept_code = 'PHD' ORDER BY fullname ASC";
        $result_stmt = $BannerWebLive->prepare($sqlstring);
        $result_stmt->execute();
        $result_res = $result_stmt->fetchAll();
        echo '<option value ="">Choose...</option>';
        foreach ($result_res as $row) {
            echo '<option value = "' . $row['fullname'] . '">' . $row['fullname']  . '</option>';
        }
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function generateData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT checklist_name,location_name FROM phd_checklist_assign 
                            INNER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_checklist_assign.phdlocation_id
                            WHERE checklist_name = 'Monthly PIR Alarm Checklist' ORDER BY phdchklistassignid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        // print_r($itemData_List);
        return json_encode($itemData_List);
    }
    public function saveDataHeader($PHD, $title, $prepared_by, $checkedBy, $notedBy, $preparedBySignature, $checkedBySignature, $notedBySignature, $date_created)
    {   
        // * GENERATE REFERRENCE NUMBER *//
        $sqlstringRefno = "SELECT * FROM phd_monthly_pir_alarm_refno";
        $result_stmt_refno = $PHD->prepare($sqlstringRefno);
        $result_stmt_refno->execute();
        $result_res_refno = $result_stmt_refno->fetch(PDO::FETCH_ASSOC);
        $refno = $result_res_refno['pir_refno'];

        $currYear = date('y');
        $getYear =  substr($refno, 5, 2);

        if ($currYear != $getYear) {
            $ref_noResult = '0001-' . $currYear;
        } else {
            $currCount = substr($refno, 0, 4);
            $counter = intval($currCount) + 1;
            $ref_noResult = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }

        $sqlstring = "INSERT INTO phd_monthly_pir_alarm_header(pir_refno,pir_title,pir_date,performed1,performed1_sign,performed1_date,
            checked_by,checked_by_sign,noted_by,noted_by_sign)
            VALUES
            (?,?,?,?,?,?,?,?,?,?) RETURNING pirid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$refno, $title, $date_created, $prepared_by, $preparedBySignature, $date_created, $checkedBy, $checkedBySignature, $notedBy, $notedBySignature]);

        $itemData_List['pirheader_id'] = $PHD->lastInsertId();
        $itemData_List['pir_ref_no'] = $ref_noResult;
        return json_encode($itemData_List);
    }
    public function saveDataDetail($PHD, $performedBy, $strLocation, $strMotion, $strNoMotion, $strDual, $generateRefno, $pirid, $time_activated, $date)
    {
        $sqlstring = "INSERT INTO phd_monthly_pir_alarm_details
            (pir_id,location,time_activated,motion_detected,no_motion_detected,dual_presence,performed,performed_date,pir_refno)
            VALUES(?,?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$pirid, $strLocation, $time_activated, $strMotion, $strNoMotion, $strDual, $performedBy, $date, $generateRefno]);
        echo 'success!';
        //* ========== Update Ref No ==========
        $sqlstringUpRefno = "UPDATE phd_monthly_pir_alarm_refno SET pir_refno = ?";
        $result_stmt_refno = $PHD->prepare($sqlstringUpRefno);
        $result_stmt_refno->execute([$generateRefno]);
    }
    public function previewData($PHD, $pirid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_pir_alarm_details WHERE pir_id = '" . $pirid . "' ORDER BY pirdetailsid ASC";
        // $sqlstring = "SELECT * FROM phd_monthly_pir_alarm_details WHERE pir_id = '" . $pirid . "'";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
    }
    public function updateDataHeader($PHD, $prepared_by, $pirid, $result_res_sign, $result_res2, $result_res3, $date_created)
    {
        if ($result_res2['performed2'] != '') {
            if ($result_res3['performed3'] != '') {
                echo 'Exceed!';
            } else {
                $sqlstring = "UPDATE phd_monthly_pir_alarm_header SET performed3 = '" . $prepared_by . "',performed3_sign = '" . $result_res_sign . "' ,performed3_date = '" . $date_created . "' WHERE pirid = '" . $pirid . "'";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute();
            }
        } else {
            $sqlstring = "UPDATE phd_monthly_pir_alarm_header SET performed2 = '" . $prepared_by . "',performed2_sign = '" . $result_res_sign . "' ,performed2_date = '" . $date_created . "' WHERE pirid = '" . $pirid . "'";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute();
        }
    }
    public function updateDataDetail($PHD, $strMotion, $strNoMotion, $strDual, $userPrepared, $strDetail, $performedBy, $strbtnActivate, $strBtnActivate, $strBtnActivateFinal, $date_created)
    {
        $sqlstring = "UPDATE phd_monthly_pir_alarm_details SET ";
        if ($userPrepared == 'null') {
            if ($strbtnActivate == null) {
                $performedBy = null;
                $date_created = null;
                $strBtnActivateFinal = null;
            }
            $sqlstring .= "time_activated = ?,motion_detected = ?,no_motion_detected = ?,dual_presence = ?,performed = ?,performed_date = ? WHERE pirdetailsid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strBtnActivateFinal, $strMotion, $strNoMotion, $strDual, $performedBy, $date_created, $strDetail]);
        } else {
            $sqlstring .= " motion_detected = ? , no_motion_detected = ? , 
                dual_presence = ? WHERE pirdetailsid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strMotion, $strNoMotion, $strDual, $strDetail]);
        }
    }
    public function deleteData($PHD, $pirid)
    {
        $sqlstring = "DELETE FROM phd_monthly_pir_alarm_header WHERE pirid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$pirid]);
    }
    public function previewDataCheckedBy($PHD, $pirid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_pir_alarm_header WHERE pirid = '" . $pirid . "'";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['result'] = $row['checked_by'];
        }
        return json_encode($itemData_List);
    }
}
