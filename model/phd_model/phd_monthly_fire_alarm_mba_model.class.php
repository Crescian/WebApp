<?php
class PhdFireAlarmMba
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_fire_alarm_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_monthly_fire_alarm_details WHERE prepared_by IS NOT NULL AND firealarm_id = ?) AS prepared_count 
                    FROM phd_monthly_fire_alarm_details WHERE firealarm_id = ? ";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute([$row['firealarmid'], $row['firealarmid']]);
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_prepared']), 'Y-m-d');
            $nestedData[] = $row['header_title'];
            $nestedData[] = $row['prepared_by1'] == '' ? '-' : $row['prepared_by1'];
            $nestedData[] = $row['checked_by'] == '' ? '-' : $row['checked_by'];
            $nestedData[] = $row['noted_by'] == '' ? '-' : $row['noted_by'];
            $nestedData[] = array($row['firealarmid'], $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataCms($BannerWebLive)
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
        $categoryArray = array();
        // $sqlstringcategory = "SELECT category_name,location,units FROM phd_checklist_assign
        //                     INNER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
        //                     INNER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_checklist_assign.phdloccat_id
        //                     INNER JOIN phd_loc_category_assign ON phd_loc_category_assign.phdloccat_id = phd_checklist_assign.phdloccat_id
        //                     INNER JOIN phd_location ON phd_location.phdlocationid = phd_loc_category_assign.phdlocation_id
        // 					INNER JOIN phd_assign_units ON phd_assign_units.location = phd_location.location_name
        //                     WHERE checklist_name = 'Monthly Fire Alarm System Checklist (MCP/BELL/ANNUNCIATOR)' ORDER BY location ASC";


        $sqlstringcategory = "SELECT category_name,phdlocation_name,units FROM phd_checklist_assign
                            INNER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
                            INNER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_checklist_assign.phdloccat_id
                            INNER JOIN phd_loc_category_assign ON phd_loc_category_assign.phdloccat_id = phd_checklist_assign.phdloccat_id
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_loc_category_assign.phdlocation_id
							INNER JOIN phd_assign_units ON phd_assign_units.phdlocation_name = phd_location.location_name
                            WHERE checklist_name ILIKE '%Monthly Fire Alarm System Checklist (MCP/BELL/ANNUNCIATOR)%' ORDER BY phdloccatassignid ASC";
        $result_stmt_categ = $PHD->prepare($sqlstringcategory);
        $result_stmt_categ->execute();
        while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
            $categoryArray[$row['category_name']][$row['phdlocation_name']][] = $row['units'];
        }
        // print_r($categoryArray);
        return json_encode($categoryArray);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function previewData($PHD, $fireid)
    {
        $categoryArray = array();
        $sqlstringcategory = "SELECT firealarmdetailsid,category_name,location,units,date_performed,status,remarks,prepared_by, encode(perpared_by_sign, 'escape') AS perpared_by_sign,date_prepared_units
            FROM phd_monthly_fire_alarm_details WHERE firealarm_id = '" . $fireid . "' ORDER BY firealarmdetailsid ASC";
        // $sqlstringcategory = "SELECT firealarmdetailsid,category_name,location,units,date_performed,status,remarks,prepared_by, encode(perpared_by_sign, 'escape') AS perpared_by_sign,date_prepared_units
        //     FROM phd_monthly_fire_alarm_details WHERE firealarm_id = '" . $fireid . "'";
        $result_stmt_categ = $PHD->prepare($sqlstringcategory);
        $result_stmt_categ->execute();
        while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
            $categoryArray[$row['category_name']][$row['location']][] = $row;
        }
        // print_r($categoryArray);
        return json_encode($categoryArray);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function saveDataHeader($PHD, $title, $prepared_by, $checkedBy, $notedBy, $preparedBySignature, $checkedBySignature, $notedBySignature, $date_created)
    {
        $itemData_List = array();
        // * GENERATE REFERRENCE NUMBER *//
        $sqlstringRefno = "SELECT * FROM phd_monthly_fire_alarm_refno";
        $result_stmt_refno = $PHD->prepare($sqlstringRefno);
        $result_stmt_refno->execute();
        $result_res_refno = $result_stmt_refno->fetch(PDO::FETCH_ASSOC);
        $refno = $result_res_refno['fire_alarm_refno'];

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

        $sqlstring = "INSERT INTO phd_monthly_fire_alarm_header(fire_alarm_refno,header_title,date_prepared,prepared_by1,prepared_by1_sign,prepared_by1_date,
            checked_by,checked_by_sign,noted_by,noted_by_sign)VALUES(?,?,?,?,?,?,?,?,?,?) RETURNING firealarmid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$refno, $title, $date_created, $prepared_by, $preparedBySignature, $date_created, $checkedBy, $checkedBySignature, $notedBy, $notedBySignature]);

        $itemData_List['fireheader_id'] = $PHD->lastInsertId();
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function saveData($PHD, $performedBy, $strLocation, $strCategory, $strUnit, $strbtnActivateUnits, $strWorking, $strRemark, $fireid, $preparedBySignature)
    {
        if ($strbtnActivateUnits == null) {
            $performedBy = null;
            $date = null;
            $preparedBySignature = null;
        } else {
            $date = date("Y-m-d");
        }
        $sqlstring = "INSERT INTO phd_monthly_fire_alarm_details
            (firealarm_id,category_name,location,units,status,remarks,prepared_by,perpared_by_sign,date_prepared_units)
            VALUES(?,?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$fireid, $strCategory, $strLocation, $strUnit, $strWorking, $strRemark, $performedBy, $preparedBySignature, $strbtnActivateUnits]);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataHeader($PHD, $prepared_by, $fireid, $result_res_sign, $result_res2, $result_res3, $date_created)
    {
        if ($result_res2['prepared_by2'] != '') {
            if ($result_res3['prepared_by3'] != '') {
                $itemData_List['result'] = 'Exceed!';
            } else {
                $sqlstring = "UPDATE phd_monthly_fire_alarm_header SET ";
                $sqlstring .= "prepared_by3 = '" . $prepared_by . "',prepared_by3_sign = '" . $result_res_sign . "' ,prepared_by3_date = '" . $date_created . "' WHERE firealarmid = '" . $fireid . "'";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute();
                $itemData_List['result'] = '';
            }
        } else {
            $sqlstring = "UPDATE phd_monthly_fire_alarm_header SET ";
            $sqlstring .= "prepared_by2 = '" . $prepared_by . "',prepared_by2_sign = '" . $result_res_sign . "' ,prepared_by2_date = '" . $date_created . "' WHERE firealarmid = '" . $fireid . "'";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute();
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateData($PHD, $detailsId, $preparedBy, $strUnit, $strWorking, $strRemark, $performedBy, $preparedBySignature, $date_created)
    {
        $sqlstring = "UPDATE phd_monthly_fire_alarm_details SET ";
        if ($preparedBy == 'null') {
            if ($strRemark == '') {
                $performedBy = null;
                $date_created = null;
                $preparedBySignature = null;
            }
            $sqlstring .= "date_prepared_units = ? , status = ?,
                            remarks = ? , prepared_by = ? , perpared_by_sign = ?
                            WHERE firealarmdetailsid = ? AND units = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$date_created, $strWorking, $strRemark, $performedBy, $preparedBySignature, $detailsId, $strUnit]);
        } else {
            $sqlstring .= "status = ?, remarks = ? 
                WHERE firealarmdetailsid = ? AND units = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strWorking, $strRemark, $detailsId, $strUnit,]);
        }
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function deleteData($PHD, $fireid)
    {
        $sqlstring = "DELETE FROM phd_monthly_fire_alarm_header WHERE firealarmid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$fireid]);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function previewDataCheckedBy($PHD, $fireid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_fire_alarm_header WHERE firealarmid = '" . $fireid . "'";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['result'] = $row['checked_by'];
        }
        return json_encode($itemData_List);
    }
}
