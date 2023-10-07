<?php
class PhdMonthlyFireSd
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_fire_alarm_smoke_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_monthly_fire_alarm_smoke_details WHERE prepared_by IS NOT NULL AND firealarmsmoke_id = ?) AS prepared_count 
                    FROM phd_monthly_fire_alarm_smoke_details WHERE firealarmsmoke_id = ? ";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute([$row['firealarmsmokeid'], $row['firealarmsmokeid']]);
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_prepared']), 'Y-m-d');
            $nestedData[] = $row['header_title'];
            $nestedData[] = $row['prepared_by1'] == '' ? '-' : $row['prepared_by1'];
            $nestedData[] = $row['checked_by'] == '' ? '-' : $row['checked_by'];
            $nestedData[] = $row['noted_by'] == '' ? '-' : $row['noted_by'];
            $nestedData[] = array($row['firealarmsmokeid'], $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function generateData($PHD)
    {
        $categoryArray = array();
        $sqlstringcategory = "SELECT category_name,zone_category_name,location_name FROM phd_checklist_assign
                            INNER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
                            INNER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_checklist_assign.phdloccat_id
                            INNER JOIN phd_loc_category_assign ON phd_loc_category_assign.phdloccat_id = phd_checklist_assign.phdloccat_id
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_loc_category_assign.phdlocation_id
                            WHERE checklist_name = 'Monthly Fire Alarm System Checklist (SMOKE DETECTOR)' ORDER BY phdloccatassignid ASC";
        $result_stmt_categ = $PHD->prepare($sqlstringcategory);
        $result_stmt_categ->execute();
        while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
            $categoryArray[$row['category_name']][$row['zone_category_name']][] = $row['location_name'];
        }
        return json_encode($categoryArray);
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
        $BannerWebLive = null;
    }

    public function saveDataHeader($PHD, $title, $prepared_by, $checkedBy, $notedBy, $preparedBySignature, $checkedBySignature, $notedBySignature, $date_created)
    {
        $itemData_List = array();

        // * GENERATE REFERRENCE NUMBER *//
        $sqlstringRefno = "SELECT * FROM phd_monthly_fire_alarm_smoke_refno";
        $result_stmt_refno = $PHD->prepare($sqlstringRefno);
        $result_stmt_refno->execute();
        $result_res_refno = $result_stmt_refno->fetch(PDO::FETCH_ASSOC);
        $refno = $result_res_refno['fire_alarm_smoke_refno'];

        $currYear = date('y');
        $getYear =  substr($refno, 5, 2);

        if ($currYear != $getYear) {
            $ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($refno, 0, 4);
            $counter = intval($currCount) + 1;
            $ref_noResult = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        // * ========== Update Ref No ==========
        // $sqlstringUpRefno = "UPDATE phd_monthly_fire_alarm_refno SET fire_alarm_refno = :fire_alarm_refno";
        // $result_stmt_refno = $PHD->prepare($sqlstringUpRefno);
        // $result_stmt_refno->bindParam(':fire_alarm_refno', $ref_noResult);
        // $result_stmt_refno->execute();

        $sqlstring = "INSERT INTO phd_monthly_fire_alarm_smoke_header(fire_alarm_refno,header_title,date_prepared,prepared_by1,prepared_by1_sign,prepared_by1_date,
            checked_by,checked_by_sign,noted_by,noted_by_sign)VALUES(?,?,?,?,?,?,?,?,?,?) RETURNING firealarmsmokeid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$refno, $title, $date_created, $prepared_by, $preparedBySignature, $date_created, $checkedBy, $checkedBySignature, $notedBy, $notedBySignature]);

        $itemData_List['fireheader_id'] = $PHD->lastInsertId();
        return json_encode($itemData_List);
    }
    public function saveDataDetail($PHD, $performedBy, $strLocation, $strZone, $strUnits, $strCategory, $strWorking, $strRemark, $fireid, $time_activated, $date, $preparedBySignature)
    {
        $sqlstring = "INSERT INTO phd_monthly_fire_alarm_smoke_details
            (firealarmsmoke_id,category_name,zone,location,no_units,date_performed,status,remarks,prepared_by,prepared_by_sign)
            VALUES(?,?,?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$fireid, $strCategory, $strZone, $strLocation, $strUnits, $time_activated, $strWorking, $strRemark, $performedBy, $preparedBySignature]);
        return 'success!';
    }
    public function updateDataHeader($PHD, $prepared_by, $fireid, $result_res_sign, $result_res2, $result_res3, $date_created)
    {
        if ($result_res2['prepared_by2'] != '') {
            if ($result_res3['prepared_by3'] != '') {
                $itemData_List['result'] = 'Exceed!';
            } else {
                $sqlstring = "UPDATE phd_monthly_fire_alarm_smoke_header SET prepared_by3 = '" . $prepared_by . "',prepared_by3_sign = '" . $result_res_sign . "' ,prepared_by3_date = '" . $date_created . "' 
                WHERE firealarmsmokeid = '" . $fireid . "'";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute();
                $itemData_List['result'] = '';
            }
        } else {
            $sqlstring = "UPDATE phd_monthly_fire_alarm_smoke_header SET prepared_by2 = '" . $prepared_by . "',prepared_by2_sign = '" . $result_res_sign . "' ,prepared_by2_date = '" . $date_created . "' 
            WHERE firealarmsmokeid = '" . $fireid . "'";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute();
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataDetail($PHD, $strDetails, $strPrepared, $strbtnActivate, $strLocation, $strUnits, $strZone, $strCategory, $strWorking, $strRemark, $performedBy, $performedBySignature)
    {
        $sqlstring = "UPDATE phd_monthly_fire_alarm_smoke_details SET ";
        if ($strPrepared == 'null') {
            if ($strRemark == '') {
                $performedBy = null;
                $performedBySignature = null;
                $strbtnActivate = null;
            }
            $sqlstring .= "date_performed = ? , status = ?,no_units = ?,remarks = ? , prepared_by = ? , prepared_by_sign = ? 
            WHERE firealarmsmokedetailsid = ? AND zone = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strbtnActivate, $strWorking, $strUnits, $strRemark, $performedBy, $performedBySignature, $strDetails, $strZone]);
        } else {
        }
    }
    public function previewData($PHD, $fireid)
    {
        $categoryArray = array();
        $sqlstringcategory = "SELECT firealarmsmokedetailsid,category_name,zone,location,no_units,date_performed,status,remarks,prepared_by, encode(prepared_by_sign, 'escape') AS prepared_by_sign
            FROM phd_monthly_fire_alarm_smoke_details WHERE firealarmsmoke_id = '" . $fireid . "' ORDER BY firealarmsmokedetailsid ASC";
        // $sqlstringcategory = "SELECT firealarmsmokedetailsid,category_name,zone,location,no_units,date_performed,status,remarks,prepared_by, encode(prepared_by_sign, 'escape') AS prepared_by_sign
        //     FROM phd_monthly_fire_alarm_smoke_details WHERE firealarmsmoke_id = '" . $fireid . "'";
        $result_stmt_categ = $PHD->prepare($sqlstringcategory);
        $result_stmt_categ->execute();
        while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
            $categoryArray[$row['category_name']][$row['zone']][] = $row;
        }
        // print_r($categoryArray);
        return json_encode($categoryArray);
    }
    public function deleteData($PHD, $fireid)
    {
        $sqlstring = "DELETE FROM phd_monthly_fire_alarm_smoke_header WHERE firealarmsmokeid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$fireid]);
    }
    public function previewDataCheckedBy($PHD, $fireid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_fire_alarm_smoke_header WHERE firealarmsmokeid = '" . $fireid . "'";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['result'] = $row['checked_by'];
        }
        return json_encode($itemData_List);
    }
}
