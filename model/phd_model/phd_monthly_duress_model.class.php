<?php
class PhdDuress
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_duress_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_monthly_duress_details WHERE verified_by IS NOT NULL AND duress_id = ?) AS prepared_count 
                    FROM phd_monthly_duress_details WHERE duress_id = ? ";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute([$row['duressid'], $row['duressid']]);
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = date_format(date_create($row['duress_date']), 'Y-m-d');
            $nestedData[] = $row['duress_title'];
            $nestedData[] = $row['performed1'] == '' ? '-' : $row['performed1'];
            $nestedData[] = $row['checked_by'] == '' ? '-' : $row['checked_by'];
            $nestedData[] = $row['noted_by'] == '' ? '-' : $row['noted_by'];
            $nestedData[] = array($row['duressid'], $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function cmsData($BannerWebLive)
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
        $sqlstringRefno = "SELECT * FROM phd_monthly_duress_refno";
        $result_stmt_refno = $PHD->prepare($sqlstringRefno);
        $result_stmt_refno->execute();
        $result_res_refno = $result_stmt_refno->fetch(PDO::FETCH_ASSOC);
        $refno = $result_res_refno['duress_refno'];

        $currYear = date('y');
        $getYear =  substr($refno, 5, 2);

        if ($currYear != $getYear) {
            $ref_noResult = '0001-' . $currYear;
        } else {
            $currCount = substr($refno, 0, 4);
            $counter = intval($currCount) + 1;
            $ref_noResult = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        $sqlstring = "INSERT INTO phd_monthly_duress_header(duress_refno,duress_title,duress_date,performed1,performed1_sign,performed1_date,
            checked_by,checked_by_sign,noted_by,noted_by_sign)VALUES(?,?,?,?,?,?,?,?,?,?) RETURNING duressid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$refno, $title, $date_created, $prepared_by, $preparedBySignature, $date_created, $checkedBy, $checkedBySignature, $notedBy, $notedBySignature]);

        $itemData_List['pagingheader_id'] = $PHD->lastInsertId();
        $itemData_List['paging_ref_no'] = $ref_noResult;
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function generateData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT checklist_name,location_name FROM phd_checklist_assign 
                            INNER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_checklist_assign.phdlocation_id
                            WHERE checklist_name = 'Monthly Duress Checklist' ORDER BY phdchklistassignid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        // print_r($itemData_List);
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function saveDataDetails($PHD, $verifiedBy, $duressid, $strLocation, $strActive, $strOutsource, $strResponse, $time_activated, $time_verified, $generateRefno, $performedBy, $date)
    {
        $sqlstring = "INSERT INTO phd_monthly_duress_details
            (duress_id,location,active_duress,outsource_cms,response_within_2mins,time_activated,time_verified,performed,performed_date,duress_refno,verified_by)
            VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$duressid, $strLocation, $strActive, $strOutsource, $strResponse, $time_activated, $time_verified, $performedBy, $date, $generateRefno, $verifiedBy]);
        $itemData_List['result'] = 'success!';
        //* ========== Update Ref No ==========
        $sqlstringUpRefno = "UPDATE phd_monthly_duress_refno SET duress_refno = ?";
        $result_stmt_refno = $PHD->prepare($sqlstringUpRefno);
        $result_stmt_refno->execute([$generateRefno]);
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function previewData($PHD, $duressid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_duress_details WHERE duress_id = '" . $duressid . "' ORDER BY duressdetailsid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteData($PHD, $duressid)
    {
        $sqlstring = "DELETE FROM phd_monthly_duress_header WHERE duressid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$duressid]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function updateDataHeader($PHD, $prepared_by, $result_res_sign, $result_res2, $result_res3, $duressid, $date_created)
    {
        if ($result_res2['performed2'] != '') {
            if ($result_res3['performed3'] != '') {
                $itemData_List['result'] = 'Exceed!';
            } else {
                $sqlstring = "UPDATE phd_monthly_duress_header SET performed3 = '" . $prepared_by . "',
                performed3_sign = '" . $result_res_sign . "' ,performed3_date = '" . $date_created . "' WHERE duressid = '" . $duressid . "'";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute();
                $itemData_List['result'] = '';
            }
        } else {
            $sqlstring = "UPDATE phd_monthly_duress_header SET performed2 = '" . $prepared_by . "',
            performed2_sign = '" . $result_res_sign . "' ,performed2_date = '" . $date_created . "' WHERE duressid = '" . $duressid . "'";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute();
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataDetail($PHD, $performedBy, $strActive, $strOutsource, $strResponse, $duressid, $userPrepare, $strbtnActivate, $strBtnActivateFinal, $strbtnVerified, $strbtnVerifiedFinal, $date_created)
    {
        $sqlstring = "UPDATE phd_monthly_duress_details SET ";
        if ($strbtnActivate != '' and $strbtnVerified != '') {
            $sqlstring .= "time_verified = ? ,verified_by = ? WHERE duressdetailsid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strBtnActivateFinal, $performedBy, $duressid]);
        } else {
            if ($strbtnActivate == '') {
                $strBtnActivateFinal = null;
            }
            $sqlstring .= "active_duress = ? ,
                outsource_cms = ? , response_within_2mins = ? , 
                time_activated = ? ,performed = ? , 
                performed_date = ? WHERE duressdetailsid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$strActive, $strOutsource, $strResponse, $strBtnActivateFinal, $performedBy, $date_created, $duressid]);
        }
        $PHD = null; //* ======== Close Connection ========
    }
    public function previewDataCheckedBy($PHD, $duressid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_duress_header WHERE duressid = '" . $duressid . "'";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['result'] = $row['checked_by'];
        }
        return json_encode($itemData_List);
    }
}
