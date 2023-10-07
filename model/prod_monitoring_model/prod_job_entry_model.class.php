<?php
class ProdJobEntry
{
    public function fetchData($prod)
    {
        $itemData_List = array();
        $sqlstring = "SELECT jobentryid,job_priority,date_receive,customer_name,jonumber,job_description,job_filename,job_quantity,delivery_date,
            start_transfer_date,end_transfer_date,job_status
            FROM prod_job_entry_header WHERE job_status <> 'Done'";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['job_priority'];
            $nestedData[] = date_format(date_create($row['date_receive']), 'Y-m-d');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = date_format(date_create($row['start_transfer_date']), 'Y-m-d');
            $nestedData[] = date_format(date_create($row['end_transfer_date']), 'Y-m-d');
            $nestedData[] = $row['delivery_date'];
            $nestedData[] = self::jobStatus($row['job_status']);
            $nestedData[] = $row['jobentryid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    function jobStatus($jobStats)
    {
        switch ($jobStats) {
            case 'Pending':
                $jobStatus = '<span class="badge bg-warning col-sm-12">Pending</span>';
                break;
            case 'On-Going':
                $jobStatus = '<span class="badge bg-success col-sm-12">On-Going</span>';
                break;
            case 'Process Done':
                $jobStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Done':
                $jobStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Hold':
                $jobStatus = '<span class="badge bg-danger col-sm-12">On Hold</span>';
                break;
            case 'Process Hold':
                $jobStatus = '<span class="badge bg-danger col-sm-12">Process Hold</span>';
                break;
        }
        return $jobStatus;
    }

    public function loadJobOrderNumber($prod, $companyname)
    {
        $itemData_List = array();

        $sqlstring = "SELECT DISTINCT jonumber FROM prod_template_assign WHERE customer_name = ? ORDER BY jonumber DESC";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$companyname]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row['jonumber'];
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadTemplateName($prod, $company, $jonumber, $orderid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT prod_template_name.templateid,prod_template_name.template_name
                FROM prod_template_assign
                INNER JOIN prod_template_name ON prod_template_name.templateid = prod_template_assign.template_id
                WHERE customer_name = ? AND orderid = ? AND jonumber = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$company, $orderid, $jonumber]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[$row['templateid']] = $row['template_name'];
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadAssignTemplate($prod, $templateid)
    {
        $itemData_List = array();
        $result_sql = "SELECT TempProc.process_seq,ListProc.process_name,section_name,card_side
                FROM prod_template_name TempName
                INNER JOIN prod_template_process TempProc ON TempProc.template_id = TempName.templateid
                INNER JOIN prod_process_name ListProc ON ListProc.processid = TempProc.process_id
                INNER JOIN prod_section_name ON prod_section_name.sectionid = ListProc.section_id
                WHERE TempName.templateid = ? ORDER BY TempProc.process_seq";
        $result_stmt = $prod->prepare($result_sql);
        $result_stmt->execute([$templateid]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function saveJobEntry(
        $prod,
        $customer_name,
        $jonumber,
        $job_description,
        $orderid,
        $template_id,
        $job_quantity,
        $outs_no,
        $card_type,
        $equiv_sheets,
        $date_receive,
        $delivery_date,
        $start_transfer_date,
        $end_transfer_date,
        $job_filename,
        $job_priority,
        $job_status,
        $job_hold
    ) {
        $itemData_List = array();

        $chkExist = "SELECT job_filename FROM prod_job_entry_header WHERE job_filename = ?";
        $chkExist_stmt = $prod->prepare($chkExist);
        $chkExist_stmt->execute([$job_filename]);

        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['jobentryid'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO prod_job_entry_header(customer_name,jonumber,job_description,orderid,template_id,job_quantity,outs_no,card_type,equiv_sheets,date_receive,
                delivery_date,start_transfer_date,end_transfer_date,job_filename,job_priority,job_status,job_hold)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING jobentryid";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([
                $customer_name, $jonumber, $job_description, $orderid, $template_id,
                $job_quantity, $outs_no, $card_type, $equiv_sheets, $date_receive, $delivery_date, $start_transfer_date,
                $end_transfer_date, $job_filename, $job_priority, $job_status, $job_hold
            ]);
            $itemData_List['jobentryid'] = $prod->lastInsertId();
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function saveJobProcess($prod, $jobentry_id, $template_id, $job_priority, $process_status)
    {
        $sqlstring = "SELECT * FROM prod_template_process WHERE template_id = ? ORDER BY process_seq ASC";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$template_id]);
        foreach ($result_stmt->fetchAll() as $row) {
            $sql_process = "INSERT INTO prod_job_entry_details(jobentry_id,process_id,process_seq,process_status,process_priority) VALUES(?,?,?,?,?)";
            $process_stmt = $prod->prepare($sql_process);
            $process_stmt->execute([$jobentry_id, $row['process_id'], $row['process_seq'], $process_status, $job_priority]);
        }
        $prod = null; //* ======== Close Connection ========
    }

    public function loadJobEntryInfo($prod, $jobentryid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM prod_job_entry_header WHERE jobentryid = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$jobentryid]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['customer_name'] = $row['customer_name'];
            $itemData_List['jonumber'] = $row['jonumber'];
            $itemData_List['job_description'] = $row['job_description'];
            $itemData_List['orderid'] = $row['orderid'];
            $itemData_List['template_id'] = $row['template_id'];
            $itemData_List['job_quantity'] = $row['job_quantity'];
            $itemData_List['outs_no'] = $row['outs_no'];
            $itemData_List['card_type'] = $row['card_type'];
            $itemData_List['equiv_sheets'] = $row['equiv_sheets'];
            $itemData_List['date_receive'] = date_format(date_create($row['date_receive']), 'Y-m-d');
            $itemData_List['delivery_date'] = date_format(date_create($row['delivery_date']), 'Y-m-d');
            $itemData_List['start_transfer_date'] = date_format(date_create($row['start_transfer_date']), 'Y-m-d');
            $itemData_List['end_transfer_date'] = date_format(date_create($row['end_transfer_date']), 'Y-m-d');
            $itemData_List['job_hold'] = $row['job_hold'];
            $itemData_List['job_status'] = $row['job_status'];
            $itemData_List['job_filename'] = $row['job_filename'];
            $itemData_List['job_priority'] = $row['job_priority'];
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }
}
