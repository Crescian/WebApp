<?php
class ImsMsdForms
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

    public function docStatus($docStats)
    {
        switch ($docStats) {
            case 'Pending':
                $docStatus = '<span class="badge bg-warning col-sm-12">Pending</span>';
                break;
            case 'Processing':
                $docStatus = '<span class="badge bg-success col-sm-12">Processing</span>';
                break;
            case 'Registered':
                $docStatus = '<span class="badge bg-dark col-sm-12">Registered</span>';
                break;
        }
        return $docStatus;
    }

    public function loadDocRegistration($imsExpress, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'doc_ref_no',
            1 => 'doc_date_requested',
            2 => 'doc_date_effective',
            3 => 'doc_date_received',
            4 => 'doc_title',
            5 => 'doc_number',
            6 => 'doc_revision',
            7 => 'doc_req_type',
            8 => 'doc_level',
            9 => 'doc_date_registered',
            10 => 'doc_ddc_no',
            11 => 'doc_date_issued',
            12 => 'doc_total_days',
            13 => 'doc_remarks'
        );
        $sqlstring = "SELECT * FROM ims_document_registered WHERE 1 = 1";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute();
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (doc_ref_no ILIKE '%" . $searchValue . "%' OR TO_CHAR(doc_date_requested, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR TO_CHAR(doc_date_effective, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR TO_CHAR(doc_date_received, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR
                doc_title ILIKE '%" . $searchValue . "%' OR doc_number ILIKE '%" . $searchValue . "%' OR CAST(doc_revision AS TEXT) ILIKE '%" . $searchValue . "%' OR doc_req_type ILIKE '%" . $searchValue . "%' OR doc_level ILIKE '%" . $searchValue . "%' OR
                TO_CHAR(doc_date_registered, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR doc_ddc_no ILIKE '%" . $searchValue . "%' OR TO_CHAR(doc_date_issued, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR 
                CAST(doc_total_days AS TEXT) ILIKE '%" . $searchValue . "%' OR doc_remarks ILIKE '%" . $searchValue . "%')";
            $result_stmt = $imsExpress->prepare($sqlstring);
            $result_stmt->execute();
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = array(
                'DRF-' . $row['doc_ref_no'],
                $row['doc_date_requested'],
                $row['doc_date_effective'] == '' ? '-' : $row['doc_date_effective'],
                $row['doc_date_received'] == '' ? '-' : $row['doc_date_received'],
                $row['doc_title'],
                $row['doc_number'],
                $row['doc_revision'],
                $row['doc_req_type'],
                $row['doc_level'],
                $row['doc_ddc_no'] == '' ? '-' : 'DDC-' . $row['doc_ddc_no'],
                $row['doc_date_issued'] == '' ? '-' : $row['doc_date_issued'],
                $row['doc_total_days'] == '' ? '-' : $row['doc_total_days'],
                $row['doc_remarks'],
                self::docStatus($row['doc_status']),
                [
                    $row['docregisteredid'],
                    $row['doc_date_received'] == '' ? '-' : $row['doc_date_received'],
                    $row['doc_date_effective'] == '' ? '-' : $row['doc_date_effective'],
                    $row['doc_date_issued'] == '' ? '-' : $row['doc_date_issued'],
                    $row['doc_prepared_by']
                ]
            );
        }
        //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
        $imsExpress = null; //* ======== Close Connection ========
    }

    public function acknowledgeDocument($imsExpress, $bannerWeb, $docregisteredid, $emp_fullname)
    {
        $receivedBySignature = self::fetchSignature($emp_fullname, $bannerWeb);
        $sqlstring = "UPDATE ims_document_registered SET doc_date_received = ?, doc_received_by = ?, doc_received_by_sign = ? , doc_status = 'Processing' WHERE docregisteredid = ?";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute([date('Y-m-d'), $emp_fullname, $receivedBySignature, $docregisteredid]);
        $imsExpress = null; //* ======== Close Connection ========
    }

    public function doneDocument($imsExpress, $docregisteredid, $date_received)
    {
        //* ======== Generate Ref No ========
        $sqlRefNo = "SELECT ddc_ref_no FROM ims_drf_ddc_no";
        $sqlRefNo_stmt = $imsExpress->prepare($sqlRefNo);
        $sqlRefNo_stmt->execute();
        while ($sqlRefNo_row = $sqlRefNo_stmt->fetch(PDO::FETCH_ASSOC)) {
            $referrence_no = $sqlRefNo_row['ddc_ref_no'];
        }
        $currYear = date('y');
        $getYear =  substr($referrence_no, 5, 2);
        if ($currYear != $getYear) {
            $ddc_ref_no = '0001-' . $currYear;
        } else {
            $currCount = substr($referrence_no, 0, 4);
            $counter = intval($currCount) + 1;
            $ddc_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
        }
        $total_days = (strtotime(date('Y-m-d')) - strtotime($date_received)) / 60 / 60 / 24;

        $sqlstring = "UPDATE ims_document_registered SET doc_date_issued = ?, doc_ddc_no = ?, doc_total_days = ?, doc_status = 'Registered' WHERE docregisteredid = ?";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute([date('Y-m-d'), $ddc_ref_no, $total_days, $docregisteredid]);

        //* ======== Update Ref No ========
        $sqlUptRefNo = "UPDATE ims_drf_ddc_no SET ddc_ref_no = ?";
        $sqlUptRefNo_stmt = $imsExpress->prepare($sqlUptRefNo);
        $sqlUptRefNo_stmt->execute([$ddc_ref_no]);

        $imsExpress = null; //* ======== Close Connection ========
    }

    public function saveEffectiveDocument($imsExpress, $docregisteredid, $doc_effective_date)
    {
        $sqlstring = "UPDATE ims_document_registered SET doc_date_effective = ? WHERE docregisteredid = ?";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute([$doc_effective_date, $docregisteredid]);
        $imsExpress = null; //* ======== Close Connection ========
    }

    public function acknowledgeRegisteredDocument($imsExpress, $docregisteredid)
    {
        $sqlstring = "UPDATE ims_document_registered SET doc_date_acknowledge = ? WHERE docregisteredid = ?";
        $result_stmt = $imsExpress->prepare($sqlstring);
        $result_stmt->execute([date('Y-m-d'), $docregisteredid]);
        $imsExpress = null; //* ======== Close Connection ========
    }
}
