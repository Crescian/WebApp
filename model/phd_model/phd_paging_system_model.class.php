<?php
class PhdPagingSystem
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_paging_monitoring_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $sqlstringCount = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) FROM phd_paging_monitoring_details WHERE prepared_by IS NOT NULL AND pagingheader_id = ?) AS prepared_count 
                    FROM phd_paging_monitoring_details WHERE pagingheader_id = ? ";
            $fetchCount_stmt = $PHD->prepare($sqlstringCount);
            $fetchCount_stmt->execute([$row['pagingheaderid'], $row['pagingheaderid']]);
            $fetchCount_row = $fetchCount_stmt->fetch(PDO::FETCH_ASSOC);

            $nestedData = array();
            $nestedData[] = $row['paging_header'];
            $nestedData[] = $row['paging_performed_by1'] == '' ? '-' : $row['paging_performed_by1'];
            $nestedData[] = $row['paging_performed_by2'] == '' ? '-' : $row['paging_performed_by2'];
            $nestedData[] = $row['paging_performed_by3'] == '' ? '-' : $row['paging_performed_by3'];
            $nestedData[] = $row['paging_checked_by'] == '' ? '-' : $row['paging_checked_by'];
            $nestedData[] = $row['paging_noted_by'] == '' ? '-' : $row['paging_noted_by'];
            $nestedData[] = array($row['pagingheaderid'], $fetchCount_row['total_count'], $fetchCount_row['prepared_count']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadDataPaging($PHD)
    {
        $resultData_List = array();

        $sqlstring = "SELECT category_name,location_name FROM phd_checklist_name
                INNER JOIN phd_checklist_assign ON phd_checklist_assign.phdchklist_id = phd_checklist_name.phdchklistid
                INNER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_checklist_assign.phdloccat_id
                INNER JOIN phd_loc_category_assign ON phd_loc_category_assign.phdloccat_id = phd_location_category.phdloccatid
                INNER JOIN phd_location ON phd_location.phdlocationid = phd_loc_category_assign.phdlocation_id
                WHERE checklist_name ILIKE '%Paging System Monitoring Checklist%' ORDER BY phdloccatassignid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List[$row['category_name']][] = $row; //* ======== 2D Array
        }
        return json_encode($resultData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataHeader($PHD, $prepared_by, $checked_by, $noted_by, $paging_header, $preparedBySignature, $checkedBySignature, $notedBySignature, $currentDate)
    {
        $itemData_List = array();
        //* ======== Generate Monitoring Ref No ========
        $fetchRefNo = "SELECT paging_ref_no FROM phd_paging_monitoring_ref_no";
        $fetchRefNo_stmt = $PHD->prepare($fetchRefNo);
        $fetchRefNo_stmt->execute();
        $fetchRefNo_row = $fetchRefNo_stmt->fetch(PDO::FETCH_ASSOC);
        $currYear = date('y');
        $getYear =  substr($fetchRefNo_row['paging_ref_no'], 5, 2);
        if ($currYear != $getYear) {
            $paging_ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($fetchRefNo_row['paging_ref_no'], 0, 4);
            $counter = intval($currCount) + 1;
            $paging_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        //* ======== Update Monitoring Ref No ========
        $updateRefno = "UPDATE phd_paging_monitoring_ref_no SET paging_ref_no = :paging_ref_no";
        $updateRefno_stmt = $PHD->prepare($updateRefno);
        $updateRefno_stmt->bindParam(':paging_ref_no', $paging_ref_no);
        $updateRefno_stmt->execute();

        //* ======== Insert Header ========
        $sqlstring = "INSERT INTO phd_paging_monitoring_header(paging_header,paging_date_created,paging_performed_by1,paging_performed_by1_sign,paging_performed_by1_date,paging_checked_by,paging_checked_by_sign,paging_noted_by,paging_noted_by_sign,paging_ref_no) 
                    VALUES(?,?,?,?,?,?,?,?,?,?) RETURNING pagingheaderid";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$paging_header, $currentDate, $prepared_by, $preparedBySignature, $currentDate, $checked_by, $checkedBySignature, $noted_by, $notedBySignature, $paging_ref_no]);

        $itemData_List['pagingheader_id'] = $PHD->lastInsertId();
        $itemData_List['paging_ref_no'] = $paging_ref_no;
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataDetails($PHD, $pagingheader_id, $paging_category_name, $paging_location_name, $paging_status_ok, $paging_status_defective, $paging_remarks, $paging_ref_no, $details_date_created, $prepared_by)
    {
        $sqlstring = "INSERT INTO phd_paging_monitoring_details(pagingheader_id,paging_category_name,paging_location_name,paging_status_ok,paging_status_defective,paging_remarks,details_date_created,paging_ref_no,prepared_by)
                VALUES(?,?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$pagingheader_id, $paging_category_name, $paging_location_name, $paging_status_ok, $paging_status_defective, $paging_remarks, $details_date_created, $paging_ref_no, $prepared_by]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function previewDataHeader($PHD, $pagingheaderid)
    {
        $sqlstring = "SELECT paging_checked_by,paging_noted_by FROM phd_paging_monitoring_header WHERE pagingheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$pagingheaderid]);
        $result_row = $result_stmt->fetch(PDO::FETCH_ASSOC);

        $itemData_List['checked_by'] = $result_row['paging_checked_by'];
        $itemData_List['noted_by'] = $result_row['paging_noted_by'];
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function previewDataDetails($PHD, $pagingheader_id)
    {
        $resultData_List = array();

        // WHERE pagingheader_id = :pagingheader_id";
        $sqlstring = "SELECT * FROM phd_paging_monitoring_details
                WHERE pagingheader_id = :pagingheader_id ORDER BY paging_category_name ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->bindParam(':pagingheader_id', $pagingheader_id);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List[$row['paging_category_name']][] = $row; //* ======== 2D Array
        }
        return json_encode($resultData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function updateDataHeader($PHD, $pagingheaderid, $prepared_by, $checked_by, $noted_by, $preparedBySignature, $checkedBySignature, $notedBySignature, $currentDate)
    {
        $chkSql = "SELECT paging_performed_by2,paging_performed_by3 FROM phd_paging_monitoring_header WHERE pagingheaderid = :pagingheaderid";
        $chkSql_stmt = $PHD->prepare($chkSql);
        $chkSql_stmt->bindParam(':pagingheaderid', $pagingheaderid);
        $chkSql_stmt->execute();
        $chkSql_row = $chkSql_stmt->fetch(PDO::FETCH_ASSOC);
        if ($chkSql_row['paging_performed_by2'] == '') {
            $prepared_field = 'paging_performed_by2 = ? , paging_performed_by2_sign = ?, paging_performed_by2_date = ?';
        } else {
            $prepared_field = 'paging_performed_by3 = ? , paging_performed_by3_sign = ?, paging_performed_by3_date = ?';
        }
        //* ======== Update Header ========
        $sqlstring = "UPDATE phd_paging_monitoring_header SET " . $prepared_field . ",paging_checked_by = ?,paging_checked_by_sign = ?, 
                paging_noted_by = ?,paging_noted_by_sign = ? WHERE pagingheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$prepared_by, $preparedBySignature, $currentDate, $checked_by, $checkedBySignature, $noted_by, $notedBySignature, $pagingheaderid]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function updateDataDetails($PHD, $pagingheaderid, $paging_location_name, $paging_category_name, $paging_status_ok, $paging_status_defective, $paging_remarks, $prepared_by, $date_created)
    {
        $sqlstring = "UPDATE phd_paging_monitoring_details SET paging_status_ok = ?, paging_status_defective = ?, paging_remarks = ?,
                details_date_created = ?,prepared_by = ? WHERE pagingheader_id = ? AND paging_category_name = ? 
                AND paging_location_name = ? AND prepared_by ISNULL";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$paging_status_ok, $paging_status_defective, $paging_remarks, $date_created, $prepared_by, $pagingheaderid, $paging_category_name, $paging_location_name]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteData($PHD, $pagingheaderid)
    {
        $sqlstring = "DELETE FROM phd_paging_monitoring_header WHERE pagingheaderid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$pagingheaderid]);
        $PHD = null; //* ======== Close Connection ========
    }
}
