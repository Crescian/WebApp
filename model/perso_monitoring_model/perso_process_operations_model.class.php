<?php
date_default_timezone_set('Asia/Manila');
class PersoProcessOperations
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

    public function loadProcessTableData($perso, $process_section, $job_category, $access_level, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'process_priority',
            1 => 'date_entry',
            2 => 'customer_name',
            3 => 'jonumber',
            4 => 'job_description',
            5 => 'job_filename',
            6 => 'process_name',
            7 => 'process_status',
            8 => 'date_time_start',
            9 => 'date_time_end',
            10 => 'job_quantity',
            11 => 'process_instructions',
            12 => 'release_date',
            13 => 'process_machine'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.release_date,JobEntry.jonumber,JobEntry.job_filename,job_quantity,JobProcess.process_id,
            ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobEntry.customer_name,JobEntry.job_description,JobProcess.process_machine,JobProcess.process_instructions
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id 
            WHERE ProcessList.process_section = ? AND JobProcess.process_status <> 'Done' AND JobProcess.process_status <> 'Process Done'";
        switch ($job_category) {
            case 'For Packing':
                $sqlstring .= " AND ProcessList.process_category = 'For Packing' ";
                break;
            case 'Persoline':
                $sqlstring .= " AND process_machine = 'Persoline' ";
                break;
            case 'Persomaster':
                $sqlstring .= " AND (process_machine ISNULL OR process_machine = 'Persomaster') ";
                break;
            default:
                $sqlstring .= " AND (ProcessList.process_category ISNULL OR ProcessList.process_category = '' OR ProcessList.process_category = 'For Kitting' OR ProcessList.process_category = 'For Print') ";
                break;
        }
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= "AND (TO_CHAR(date_entry, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%' OR job_filename ILIKE '%" . $searchValue . "%' OR job_description ILIKE '%" . $searchValue . "%' OR process_name ILIKE '%" . $searchValue . "%' OR process_status ILIKE '%" . $searchValue . "%' OR TO_CHAR(date_time_start, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' 
                OR TO_CHAR(date_time_end, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR CAST(job_quantity AS TEXT) ILIKE '%" . $searchValue . "%' OR process_instructions ILIKE '%" . $searchValue . "%' OR TO_CHAR(release_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR process_machine ILIKE '%" . $searchValue . "%') ";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute([$process_section]);
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= "GROUP BY JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.jonumber,JobEntry.job_filename,JobEntry.job_quantity,JobProcess.process_id,ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,
                JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobProcess.process_machine,JobProcess.process_instructions
                ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $fetch_seq_sql = "SELECT JobEntry.jobentryid,JobEntry.job_priority,JobEntry.job_filename,JobProcess.process_id,JobProcess.process_sequence,JobProcess.process_status
                FROM bpi_perso_job_entry JobEntry
                INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
                INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
                WHERE JobEntry.job_filename = ? AND JobProcess.process_status <> 'Process Done'
                GROUP BY JobEntry.jobentryid,JobEntry.job_priority,JobEntry.job_filename,JobProcess.process_id,JobProcess.process_sequence,JobProcess.process_status
                ORDER BY JobEntry.job_priority, JobEntry.job_filename, JobProcess.process_sequence ASC LIMIT 1";
            $sqlSequence_stmt = $perso->prepare($fetch_seq_sql);
            $sqlSequence_stmt->execute([$row['job_filename']]);
            foreach ($sqlSequence_stmt->fetchAll(PDO::FETCH_ASSOC) as $sqlSequence_res) {
                $processSequence = $sqlSequence_res['process_sequence'];
                $jobFilename = $sqlSequence_res['job_filename'];
            }

            $nestedData = array();
            $nestedData[] = $row['process_priority'];
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = $row['process_name'];
            $nestedData[] = self::processStatus($row['process_status']);
            $nestedData[] = $row['date_time_start'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_start']), 'H:i:s A');
            $nestedData[] = $row['date_time_end'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_end']), 'H:i:s A');
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['process_instructions'] == '' ? '-' : $row['process_instructions'];
            $nestedData[] = self::releaseDateColor($row['release_date']);
            if ($process_section == 'Inkjet Section' || $process_section == 'Persomaster/Persoline Section' || $process_section == 'HSA Kitting Section') {
                $nestedData[] = $row['process_machine'] == '' ? '-' : $row['process_machine'];
            }
            $nestedData[] = array($row["jobentryid"], $row["process_id"], $process_section, $access_level, $row["process_status"], $row["process_sequence"], $processSequence, $row['job_filename'], $jobFilename, $row['process_division']);
            $itemData_List[] = $nestedData;
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
            $nestedData[] = date_format(date_create($row['date_time_start']), 'Y-m-d h:i:s A');
            $nestedData[] = date_format(date_create($row['date_time_end']), 'Y-m-d h:i:s A');
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['job_remarks'] == '' ? '-' : $row['job_remarks'];
            $nestedData[] = self::releaseDateColor($row['release_date']);
            if ($process_section == 'Inkjet Section' || $process_section == 'Persomaster/Persoline Section' || $process_section == 'HSA Kitting Section') {
                $nestedData[] = $row['process_machine'] == '' ? '-' : $row['process_machine'];
            }
            $nestedData[] = $row['operator_remarks'];
            $nestedData[] = array($row['jobentryid'], $row['process_id']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadVaultTableData($perso, $process_section, $access_level, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'date_entry',
            1 => 'customer_name',
            2 => 'jonumber',
            3 => 'job_description',
            4 => 'job_filename',
            5 => 'job_quantity',
            6 => 'release_date',
            7 => 'process_status',
            8 => 'mode_delivery'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_id,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.customer_name,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,
            JobEntry.jonumber,JobEntry.orderid,JobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,ProcessList.process_division
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_section = ? AND JobProcess.process_status <> 'Done' AND JobProcess.process_status <> 'Process Done' ";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= "AND (TO_CHAR(date_entry, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%' OR job_description ILIKE '%" . $searchValue . "%' OR job_filename ILIKE '%" . $searchValue . "%' 
                OR CAST(job_quantity AS TEXT) ILIKE '%" . $searchValue . "%' OR TO_CHAR(release_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR process_status ILIKE '%" . $searchValue . "%' OR mode_delivery ILIKE '%" . $searchValue . "%') ";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute([$process_section]);
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= "GROUP BY JobEntry.jobentryid,JobProcess.process_id,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.customer_name,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,
            JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,ProcessList.process_division
            ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $fetch_seq_sql = "SELECT JobEntry.jobentryid,JobEntry.job_priority,JobEntry.job_filename,JobProcess.process_id,JobProcess.process_sequence,JobProcess.process_status
                FROM bpi_perso_job_entry JobEntry
                INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
                INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
                WHERE JobEntry.job_filename = ? AND JobProcess.process_status <> 'Process Done'
                GROUP BY JobEntry.jobentryid,JobEntry.job_priority,JobEntry.job_filename,JobProcess.process_id,JobProcess.process_sequence,JobProcess.process_status
                ORDER BY JobEntry.job_priority, JobEntry.job_filename, JobProcess.process_sequence ASC LIMIT 1";
            $sqlSequence_stmt = $perso->prepare($fetch_seq_sql);
            $sqlSequence_stmt->execute([$row['job_filename']]);
            $sqlSequence_res = $sqlSequence_stmt->fetch(PDO::FETCH_ASSOC);
            $processSequence = $sqlSequence_res['process_sequence'];
            $jobFilename = $sqlSequence_res['job_filename'];

            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['release_date'] == '' ? '-' : self::releaseDateColor($row['release_date']);
            $nestedData[] = array($row['jobentryid'], $row['process_id'], $row['orderid'], $row['process_status'], $process_section, $row['process_sequence'], $processSequence, $row['job_filename'], $jobFilename, $row['customer_name'], $row['jonumber'], $row['job_description'], $access_level);
            $nestedData[] = $row['mode_delivery'];
            $nestedData[] = array($row['jobentryid'], $row['process_id']);
            $itemData_List[] = $nestedData;
            //TODO ======== Fetch DR Number to update job entry ========
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
        $perso = null; //* ======== Close Connection ========
    }

    public function loadVaultTableDataDone($perso, $process_section, $mode_delivery)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_id,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.customer_name,JobEntry.date_entry,JobEntry.release_date,
            JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,JobProcess.operator_remarks,ProcessList.process_division,JobEntry.servicereportno
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id 
            WHERE ProcessList.process_section = ? AND JobProcess.process_status = 'Process Done'";
        if ($mode_delivery == 'Pick up') {
            $sqlstring .= " AND mode_delivery = 'Pick up' ";
        } else {
            $sqlstring .= " AND mode_delivery = 'Delivery' ";
        }
        $sqlstring .= "GROUP BY JobEntry.jobentryid,JobProcess.process_id,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.customer_name,JobEntry.date_entry,JobEntry.release_date,
            JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,JobProcess.operator_remarks,ProcessList.process_division,JobEntry.servicereportno
            ORDER BY JobEntry.release_date,JobEntry.customer_name,JobEntry.jonumber,JobEntry.job_filename ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $service_report_no = $row['servicereportno'] == '' ? '-' : $row['servicereportno'];
            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['job_remarks'];
            $nestedData[] = $row['release_date'] == '' ? '-' : self::releaseDateColor($row['release_date']);
            $nestedData[] = $row['dr_number'] == '' ? '-' : $row['dr_number'];;
            $nestedData[] = $row['mode_delivery'];
            $nestedData[] = $row['operator_remarks'];
            if ($mode_delivery == 'Delivery') {
                $nestedData[] = $service_report_no;
            }
            $nestedData[] = array($row['jobentryid'], $row['process_id'], $service_report_no, $row['customer_name']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadDispatchingTableData($perso, $cms_data, $process_section, $job_category, $access_level, $searchValue)
    {
        $itemData_List = array();
        $currentDate = date('Y-m-d');
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'release_date',
            1 => 'jonumber',
            2 => 'customer_name',
            3 => 'job_description',
            4 => 'job_filename',
            5 => 'job_quantity',
            6 => 'date_entry',
            7 => 'dr_number',
            8 => 'mode_delivery',
            9 => 'pickup_courier'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_id,JobEntry.customer_name,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,
            JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,ProcessList.process_division,JobEntry.pickup_courier
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_section = ? AND JobProcess.process_status <> 'Done' ";
        if ($job_category == 'default') {
            $sqlstring .= " AND release_date >= ? ";
        } else {
            $sqlstring .= " AND release_date < ? ";
        }
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section, $currentDate]);
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= "AND (TO_CHAR(date_entry, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%' OR job_description ILIKE '%" . $searchValue . "%' OR job_filename ILIKE '%" . $searchValue . "%' 
                OR CAST(job_quantity AS TEXT) ILIKE '%" . $searchValue . "%' OR job_remarks ILIKE '%" . $searchValue . "%' OR TO_CHAR(release_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR dr_number ILIKE '%" . $searchValue . "%' OR mode_delivery ILIKE '%" . $searchValue . "%') ";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute([$process_section, $currentDate]);
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $sqlstring .= "GROUP BY JobEntry.jobentryid,JobProcess.process_id,JobEntry.customer_name,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,
            JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,ProcessList.process_division,JobEntry.pickup_courier
            ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section, $currentDate]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $fetch_seq_sql = "SELECT JobEntry.jobentryid,JobEntry.job_priority,JobEntry.job_filename,JobProcess.process_id,JobProcess.process_sequence,JobProcess.process_status
                FROM bpi_perso_job_entry JobEntry
                INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
                INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
                WHERE JobEntry.job_filename = ? AND JobProcess.process_status <> 'Process Done'
                GROUP BY JobEntry.jobentryid,JobEntry.job_priority,JobEntry.job_filename,JobProcess.process_id,JobProcess.process_sequence,JobProcess.process_status
                ORDER BY JobEntry.job_priority, JobEntry.job_filename, JobProcess.process_sequence ASC LIMIT 1";
            $sqlSequence_stmt = $perso->prepare($fetch_seq_sql);
            $sqlSequence_stmt->execute([$row['job_filename']]);
            foreach ($sqlSequence_stmt->fetchAll(PDO::FETCH_ASSOC) as $sqlSequence_res) {
                $processSequence = $sqlSequence_res['process_sequence'];
                $jobFilename = $sqlSequence_res['job_filename'];
            }

            //* ======== Fetch Courier Status if Arrived ========
            //TODO Fetch Authorize Name then compare to arrived courier, loop if has multiple authorize in a company
            if ($row['mode_delivery'] == 'Pick up') {
                $chkCourier_sql = "SELECT * FROM cms_visitorlog WHERE companyname = ? AND courier = ? AND datetimein IS NOT NULL AND datetimeout ISNULL AND REPLACE(REPLACE(LOWER(purpose),'-',' '),' ','') ILIKE '%Pickup%'";
                $chkCourier_stmt = $cms_data->prepare($chkCourier_sql);
                $chkCourier_stmt->execute([$row['customer_name'], $row['pickup_courier']]);
                if ($chkCourier_stmt->rowCount() > 0) {
                    $courierStatus = '<span class="badge bg-success col-sm-12">Arrived</span>';
                } else {
                    $courierStatus = '<span class="badge bg-danger col-sm-12">Waiting Arrival</span>';
                }
            } else {
                $courierStatus = '<span class="badge bg-info col-sm-12">Delivery</span>';
            }

            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['pickup_courier'];
            $nestedData[] = $courierStatus;
            $nestedData[] = $row['release_date'] == '' ? '-' : self::releaseDateColor($row['release_date']);
            $nestedData[] = $row['dr_number'] == '' ? '-' : $row['dr_number'];
            $nestedData[] = array($row['jobentryid'], $row['process_id'], $process_section, $row['process_status'], $row['process_sequence'], $processSequence, $row['job_filename'], $jobFilename, $access_level, $row['process_division']);
            $nestedData[] = $row['mode_delivery'];
            $itemData_List[] = $nestedData;
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
        $perso = null; //* ======== Close Connection ========
        $cms_data = null; //* ======== Close Connection ========
    }

    public function loadDispatchingDRTableData($perso)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_dr_assigned_list ORDER BY drnumber ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $dataTimePrep = $row['date_time_prep'] == '' ? '-' : $row['date_time_prep'];
            $received_by = $row['received_by'] == '' ? '-' : $row['received_by'];
            $nestedData = array();
            $nestedData[] = $dataTimePrep;
            $nestedData[] = $row['drnumber'] == '' ? '-' : $row['drnumber'];
            $nestedData[] = $row['customer_name'] == '' ? '-' : $row['customer_name'];
            $nestedData[] = $row['jonumber'] == '' ? '-' : $row['jonumber'];
            $nestedData[] = $row['job_description'] == '' ? '-' : $row['job_description'];
            $nestedData[] = $row['signed'] == false ? '<span class="badge bg-danger col-sm-12 fs-14">To Sign</span>' : '<span class="badge bg-success col-sm-12 fs-14">Signed</span>';
            $nestedData[] = array($received_by, $dataTimePrep, $row['drassignid']);
            $nestedData[] = $row['date_received'] == '' ? '- - -' : date_format(date_create($row['date_received']), 'Y-m-d');
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadDispatchingTableDataDone($perso, $process_section)
    {
        $itemData_List = array();
        $currentDate = date('Y-m-d');

        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_id,JobEntry.customer_name,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,
            JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,ProcessList.process_division,JobProcess.operator_remarks
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_section = ? AND JobProcess.process_status = 'Done' AND JobEntry.release_date = ?
            GROUP BY JobEntry.jobentryid,JobProcess.process_id,JobEntry.customer_name,JobEntry.job_priority,JobProcess.process_sequence,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,
                JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number,ProcessList.process_division,JobProcess.operator_remarks
            ORDER BY JobEntry.release_date,JobEntry.customer_name,JobEntry.jonumber,JobEntry.job_filename ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section, $currentDate]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['job_remarks'];
            $nestedData[] = $row['mode_delivery'];
            $nestedData[] = self::releaseDateColor($row['release_date']);
            $nestedData[] = $row['dr_number'] == '' ? '-' : $row['dr_number'];
            $nestedData[] = $row['operator_remarks'];
            $nestedData[] = array($row["jobentryid"], $row["process_id"], $process_section, $row['process_sequence']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadMaterialTableData($perso, $material_section, $access_level, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'date_entry',
            1 => 'customer_name',
            2 => 'jonumber',
            3 => 'job_description',
            4 => 'job_filename',
            5 => 'job_quantity',
            6 => 'material_name',
            7 => 'job_remarks',
            8 => 'material_status'
        );
        //* ======== Fetch Data ========
        $slqstring = "SELECT JobEntry.jobentryid,JobEntry.date_entry,JobEntry.customer_name,JobEntry.jonumber,JobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobMaterial.material_id,MaterialList.material_name,
            JobMaterial.material_status,MaterialList.material_section
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_material JobMaterial ON JobMaterial.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_material_list MaterialList ON MaterialList.materialid = JobMaterial.material_id
            WHERE MaterialList.material_section = ? AND JobMaterial.material_status <> 'Done' AND JobMaterial.material_status <> 'Process Done' ";
        $result_stmt = $perso->prepare($slqstring);
        $result_stmt->execute([$material_section]);
        $result_total_record = $result_stmt->rowCount();
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $slqstring .= "AND (TO_CHAR(date_entry, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%' OR job_description ILIKE '%" . $searchValue . "%' OR job_filename ILIKE '%" . $searchValue . "%' 
                OR CAST(job_quantity AS TEXT) ILIKE '%" . $searchValue . "%' OR material_name ILIKE '%" . $searchValue . "%' OR material_status ILIKE '%" . $searchValue . "%') ";
            $result_stmt = $perso->prepare($slqstring);
            $result_stmt->execute([$material_section]);
        }
        $result_total_record_filtered = $result_stmt->rowCount();
        //* ======== Ordering ========
        $slqstring .= "ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
        $result_stmt = $perso->prepare($slqstring);
        $result_stmt->execute([$material_section]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['material_name'];
            $nestedData[] = array($row["jobentryid"], $row["material_id"], $material_section, $row['material_status'], $access_level);
            $itemData_List[] = $nestedData;
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
        $perso = null; //* ======== Close Connection ========
    }

    public function loadMaterialTableDataDone($perso, $material_section)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,JobEntry.date_entry,JobEntry.customer_name,JobEntry.jonumber,JobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,MaterialList.material_name,JobMaterial.operator_remarks,JobMaterial.material_id,
            JobMaterial.date_time_end,MaterialList.material_section
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_material JobMaterial ON JobMaterial.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_material_list MaterialList ON MaterialList.materialid = JobMaterial.material_id
            WHERE MaterialList.material_section = ? AND JobMaterial.material_status = 'Process Done'";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$material_section]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            //* ======== Fetch Operator Name ========
            $result_opt_sql = "SELECT material_operator FROM bpi_perso_job_material_operator WHERE jobentry_id = ? AND material_id = ?";
            $result_opt_stmt = $perso->prepare($result_opt_sql);
            $result_opt_stmt->execute([$row['jobentryid'], $row['material_id']]);
            while ($result_opt_row = $result_opt_stmt->fetch(PDO::FETCH_ASSOC)) {
                $strOperatorName = $result_opt_row['material_operator'];
            }
            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['material_name'];
            $nestedData[] = $row['operator_remarks'];
            $nestedData[] = $strOperatorName;
            $nestedData[] = date_format(date_create($row['date_time_end']), 'Y-m-d');
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function saveMaterialStart($perso, $jobentryid, $materialid, $material_section, $material_operator_remarks, $emp_name)
    {
        $dateDoneTime = date('Y-m-d H:i:s');
        //* ======== Insert Operator Name ========
        $result_opt_sql = "INSERT INTO bpi_perso_job_material_operator(jobentry_id,material_id,material_operator,job_category,material_section) 
            VALUES(?,?,?,'Job Entry',?)";
        $result_opt_stmt = $perso->prepare($result_opt_sql);
        $result_opt_stmt->execute([$jobentryid, $materialid, $emp_name, $material_section]);

        //* ======== Update Material ========
        $sqlstring = "UPDATE bpi_perso_job_material SET date_time_end = ?, operator_remarks = ?, material_status = 'Process Done'
            WHERE jobentry_id = ? AND material_id = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$dateDoneTime, $material_operator_remarks, $jobentryid, $materialid]);
        $perso = null; //* ======== Close Connection ========
    }

    public function processJobStart($perso, $jobentry_id, $process_id, $category)
    {
        $dateTimeStart = date('Y-m-d H:i:s');
        //* ======== UPDATE JOB ENTRY STATUS ========
        $job_status_sql = "UPDATE bpi_perso_job_entry SET job_status = 'On-Going' WHERE jobentryid = ?";
        $job_status_stmt = $perso->prepare($job_status_sql);
        $job_status_stmt->execute([$jobentry_id]);
        //* ======== UPDATE JOB PROCESS ========
        switch ($category) {
            case 'resume':
                $sqlstring = "UPDATE bpi_perso_job_process SET process_status = 'On-Going', operator_remarks = NULL WHERE jobentry_id = ? AND process_id = ?";
                $result_stmt = $perso->prepare($sqlstring);
                $result_stmt->execute([$jobentry_id, $process_id]);
                break;
            default:
                $sqlstring = "UPDATE bpi_perso_job_process SET date_time_start = ? ,process_status = 'On-Going' WHERE jobentry_id = ? AND process_id = ?";
                $result_stmt = $perso->prepare($sqlstring);
                $result_stmt->execute([$dateTimeStart, $jobentry_id, $process_id]);
        }
        $perso = null; //* ======== Close Connection ========
    }

    public function processJobHold($perso, $jobentry_id, $process_id, $operator_remarks)
    {
        //* ======== UPDATE JOB ENTRY STATUS ========
        $job_status_sql = "UPDATE bpi_perso_job_entry SET job_status = 'Process Hold' WHERE jobentryid = ?";
        $job_status_stmt = $perso->prepare($job_status_sql);
        $job_status_stmt->execute([$jobentry_id]);
        //* ======== UPDATE JOB PROCESS ========
        $sqlstring = "UPDATE bpi_perso_job_process SET process_status = 'Process Hold' ,operator_remarks = ? WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$operator_remarks, $jobentry_id, $process_id]);
        $perso = null; //* ======== Close Connection ========
    }

    public function processJobDone($perso, $jobentry_id, $process_id, $sequence_number, $operator_remarks)
    {
        $dateTimeEnd = date('Y-m-d H:i:s');
        $released_date = date('Y-m-d');

        //* ======== Check Total Sequence of Process ========
        $result_chk_sql = "SELECT * FROM bpi_perso_job_process WHERE jobentry_id = ?";
        $result_chk_stmt = $perso->prepare($result_chk_sql);
        $result_chk_stmt->execute([$jobentry_id]);

        if ($result_chk_stmt->rowCount() == $sequence_number) {
            //* ======== Update Job Entry Status And Update Release Date ========
            $result_job_update_sql = "UPDATE bpi_perso_job_entry SET job_status = 'Done', release_date = ? WHERE jobentryid = ?";
            $result_job_update_stmt = $perso->prepare($result_job_update_sql);
            $result_job_update_stmt->execute([$released_date, $jobentry_id]);

            //* ======== Update Material Status ========
            $result_material_sql = "UPDATE bpi_perso_job_material SET material_status = 'Done' WHERE jobentry_id = ?";
            $result_material_stmt = $perso->prepare($result_material_sql);
            $result_material_stmt->execute([$jobentry_id]);

            //* ======== Update Process ========
            $sqlstring = "UPDATE bpi_perso_job_process SET date_time_end = ?, operator_remarks = ?, process_status = 'Done' WHERE jobentry_id = ? AND process_id = ?";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute([$dateTimeEnd, $operator_remarks, $jobentry_id, $process_id]);

            //* ======== Update All Process to Done ========
            $result_update_process_sql = "UPDATE bpi_perso_job_process SET process_status = 'Done' WHERE jobentry_id = ?";
            $result_update_process_stmt = $perso->prepare($result_update_process_sql);
            $result_update_process_stmt->execute([$jobentry_id]);
        } else {
            //* ======== Update Process ========
            $sqlstring = "UPDATE bpi_perso_job_process SET date_time_end = ?, operator_remarks = ?, process_status = 'Process Done' WHERE jobentry_id = ? AND process_id = ?";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute([$dateTimeEnd, $operator_remarks, $jobentry_id, $process_id]);
        }
        $perso = null; //* ======== Close Connection ========
    }

    public function saveProcessOperator($perso, $jobentry_id, $process_id, $process_section, $job_category, $process_operator)
    {
        $sqlstring = "INSERT INTO bpi_perso_job_process_operator(jobentry_id,process_id,process_operator,job_category,process_section) VALUES(?,?,?,?,?)";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentry_id, $process_id, $process_operator, $job_category, $process_section]);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobProcessOperator($perso, $jobentry_id, $process_id, $processSequence, $jobCategory)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_job_process_operator WHERE jobentry_id = ? AND process_id = ? AND job_category = ? AND partial_sequence = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentry_id, $process_id, $jobCategory, $processSequence]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadProcessTimelineTableData($perso, $process_division, $jobentry_id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,ProcessList.processid,JobProcess.process_sequence,ProcessList.process_name,JobEntry.job_quantity,JobProcess.process_status
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_division = ? AND jobentryid = ?
            GROUP BY  JobEntry.jobentryid,ProcessList.processid,JobProcess.process_sequence,ProcessList.process_name,JobEntry.job_quantity,JobProcess.process_status
            ORDER BY JobEntry.jobentryid,JobProcess.process_sequence ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_division, $jobentry_id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobProcessInfo($perso, $jobentry_id, $process_id)
    {
        $itemData_List = array();
        $result_sql = "SELECT job_filename,operator_remarks,process_status,job_quantity FROM bpi_perso_job_process
                INNER JOIN bpi_perso_job_entry ON bpi_perso_job_entry.jobentryid = bpi_perso_job_process.jobentry_id 
                WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $perso->prepare($result_sql);
        $result_stmt->execute([$jobentry_id, $process_id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['job_filename'] = $row['job_filename'];
            $itemData_List['operator_remarks'] = $row['operator_remarks'];
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadServiceReportNo($cms, $customer_name)
    {
        $itemData_List = array();
        $sqlstring = "SELECT serviceno,companyname FROM tblservicereport WHERE companyname = ? ORDER BY serviceid DESC";
        $result_stmt = $cms->prepare($sqlstring);
        $result_stmt->execute([$customer_name]);
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['serviceno']] = $row['serviceno'];
            }
        } else {
            $itemData_List['serviceno'] = 'empty';
        }
        return json_encode($itemData_List);
        $cms = null; //* ======== Close Connection ========
    }

    public function saveServiceReportNo($perso, $preparedby, $serviceno, $jobentry_id)
    {
        $sqlstring = "UPDATE bpi_perso_job_entry SET servicereportno = ?, servicereportno_preparedby = ? WHERE jobentryid = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$serviceno, $preparedby, $jobentry_id]);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadDrNumber($bannerData, $orderid, $jonumber)
    {
        $itemData_List = array();
        //* ======== per_deliveredcard ========
        $sqldelivered = "SELECT DISTINCT drnumber FROM per_deliveredcard WHERE orderid = ? ORDER BY drnumber DESC";
        $sqldelivered_stmt = $bannerData->prepare($sqldelivered);
        $sqldelivered_stmt->execute([$orderid]);
        if ($sqldelivered_stmt->rowCount() > 0) {
            foreach ($sqldelivered_stmt->fetchAll() as $row) {
                $itemData_List[$row['drnumber']] = $row['drnumber'];
            }
        }
        //* ======== tbleb_tocustomer ========
        $sqlcustomer = "SELECT DISTINCT drnumber FROM tbleb_tocustomer WHERE orderid = ? ORDER BY drnumber DESC";
        $sqlcustomer_stmt = $bannerData->prepare($sqlcustomer);
        $sqlcustomer_stmt->execute([$orderid]);
        if ($sqlcustomer_stmt->rowCount() > 0) {
            foreach ($sqlcustomer_stmt->fetchAll() as $row) {
                $itemData_List[$row['drnumber']] = $row['drnumber'];
            }
        }
        //* ======== tblper_deliveryacceptancehead ========
        $sqlstring = "SELECT DISTINCT controlno FROM tblper_deliveryacceptancehead WHERE jonumber = ?";
        $sqlstring_stmt = $bannerData->prepare($sqlstring);
        $sqlstring_stmt->execute([$jonumber]);
        if ($sqlstring_stmt->rowCount() > 0) {
            foreach ($sqlstring_stmt->fetchAll() as $row) {
                $itemData_List[$row['controlno']] = $row['controlno'];
            }
        }
        //* ======== tbleb_deliveryacceptancehead ========
        $sqlstring_ar = "SELECT DISTINCT controlno FROM tbleb_deliveryacceptancehead WHERE jonumber = ?";
        $sqlstring_ar_stmt = $bannerData->prepare($sqlstring_ar);
        $sqlstring_ar_stmt->execute([$jonumber]);
        if ($sqlstring_ar_stmt->rowCount() > 0) {
            foreach ($sqlstring_ar_stmt->fetchAll() as $row) {
                $itemData_List[$row['controlno']] = $row['controlno'];
            }
        }
        return json_encode($itemData_List);
        $bannerData = null; //* ======== Close Connection ========
    }

    public function saveDrAssigned($perso, $drnumber, $customerName, $jonumber, $jobDescription)
    {
        $dateTimePrep = date('Y-m-d H:i:s');

        $sqlstring = "UPDATE bpi_perso_dr_assigned_list SET date_time_prep = ?, customer_name = ? ,jonumber = ? ,job_description = ? WHERE drnumber = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$dateTimePrep, $customerName, $jonumber, $jobDescription, $drnumber]);

        $result_dr_save_sql = "SELECT DISTINCT drnumber FROM bpi_perso_dr_assigned_list ORDER BY drnumber DESC LIMIT 1";
        $result_dr_save_stmt = $perso->prepare($result_dr_save_sql);
        $result_dr_save_stmt->execute();
        $result_dr_save_row = $result_dr_save_stmt->fetch(PDO::FETCH_ASSOC);

        $currCount = substr($result_dr_save_row['drnumber'], 2, 10);
        $counter = intval($currCount) + 1;
        $drnumber_new = 'DR' . str_pad($counter, 10, '0', STR_PAD_LEFT);

        $save_dr_sql = "INSERT INTO bpi_perso_dr_assigned_list(drnumber) VALUES(?)";
        $save_dr_stmt = $perso->prepare($save_dr_sql);
        $save_dr_stmt->execute([$drnumber_new]);
        $perso = null; //* ======== Close Connection ========
    }

    public function saveJobProcessDR($perso, $jobentryid, $remarks, $drnumber, $processid, $processSequence)
    {
        $dateTimeStartEnd = date('Y-m-d H:i:s');
        //* ======== Check Total Sequence of Process ========
        $chkSequence_sql = "SELECT * FROM bpi_perso_job_process WHERE jobentry_id = ?";
        $chkSequence_sql_stmt = $perso->prepare($chkSequence_sql);
        $chkSequence_sql_stmt->execute([$jobentryid]);
        if ($chkSequence_sql_stmt->rowCount() == $processSequence) {
            //* ======== Update Job Entry Status ========
            $result_job_update_sql = "UPDATE bpi_perso_job_entry SET job_status = 'Done' WHERE jobentryid = ?";
            $result_job_update_stmt = $perso->prepare($result_job_update_sql);
            $result_job_update_stmt->execute([$jobentryid]);
            //* ======== Update Material Status ========
            $result_material_sql = "UPDATE bpi_perso_job_material SET material_status = 'Done' WHERE jobentry_id = ?";
            $result_material_stmt = $perso->prepare($result_material_sql);
            $result_material_stmt->execute([$jobentryid]);
            //* ======== Update all Process to done  ========
            $result_update_process_sql = "UPDATE bpi_perso_job_process SET process_status = 'Done' WHERE jobentry_id = ?";
            $result_update_process_stmt = $perso->prepare($result_update_process_sql);
            $result_update_process_stmt->execute([$jobentryid]);

            $processStatus = 'Done';
        } else {
            $processStatus = 'Process Done';
        }

        $result_process_sql = "UPDATE bpi_perso_job_process SET process_status = ?, operator_remarks = ?, date_time_start = ?, date_time_end = ? WHERE jobentry_id = ? AND process_id = ?";
        $result_process_stmt = $perso->prepare($result_process_sql);
        $result_process_stmt->execute([$processStatus, $remarks, $dateTimeStartEnd, $dateTimeStartEnd, $jobentryid, $processid]);

        //* ======== Update Job Entry DR Number ========
        $sqlstring = "UPDATE bpi_perso_job_entry SET dr_number = ? WHERE jobentryid = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$drnumber, $jobentryid]);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadVerifyCourierInfo($perso, $cms_data, $jobentryid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT customer_name,pickup_courier,job_filename,dr_number,mode_delivery FROM bpi_perso_job_entry WHERE jobentryid = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentryid]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            //* ======== Fetch Courier if Arrived ========
            $sqlstring_courier = "SELECT name FROM cms_visitorlog WHERE companyname = ? AND courier = ? AND datetimein IS NOT NULL AND datetimeout ISNULL AND REPLACE(REPLACE(LOWER(purpose),'-',' '),' ','') ILIKE '%Pickup%'";
            $result_courier_stmt = $cms_data->prepare($sqlstring_courier);
            $result_courier_stmt->execute([$row['customer_name'], $row['pickup_courier']]);
            if ($result_courier_stmt->rowCount() > 0) {
                while ($row_courier_name = $result_courier_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nestedData = array();
                    $nestedData[] = $row['customer_name'];
                    $nestedData[] = $row['mode_delivery'] == 'Delivery' ? '-' : $row['pickup_courier'];
                    $nestedData[] = $row['job_filename'];
                    $nestedData[] = $row['mode_delivery'];
                    $nestedData[] = $row['dr_number'] == '' ? '-' : $row['dr_number'];
                    $nestedData[] = 'Arrived';
                    //* ======== Fetch Authorized Courier Image ========
                    $sqlstring_image = "SELECT encode(visitor_image, 'escape') AS visitor_image FROM cms_visitorlog_images WHERE company_name = ? AND courier = ? AND visitor_name = ? AND date_time_in = ?";
                    $result_stmt_image = $cms_data->prepare($sqlstring_image);
                    $result_stmt_image->execute([$row['customer_name'], $row['pickup_courier'], $row_courier_name['name'], date('Y-m-d')]);
                    while ($row_image = $result_stmt_image->fetch(PDO::FETCH_ASSOC)) {
                        $nestedData[] = 'src="data:image/jpeg;base64,' . $row_image['visitor_image'] . '" value="' . $row_image['visitor_image'] . '"';
                    }
                    $itemData_List[$row_courier_name['name']][] = $nestedData;
                }
            } else {
                $nestedData = array();
                $nestedData[] = $row['customer_name'];
                $nestedData[] = $row['mode_delivery'] == 'Delivery' ? '-' : $row['pickup_courier'];
                $nestedData[] = $row['job_filename'];
                $nestedData[] = $row['mode_delivery'];
                $nestedData[] = $row['dr_number'] == '' ? '-' : $row['dr_number'];
                $nestedData[] = 'Waiting Arrival';
                $nestedData[] = 'src="../vendor/images/blank-profile-picture.png"';
                $itemData_List['-'][] = $nestedData;
            }
        }
        return json_encode($itemData_List);
        $cms_data = null; //* ======== Close Connection ========
        $perso = null; //* ======== Close Connection ========
    }

    public function saveDrAssignedBy($perso, $drassignid, $dr_assign_received_by)
    {
        $dateReceived = date('Y-m-d h:i:s');

        $sqlstring = "UPDATE bpi_perso_dr_assigned_list SET received_by = ? , date_received = ?  WHERE drassignid = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$dr_assign_received_by, $dateReceived, $drassignid]);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobProcessDispatchInfo($perso, $jobentryid, $processid, $process_section, $processSequence)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_id,JobProcess.process_sequence,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,JobEntry.job_quantity,JobProcess.process_status,JobEntry.mode_delivery,
            JobEntry.dr_number,JobProcess.date_time_start,JobProcess.date_time_end,JobProcess.operator_remarks,JobEntry.pickup_courier
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_section = ? AND JobEntry.jobentryid = ? AND JobProcess.process_id = ? AND JobProcess.process_sequence = ?
            GROUP BY JobEntry.jobentryid,JobProcess.process_id,JobProcess.process_sequence,JobEntry.date_entry,JobEntry.release_date,JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,JobEntry.job_quantity,JobProcess.process_status,JobEntry.mode_delivery,
            JobEntry.dr_number,JobProcess.date_time_start,JobProcess.date_time_end,JobProcess.operator_remarks,JobEntry.pickup_courier";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$process_section, $jobentryid, $processid, $processSequence]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['customer_name'] = $row['customer_name'];
            $itemData_List['jonumber'] = $row['jonumber'];
            $itemData_List['job_description'] = $row['job_description'];
            $itemData_List['pickup_courier'] = $row['pickup_courier'] == '' ? '-' : $row['pickup_courier'];
            $itemData_List['date_entry'] = $row['date_entry'];
            $itemData_List['release_date'] = $row['release_date'];
            $itemData_List['date_time_start'] = $row['date_time_start'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_start']), 'h:i:s A');
            $itemData_List['date_time_end'] = $row['date_time_end'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_end']), 'h:i:s A');
            $itemData_List['dr_number'] = $row['dr_number'];
            $itemData_List['process_status'] = $row['process_status'];
            $itemData_List['operator_remarks'] = $row['operator_remarks'] == '' ? '-' : $row['operator_remarks'];
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadMaterialOperator($BannerWebLive, $empno)
    {
        $itemData_List = array();
        $sqlfetch = "SELECT empno,section_name,pos_code FROM prl_employee
            INNER JOIN bpi_assigned_section ON bpi_assigned_section.sec_job_title = prl_employee.pos_code
            INNER JOIN bpi_section_perso ON bpi_section_perso.sectionpersoid = bpi_assigned_section.sectionperso_id
            WHERE empno = ?";
        $sqlfetch_stmt = $BannerWebLive->prepare($sqlfetch);
        $sqlfetch_stmt->execute([$empno]);
        //* ======== Prepare Array ========
        while ($sqlfetch_row = $sqlfetch_stmt->fetch(PDO::FETCH_ASSOC)) {
            $section_name = $sqlfetch_row['section_name'];
        }

        $sqlstring = "SELECT empno,(emp_fn || ' ' || emp_sn) AS emp_name FROM prl_employee
            INNER JOIN bpi_assigned_section ON bpi_assigned_section.sec_job_title = prl_employee.pos_code
            INNER JOIN bpi_section_perso ON bpi_section_perso.sectionpersoid = bpi_assigned_section.sectionperso_id
            WHERE section_name = ? ORDER BY emp_name ASC";
        $result_stmt = $BannerWebLive->prepare($sqlstring);
        $result_stmt->execute([$section_name]);
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['emp_name']] = $row['emp_name'];
            }
        } else {
            $itemData_List['emp_name'] = 'empty';
        }
        return json_encode($itemData_List);
        $BannerWebLive = null; //* ======== Close Connection ========
    }
}
