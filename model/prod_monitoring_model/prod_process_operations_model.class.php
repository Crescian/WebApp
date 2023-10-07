<?php
date_default_timezone_set('Asia/Manila');

class ProdProcessOperations
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

    public function fetchData($prod, $process_section, $access_level)
    {
        $itemData_List = array();
        $sqlstring = "SELECT jobentry_id,process_id,process_priority,process_seq,customer_name,jonumber,job_description,job_filename,job_quantity,process_name,process_machine,start_date,end_date,
            instructions,start_transfer_date,process_status
            FROM prod_job_entry_header
            INNER JOIN prod_job_entry_details ON prod_job_entry_details.jobentry_id = prod_job_entry_header.jobentryid
            INNER JOIN prod_process_name ON prod_process_name.processid = prod_job_entry_details.process_id
            INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_process_name.section_id
            WHERE section_name = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            //* Fetch Process Sequence 
            $sqlSequence = "SELECT process_priority,jobentry_id,process_id,process_seq,process_status,job_filename
                FROM prod_job_entry_header
                INNER JOIN prod_job_entry_details ON prod_job_entry_details.jobentry_id = prod_job_entry_header.jobentryid
                INNER JOIN prod_process_name ON prod_process_name.processid = prod_job_entry_details.process_id
                INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_process_name.section_id
                WHERE job_filename = ? AND process_status <> 'Process Done'
                GROUP BY process_priority,jobentry_id,process_id,process_seq,process_status,job_filename
                ORDER BY process_priority,job_filename,process_seq ASC LIMIT 1";
            $sqlSequence_stmt = $prod->prepare($sqlSequence);
            $sqlSequence_stmt->execute([$row['job_filename']]);
            $sqlSequence_result = $sqlSequence_stmt->fetch(PDO::FETCH_ASSOC);
            $processSequence = $sqlSequence_result['process_seq'];
            $jobFilename = $sqlSequence_result['job_filename'];

            $nestedData = array();
            $nestedData[] = $row['process_priority'];
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['process_name'];
            $nestedData[] = $row['process_machine'] == '' ? '-' : $row['process_machine'];
            $nestedData[] = $row['start_date'] == '' ? '---- - -- - --' : date_format(date_create($row['start_date']), 'Y-m-d');
            $nestedData[] = $row['end_date'] == '' ? '---- - -- - --' : date_format(date_create($row['end_date']), 'Y-m-d');
            $nestedData[] = $row['instructions'] == '' ? '-' : $row['instructions'];
            $nestedData[] = date_format(date_create($row['start_transfer_date']), 'Y-m-d');
            $nestedData[] = self::processStatus($row['process_status']);
            $nestedData[] = array($row['jobentry_id'], $row["process_id"], $process_section, $access_level, $row["process_status"], $row["process_seq"], $processSequence, $row['job_filename'], $jobFilename);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function processJobStart($prod, $jobentry_id, $process_id)
    {
        $dateTimeStart = date('Y-m-d H:i:s');

        //* ======== UPDATE JOB ENTRY STATUS ========
        $job_status_sql = "UPDATE prod_job_entry_header SET job_status = 'On-Going' WHERE jobentryid = ?";
        $job_status_stmt = $prod->prepare($job_status_sql);
        $job_status_stmt->execute([$jobentry_id]);

        //* ======== UPDATE JOB PROCESS ========
        $result_sql = "UPDATE prod_job_entry_details SET date_time_start = ? ,process_status = 'On-Going' WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $prod->prepare($result_sql);
        $result_stmt->execute([$dateTimeStart, $jobentry_id, $process_id]);
        $prod = null; //* ======== Close Connection ========
    }

    public function processJobHold($prod, $jobentry_id, $process_id, $operator_remarks)
    {
        //* ======== UPDATE JOB ENTRY STATUS ========
        $job_status_sql = "UPDATE prod_job_entry_header SET job_status = 'Process Hold' WHERE jobentryid = ?";
        $job_status_stmt = $prod->prepare($job_status_sql);
        $job_status_stmt->execute([$jobentry_id]);

        //* ======== UPDATE JOB PROCESS ========
        $result_sql = "UPDATE prod_job_entry_details SET process_status = 'Process Hold', process_remarks = ? WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $prod->prepare($result_sql);
        $result_stmt->execute([$operator_remarks, $jobentry_id, $process_id]);
        $prod = null; //* ======== Close Connection ========
    }

    public function processJobResume($prod, $jobentry_id, $process_id)
    {
        //* ======== UPDATE JOB ENTRY STATUS ========
        $job_status_sql = "UPDATE prod_job_entry_header SET job_status = 'On-Going' WHERE jobentryid = ?";
        $job_status_stmt = $prod->prepare($job_status_sql);
        $job_status_stmt->execute([$jobentry_id]);

        //* ======== UPDATE JOB PROCESS ========
        $result_sql = "UPDATE prod_job_entry_details SET process_status = 'On-Going' WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $prod->prepare($result_sql);
        $result_stmt->execute([$jobentry_id, $process_id]);
        $prod = null; //* ======== Close Connection ========
    }
}
