<?php
class ProdProcessPlanner
{
    function processStatus($processStats)
    {
        switch ($processStats) {
            case 'Pending':
                $processStatus = '<span class="badge bg-warning col-sm-12">Pending</span>';
                break;
            case 'On-Going':
                $processStatus = '<span class="badge bg-success col-sm-12">On-Going</span>';
                break;
            case 'Process Done':
                $processStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Done':
                $processStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Hold':
                $processStatus = '<span class="badge bg-danger col-sm-12">On Hold</span>';
                break;
            case 'Process Hold':
                $processStatus = '<span class="badge bg-danger col-sm-12">Process Hold</span>';
                break;
        }
        return $processStatus;
    }
    public function fetchData($prod, $process_section)
    {
        $itemData_List = array();
        $sqlstring = "SELECT jobentry_id,process_id,process_priority,date_receive,customer_name,jonumber,job_description,job_quantity,process_name,process_machine,start_date,end_date,
            instructions,start_transfer_date,process_status
            FROM prod_job_entry_header
            INNER JOIN prod_job_entry_details ON prod_job_entry_details.jobentry_id = prod_job_entry_header.jobentryid
            INNER JOIN prod_process_name ON prod_process_name.processid = prod_job_entry_details.process_id
            INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_process_name.section_id
            WHERE section_name = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['process_priority'];
            $nestedData[] = date_format(date_create($row['date_receive']), 'Y-m-d');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['process_name'];
            $nestedData[] = $row['process_machine'] == '' ? '-' : $row['process_machine'];
            $nestedData[] = $row['start_date'] == '' ? '---- - -- - --' : date_format(date_create($row['start_date']), 'Y-m-d');
            $nestedData[] = $row['end_date'] == '' ? '---- - -- - --' : date_format(date_create($row['end_date']), 'Y-m-d');
            $nestedData[] = $row['instructions'] == '' ? '-' : $row['instructions'];
            $nestedData[] = date_format(date_create($row['start_transfer_date']), 'Y-m-d');
            $nestedData[] = self::processStatus($row['process_status']);
            $nestedData[] = '<input type="checkbox" class="rowChkBox">';
            $nestedData[] = $row['jobentry_id'];
            $nestedData[] = $row['process_id'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function fetchProcessMachine($prod, $process_section)
    {
        $itemData_List = array();
        $sqlstring = "SELECT section_name, machine_name FROM prod_section_assign
            INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_section_assign.section_id
            INNER JOIN prod_machine_name ON prod_machine_name.machineid = prod_section_assign.machine_id
            WHERE section_name = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row['machine_name'];
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function updateProcessPlanner($prod, $process_machine, $process_priority, $instructions, $start_date, $end_date,  $jobentry_id, $process_id)
    {
        $sqlstring = "UPDATE prod_job_entry_details SET process_machine = ?, process_priority = ?, instructions = ?, start_date = ?, end_date = ?
            WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$process_machine, $process_priority, $instructions, $start_date, $end_date, $jobentry_id, $process_id]);
        $prod = null; //* ======== Close Connection ========
    }
}
