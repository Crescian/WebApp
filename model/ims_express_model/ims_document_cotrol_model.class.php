<?php
date_default_timezone_set('Asia/Manila');
class ImsDocumentControl
{
    public function fetchSignature($emp_name, $bannerWeb)
    {
        $sqlstring = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = ?";
        $result_stmt = $bannerWeb->prepare($sqlstring);
        $result_stmt->execute([$emp_name]);
        while ($result_row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            return $result_row['employee_signature'];
        }
        $bannerWeb = null; //* ======== Close Connection ========
    }

    public function loadDocumentTree($imsExpress, $inTable, $inFieldId, $inField1, $inField2)
    {
        // $itemData_List = array();
        $sqlstring = "SELECT CAST({$inFieldId} AS varchar) AS id,{$inField1} AS text, CASE WHEN CAST({$inField2} AS varchar) = '0' THEN '#' ELSE CAST({$inField2} AS varchar) END AS parent, doc_menu_type AS type 
            FROM {$inTable}";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $app_menu[] = $row;
            }
            // // * build array of item references
            // foreach ($app_menu as $key => $item) {
            //     $itemData_List[$item['id']] = $item;
            // }
            return json_encode($app_menu);
        } else {
            return json_encode('empty');
        }
        $imsExpress = null; //* ======== Close Connection ========
    }

    public function saveDocRegistration($imsExpress, $bannerWeb, $doc_department, $date_requested, $doc_title, $doc_number, $doc_revision, $doc_req_type, $doc_level, $doc_reason_remarks, $doc_type, $doc_mother_procedure, $doc_owner_originator, $doc_owner_user, $doc_prepared_by, $doc_approved_by, $menuid, $doc_pdf_file_value, $doc_word_file_value)
    {
        //* ======== Generate Ref No ========
        $sqlRefNo = "SELECT drf_ref_no FROM ims_drf_ddc_no";
        $sqlRefNo_stmt = $imsExpress->prepare($sqlRefNo);
        $sqlRefNo_stmt->execute();
        while ($sqlRefNo_row = $sqlRefNo_stmt->fetch(PDO::FETCH_ASSOC)) {
            $referrence_no = $sqlRefNo_row['drf_ref_no'];
        }
        $currYear = date('y');
        $getYear =  substr($referrence_no, 5, 2);
        if ($currYear != $getYear) {
            $drf_ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($referrence_no, 0, 4);
            $counter = intval($currCount) + 1;
            $drf_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        //* ======== Fetch Employee Prepared By Signature ========
        $preparedBySignature = self::fetchSignature($doc_prepared_by, $bannerWeb);
        //* ======== Fetch Employee Approved By Signature ========
        $approvedBySignature = self::fetchSignature($doc_approved_by, $bannerWeb);
        //* ======== Save Document ========
        $sqlstring = "INSERT INTO ims_document_registered(doc_ref_no,doc_department,doc_date_requested,doc_title,doc_number,doc_revision,doc_req_type,doc_level,doc_remarks,doc_prepared_by,doc_prepared_by_sign,doc_approved_by,doc_approved_by_sign,docmenu_id,doc_type,doc_mother_procedure,doc_owner_user,doc_owner_originator,doc_pdf_file,doc_word_file) 
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute([$drf_ref_no, $doc_department, $date_requested, $doc_title, strtoupper($doc_number), $doc_revision, $doc_req_type, $doc_level, $doc_reason_remarks, $doc_prepared_by, $preparedBySignature, $doc_approved_by, $approvedBySignature, $menuid, $doc_type, $doc_mother_procedure, $doc_owner_user, $doc_owner_originator, $doc_pdf_file_value, $doc_word_file_value]);

        //* ======== Add Document to Document Menu Tree ========
        $doc_menu_title = strtoupper($doc_number) . ': ' . $doc_title;
        $document_type = 'file';
        $sqlDocTree = "INSERT INTO ims_document_menu(doc_menu_title,doc_menu_parent_id,doc_menu_type) VALUES(?,?,?)";
        $sqlDocTree_stmt = $imsExpress->prepare($sqlDocTree);
        $sqlDocTree_stmt->execute([$doc_menu_title, $menuid, $document_type]);

        //* ======== Update Ref No ========
        $sqlUptRefNo = "UPDATE ims_drf_ddc_no SET drf_ref_no = ?";
        $sqlUptRefNo_stmt = $imsExpress->prepare($sqlUptRefNo);
        $sqlUptRefNo_stmt->execute([$drf_ref_no]);

        $imsExpress = null; //* ======== Close Connection ========
    }

    public function loadDepartment($bannerWeb)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_department ORDER BY department ASC";
        $result_stmt = $bannerWeb->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['dept_code']] = $row['department'];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
        $bannerWeb = null; //* ======== Close Connection ========
    }

    public function loadDepartmentHead($bannerWeb)
    {
        $itemData_List = array();
        $sqlstring = "SELECT pos_code,pos_code,(emp_fn || ' ' || emp_sn) AS fullname FROM prl_employee WHERE job_level in('Level-08', 'Level-07') ORDER BY fullname ASC";
        $result_stmt = $bannerWeb->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['pos_code']] = $row['fullname'];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
        $bannerWeb = null; //* ======== Close Connection ========
    }

    public function loadEmployee($bannerWeb, $dept_code)
    {
        $itemData_List = array();
        $sqlstring = "SELECT empno, (emp_fn || ' ' || emp_sn) AS fullname FROM prl_employee WHERE dept_code = ?";
        $result_stmt = $bannerWeb->prepare($sqlstring);
        $result_stmt->execute([$dept_code]);
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['fullname']] = $row['fullname'];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
        $bannerWeb = null; //* ======== Close Connection ========
    }
}
