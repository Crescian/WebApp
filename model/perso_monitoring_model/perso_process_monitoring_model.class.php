<?php
class PersoProcessPlanner
{
    public function processStatus($processStats)
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

    public function releaseDateColor($releaseDate)
    {
        $color = array(
            1 => 'date-color1', 'date-color2', 'date-color3', 'date-color4', 'date-color5', 'date-color6', 'date-color7', 'date-color8', 'date-color9', 'date-color10',
            'date-color11', 'date-color12', 'date-color13', 'date-color14', 'date-color15', 'date-color16', 'date-color17', 'date-color18', 'date-color19', 'date-color20',
            'date-color21', 'date-color22', 'date-color23', 'date-color24', 'date-color25', 'date-color26', 'date-color27', 'date-color28', 'date-color29', 'date-color30', 'date-color31'
        );
        $x = date_format(date_create($releaseDate), 'j');
        $dateRelease = '<span class="badge ' . $color[$x] . ' fs-16">' . $releaseDate . '</span>';
        return $dateRelease;
    }
    public function loadProcessTableData($perso, $process_section, $process_category)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.date_receive,JobEntry.job_cutoff,JobEntry.release_date,JobEntry.jonumber,JobEntry.job_filename,JobEntry.job_quantity,JobProcess.process_id,
            ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobEntry.customer_name,JobEntry.job_description,JobProcess.process_machine,JobProcess.operator_remarks,JobProcess.process_instructions
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id 
            WHERE ProcessList.process_section = ? AND JobProcess.process_status <> 'Done' AND JobProcess.process_status <> 'Process Done'";
        if ($process_category == 'default') {
            $sqlstring .= " AND (ProcessList.process_category ISNULL OR ProcessList.process_category = '' OR ProcessList.process_category = 'For Kitting' OR ProcessList.process_category = 'For Print')";
        } else if ($process_category == 'For Packing') {
            $sqlstring .= " AND ProcessList.process_category = 'For Packing'";
        } else if ($process_category == 'Persoline') {
            $sqlstring .= " AND process_machine = 'Persoline' ";
        } else {
            $sqlstring .= " AND (process_machine ISNULL OR process_machine = 'Persomaster') ";
        }
        $sqlstring .= "GROUP BY JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.jonumber,JobEntry.job_filename,JobEntry.job_quantity,JobProcess.process_id,ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,
            JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobProcess.process_machine,JobProcess.operator_remarks,JobProcess.process_instructions
            ORDER BY JobProcess.process_priority, JobEntry.job_filename, JobProcess.process_sequence ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['process_priority'];
            $nestedData[] = date_format(date_create($row['date_receive']), 'Y-m-d');
            $nestedData[] = $row['job_cutoff'] == 'Beyond Cut-Off' ? '<span class="badge bg-danger col-sm-12">Beyond Cut-Off</span>' : ($row['job_cutoff'] == 'Within Cut-Off' ? '<span class="badge bg-success col-sm-12">Within Cut-Off</span>' : '-');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = $row['process_name'];
            $nestedData[] = self::processStatus($row['process_status']); //* ======== Set Process Status ========
            $nestedData[] = $row['date_time_start'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_start']), 'H:i:s A');
            $nestedData[] = $row['date_time_end'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_end']), 'H:i:s A');
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['process_instructions'] == '' ? '-' : $row['process_instructions'];
            $nestedData[] = $row['release_date'] == '' ? '-' : self::releaseDateColor($row['release_date']); //* ======== Set Release Date Color ========;
            if ($process_section == 'Inkjet Section' || $process_section == 'Persomaster/Persoline Section' || $process_section == 'HSA Kitting Section') {
                $nestedData[] = $row['process_machine'] == '' ? '-' : $row['process_machine'];
            }
            $nestedData[] = $row['operator_remarks'];
            $nestedData[] = '<input type="checkbox" class="rowChkBox">';
            $nestedData[] = $row['jobentryid'];
            $nestedData[] = $row['process_id'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadProcessTableDataDone($perso, $process_section)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.release_date,JobEntry.jonumber,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_id,
            ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobEntry.customer_name,JobEntry.job_description,JobProcess.process_machine,JobProcess.operator_remarks
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_section = ? AND JobProcess.process_status = 'Process Done'
            GROUP BY JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.jonumber,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_id,ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,
                JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobEntry.customer_name,JobEntry.job_description,JobProcess.process_machine,JobProcess.operator_remarks
            ORDER BY JobProcess.process_priority, JobEntry.job_filename, JobProcess.process_sequence ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['process_priority'];
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = $row['process_name'];
            $nestedData[] = '<span class="badge bg-dark col-sm-12">Done</span>';
            $nestedData[] = date_format(date_create($row['date_time_start']), 'H:i:s A');
            $nestedData[] = date_format(date_create($row['date_time_end']), 'H:i:s A');
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['job_remarks'] == '' ? '-' : $row['job_remarks'];
            $nestedData[] = self::releaseDateColor($row['release_date']); //* ======== Set Release Date Color ========;
            if ($process_section == 'Inkjet Section' || $process_section == 'Persomaster/Persoline Section' || $process_section == 'Embossing/Datacard Section' || $process_section == 'HSA Kitting Section') {
                $nestedData[] = $row['process_machine'] == '' ? '-' : $row['process_machine'];
            }
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function updateProcessPlanner($perso, $jobentry_id, $process_id, $process_priority, $process_machine, $releaseDate, $process_instructions)
    {
        $priorityLevel = $process_priority == '0' ? '500' :  $process_priority; //* ======== Set Process Priority Level ========
        $processMachine = $process_machine == '' ? Null : $process_machine;
        $release_date = $releaseDate == '' ? Null : $releaseDate;

        $job_sql = "UPDATE bpi_perso_job_entry SET release_date = ? WHERE jobentryid = ?";
        $job_sql_stmt = $perso->prepare($job_sql);
        $job_sql_stmt->execute([$release_date, $jobentry_id]);

        $sqlstring = "UPDATE bpi_perso_job_process SET process_priority = ?, process_machine = ?, process_instructions = ? WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$priorityLevel, $processMachine, $process_instructions, $jobentry_id, $process_id]);
        $perso = null; //* ======== Close Connection ======== 
    }
}
