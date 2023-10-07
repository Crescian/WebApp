<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

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
            case 'With Replacement':
                $processStatus = '<span class="badge bg-secondary col-sm-12">w/ Replacement</span>';
                break;
            case 'Partial':
                $processStatus = '<span class="badge bg-info col-sm-12">Partial On-Going</span>';
                break;
            case 'Process Partial':
                $processStatus = '<span class="badge bg-info col-sm-12">Partial</span>';
                break;
        }
        return $processStatus;
    }

    function releaseDateColor($releaseDate)
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

    switch ($action) {
        case 'load_job_process_count':
            $processDivision = trim($_POST['processDivision']);
            $result_sql = "SELECT JobEntry.date_entry,JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number
                    FROM bpi_perso_job_entry JobEntry
                    INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
                    INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
                    WHERE ProcessList.process_division = :processDivision AND JobProcess.process_status <> 'Done' AND JobProcess.process_status <> 'Process Done'
                    GROUP BY JobEntry.date_entry,JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_status,JobEntry.mode_delivery,JobEntry.dr_number";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':processDivision', $processDivision);
            $result_stmt->execute();

            echo $result_stmt->rowCount();
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_released_count':
            $result_sql = "SELECT * FROM bpi_perso_job_entry 
                    INNER JOIN bpi_perso_job_process
                    ON bpi_perso_job_process.jobentry_id = bpi_perso_job_entry.jobentryid
                    WHERE job_status = 'Done' AND process_id = '27'";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->execute();

            echo $result_stmt->rowCount();
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_dispatch_ongoing_list':
            $sqlstring = "SELECT jonumber,job_filename,process_status FROM bpi_perso_job_entry JobEntry
                INNER JOIN bpi_perso_job_process JobProcess
                ON JobProcess.jobentry_id = JobEntry.jobentryid
                WHERE JobProcess.process_id = '27' AND JobProcess.process_status = 'On-Going'";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_res = $result_stmt->fetchAll();

            if ($result_stmt->rowCount() > 0) {
                foreach ($result_res as $row) {
                    echo '<li class="dispatch_ongoing_li">';
                    echo '<div class="row">';
                    echo '<div class="col-sm text-center dispatch_ongoing_jo">' . $row['jonumber'] . '</div>';
                    echo '<div class="col-sm text-center dispatch_ongoing_filename">' . $row['job_filename'] . '</div>';
                    echo '<div class="col-sm text-center dispatch_ongoing_status">Dispatching...</div>';
                    echo '</div>';
                    echo '</li>';
                }
            } else {
                echo '<li class="dispatch_ongoing_li">';
                echo '<div class="row">';
                echo '<div class="col-sm text-center dispatch_ongoing_jo fw-bold">Currently No Data to Show</div>';
                echo '</div>';
                echo '</li>';
            }
            $perso = null;
            break;

        case 'load_process_data_timeline':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $processDivision = trim($_POST['processDivision']);
            $resultData_List = array();

            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'process_priority',
                1 => 'date_receive',
                2 => 'customer_name',
                3 => 'jonumber',
                4 => 'job_description',
                5 => 'job_filename',
                6 => 'process_name',
                7 => 'job_quantity',
                8 => 'dr_number',
                9 => 'release_date',
                10 => 'process_status'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_priority,JobProcess.process_sequence,JobProcess.process_id,JobEntry.date_receive,JobEntry.customer_name,
                JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,ProcessList.process_name,JobEntry.job_quantity,JobEntry.release_date,JobProcess.process_status,JobEntry.dr_number
                FROM bpi_perso_job_entry JobEntry
                INNER JOIN bpi_perso_job_process JobProcess
                ON JobProcess.jobentry_id = JobEntry.jobentryid
                INNER JOIN bpi_perso_process_list ProcessList
                ON ProcessList.processid = JobProcess.process_id
                WHERE ProcessList.process_division = :processDivision AND JobProcess.process_status <> 'Done' 
                GROUP BY JobEntry.jobentryid,JobProcess.process_priority,JobProcess.process_sequence,JobProcess.process_id,JobEntry.date_receive,JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,
                JobEntry.job_filename,ProcessList.process_name,JobEntry.job_quantity,JobEntry.release_date,JobProcess.process_status
                ORDER BY release_date,customer_name,jonumber,job_filename ASC";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':processDivision', $processDivision);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();

            //* ======== Fetch Total Filtered Record Data ========
            $sqlstring = "SELECT JobEntry.jobentryid,JobProcess.process_priority,JobProcess.process_sequence,JobProcess.process_id,JobEntry.date_receive,JobEntry.customer_name,
                JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,ProcessList.process_name,JobEntry.job_quantity,JobEntry.release_date,JobProcess.process_status,JobEntry.dr_number
                FROM bpi_perso_job_entry JobEntry
                INNER JOIN bpi_perso_job_process JobProcess
                ON JobProcess.jobentry_id = JobEntry.jobentryid
                INNER JOIN bpi_perso_process_list ProcessList
                ON ProcessList.processid = JobProcess.process_id
                WHERE ProcessList.process_division = :processDivision AND JobProcess.process_status <> 'Done' ";
            if (!empty($searchValue)) {
                $sqlstring .= "AND (TO_CHAR(date_receive, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%' OR job_filename ILIKE '%" . $searchValue . "%' 
                    OR job_description ILIKE '%" . $searchValue . "%' OR process_name ILIKE '%" . $searchValue . "%' OR process_status ILIKE '%" . $searchValue . "%' OR TO_CHAR(date_time_start, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' 
                    OR CAST(job_quantity AS TEXT) ILIKE '%" . $searchValue . "%' OR TO_CHAR(release_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR dr_number ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':processDivision', $processDivision);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            //* ======== Ordering ========
            $sqlstring .= "GROUP BY JobEntry.jobentryid,JobProcess.process_priority,JobProcess.process_sequence,JobProcess.process_id,JobEntry.date_receive,JobEntry.customer_name,JobEntry.jonumber,jobEntry.job_description,JobEntry.job_filename,
                ProcessList.process_name,JobEntry.job_quantity,JobEntry.release_date,JobProcess.process_status
                ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':processDivision', $processDivision);
            $result_stmt->execute();

            //* ======== Preparing an array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['process_status'] == 'Pending' && $row['process_priority'] == '500') { //* ======== Set Process Priority ========
                    $processPriority = '-';
                } else if ($row['process_status'] == 'On-Going' && $row['process_priority'] == '500' || $row['process_status'] == 'Process Done' && $row['process_priority'] == '500' || $row['process_status'] == 'Hold' && $row['process_priority'] == '1000' || $row['process_status'] == 'Process Hold' && $row['process_priority'] == '500') {
                    $processPriority = '#';
                } else {
                    $processPriority = $row['process_priority'];
                }
                $processStatus = processStatus($row['process_status']); //* ======== Set Process Status ========
                $drnumber = $row['dr_number'] == '' ? '-' : $row['dr_number']; //* ======== Set DR Number ========
                $dateRelease = releaseDateColor($row['release_date']); //* ======== Set Release Date Color ========

                $nestedData = array();
                $nestedData[] = $processPriority;
                $nestedData[] = date_format(date_create($row['date_receive']), 'm-d-Y');
                $nestedData[] = $row['customer_name'];
                $nestedData[] = $row['jonumber'];
                $nestedData[] = $row['job_description'];
                $nestedData[] = $row['job_filename'];
                $nestedData[] = $row['process_name'];
                $nestedData[] = number_format($row['job_quantity']);
                $nestedData[] = $drnumber;
                $nestedData[] = $dateRelease;
                $nestedData[] = $processStatus;
                $resultData_List[] = $nestedData;
            }
            //* ======== Output Data ========
            $output = array(
                'draw'                  =>  intval($_POST['draw']),
                'iTotalRecords'         =>  $result_total_record,
                'iTotalDisplayRecords'  =>  $result_total_record_filtered,
                'data'                  =>  $resultData_List
            );
            //* ======== Send Data as JSON Format ========
            echo json_encode($output);
            $perso = null; //* ======== Close Connection ========
            break;
        case 'load_job_entry_timeline_data':
            // * ======== Read Data ========
            $filtered = trim($_POST['filtered']);
            $dateentryfrom = trim($_POST['dateentryfrom']);
            $dateentryto = trim($_POST['dateentryto']);
            $customername = trim($_POST['customername']);
            $jonumber = trim($_POST['jonumber']);
            $jobfilename = trim($_POST['jobfilename']);
            $deliverydatefrom = trim($_POST['deliverydatefrom']);
            $deliverydateto = trim($_POST['deliverydateto']);
            $filter_status = trim($_POST['filter_status']);
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            // * ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'date_receive',
                1 => 'job_cutoff',
                2 => 'customer_name',
                3 => 'jonumber',
                4 => 'job_description',
                5 => 'job_filename',
                6 => 'job_quantity',
                7 => 'job_remarks',
                8 => 'release_date',
                9 => 'dr_number',
                10 => 'pickup_courier',
                11 => 'mode_delivery',
                12 => 'job_status'
            );
            // * ======== Fetch Data ========
            $sqlstring = "SELECT * FROM bpi_perso_job_entry WHERE job_status <> 'Done' ";
            if ($filtered == 'Yes') {
                if (
                    $dateentryfrom <> '' || $dateentryto <> ''
                ) {
                    $sqlstring .= "AND date_entry BETWEEN '" . $dateentryfrom . "' AND '" . $dateentryto . "' ";
                }

                if ($customername <> '') {
                    $sqlstring .= "AND customer_name ILIKE '%" . $customername . "%' ";
                }

                if ($jonumber <> '') {
                    $sqlstring .= "AND jonumber ILIKE '%" . $jonumber . "%' ";
                }

                if ($jobfilename <> '') {
                    $sqlstring .= "AND job_filename ILIKE '%" . $jobfilename . "%' ";
                }

                if (
                    $deliverydatefrom <> '' || $deliverydateto <> ''
                ) {
                    $sqlstring .= "AND release_date BETWEEN '" . $deliverydatefrom . "' AND '" . $deliverydateto . "' ";
                }

                if (
                    $filter_status <> ''
                ) {
                    $sqlstring .= "AND job_status ILIKE '%" . $filter_status . "%' ";
                }
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();

            // * ======== Search ========
            $sqlstring = "SELECT * FROM bpi_perso_job_entry WHERE 1 = 1 AND job_status <> 'Done' ";
            if ($filtered == 'Yes') {
                if (
                    $dateentryfrom <> '' || $dateentryto <> ''
                ) {
                    $sqlstring .= "AND date_entry BETWEEN '" . $dateentryfrom . "' AND '" . $dateentryto . "' ";
                }

                if ($customername <> '') {
                    $sqlstring .= "AND customer_name ILIKE '%" . $customername . "%' ";
                }

                if ($jonumber <> '') {
                    $sqlstring .= "AND jonumber ILIKE '%" . $jonumber . "%' ";
                }

                if ($jobfilename <> '') {
                    $sqlstring .= "AND job_filename ILIKE '%" . $jobfilename . "%' ";
                }

                if (
                    $deliverydatefrom <> '' || $deliverydateto <> ''
                ) {
                    $sqlstring .= "AND release_date BETWEEN '" . $deliverydatefrom . "' AND '" . $deliverydateto . "' ";
                }

                if (
                    $filter_status <> ''
                ) {
                    $sqlstring .= "AND job_status ILIKE '%" . $filter_status . "%' ";
                }
            }
            if (!empty($searchValue)) {
                $sqlstring .= "AND (TO_CHAR(date_receive, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR job_cutoff ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%'
                    OR job_description ILIKE '%" . $searchValue . "%' OR job_filename ILIKE '%" . $searchValue . "%' OR CAST(job_quantity AS TEXT) ILIKE '%" . $searchValue . "%' OR TO_CHAR(release_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' 
                    OR dr_number ILIKE '%" . $searchValue . "%' OR mode_delivery ILIKE '%" . $searchValue . "%' OR job_status ILIKE '%" . $searchValue . "%' OR job_remarks ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            // * ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();

            // * ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['job_cutoff'] == 'Beyond Cut-Off') {
                    $jobCutOff = '<span class="badge bg-danger col-sm-12">Beyond Cut-Off</span>';
                } else if ($row['job_cutoff'] == 'Within Cut-Off') {
                    $jobCutOff = '<span class="badge bg-success col-sm-12">Within Cut-Off</span>';
                } else {
                    $jobCutOff = '-';
                }
                $job_status = processStatus($row['job_status']); //* ======== Set Process Status ========
                $jobAction = '<button type="button" class="btn btn-info col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Information" onclick="jobEntryTimelineInfo(\'' . $row["jobentryid"] . '\',\'' . $row["customer_name"] . '\',\'' . $row["jonumber"] . '\',\'' . $row["job_description"] . '\');"><i class="fa-solid fa-circle-info fa-bounce" ></i></button>';
                $dateRelease = releaseDateColor($row['release_date']); //* ======== Set Release Date Color ========

                $nestedData = array();
                $nestedData[] = $row['date_receive'] == '' ? '-' : date_format(date_create($row['date_receive']), 'm-d-Y');
                $nestedData[] = $jobCutOff;
                $nestedData[] = $row['customer_name'];
                $nestedData[] = $row['jonumber'];
                $nestedData[] = $row['job_description'];
                $nestedData[] = $row['job_filename'];
                $nestedData[] = number_format($row['job_quantity']);
                $nestedData[] = $row['job_remarks'];
                $nestedData[] = $row['release_date'] == '' ? '-' : $dateRelease;
                $nestedData[] = $row['dr_number'] == '' ? '-' : $row['dr_number'];
                $nestedData[] = $row['pickup_courier'] == '' ? '-' : $row['pickup_courier'];
                $nestedData[] = $row['mode_delivery'];
                $nestedData[] = $job_status;
                $nestedData[] = $jobAction;
                $resultData_List[] = $nestedData;
            }
            // * ======== Output Data ========
            $output = array(
                'draw'                  =>  intval($_POST['draw']),
                'iTotalRecords'         =>  $result_total_record,
                'iTotalDisplayRecords'  =>  $result_total_record_filtered,
                'data'                  =>  $resultData_List
            );
            // * ======== Send Data as JSON Format ========
            echo json_encode($output);
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_release_table_data':
            // * ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            // * ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'date_receive',
                1 => 'job_cutoff',
                2 => 'customer_name',
                3 => 'jonumber',
                4 => 'job_description',
                5 => 'job_filename',
                6 => 'job_quantity',
                7 => 'release_date',
                8 => 'dr_number',
                9 => 'operator_remarks',
                10 => 'mode_delivery'
            );
            // * ======== Fetch Data ========
            $sqlstring = "SELECT jobentryid,date_receive,job_cutoff,customer_name,jonumber,job_description,job_filename,job_quantity,release_date,dr_number,operator_remarks,mode_delivery,job_status
                    FROM bpi_perso_job_entry jobEntry
                    INNER JOIN bpi_perso_job_process jobProcess
                    ON jobProcess.jobentry_id = jobEntry.jobentryid
                    INNER JOIN bpi_perso_process_list processList
                    ON processList.processid = jobProcess.process_id
                    WHERE processList.process_name = 'Dispatch' AND job_status = 'Done'
                    GROUP BY jobentryid,date_receive,job_cutoff,customer_name,jonumber,job_description,job_filename,job_quantity,release_date,dr_number,operator_remarks,mode_delivery,job_status
                    ORDER BY release_date,customer_name,jonumber,job_filename ASC";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();

            // * ======== Search ========
            $sqlstring = "SELECT jobentryid,date_receive,job_cutoff,customer_name,jonumber,job_description,job_filename,job_quantity,release_date,dr_number,operator_remarks,mode_delivery,job_status
                    FROM bpi_perso_job_entry jobEntry
                    INNER JOIN bpi_perso_job_process jobProcess
                    ON jobProcess.jobentry_id = jobEntry.jobentryid
                    INNER JOIN bpi_perso_process_list processList
                    ON processList.processid = jobProcess.process_id
                    WHERE 1 = 1 AND processList.process_name = 'Dispatch' AND job_status = 'Done' ";
            if (!empty($searchValue)) {
                $sqlstring .= "AND (TO_CHAR(date_receive,'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' OR job_cutoff ILIKE '%" . $searchValue . "%' OR customer_name ILIKE '%" . $searchValue . "%' OR jonumber ILIKE '%" . $searchValue . "%'
                    OR job_description ILIKE '%" . $searchValue . "%' OR job_filename ILIKE '%" . $searchValue . "%' OR CAST(job_quantity AS TEXT) ILIKE '%" . $searchValue . "%' OR TO_CHAR(release_date, 'YYYY-MM-DD') ILIKE '%" . $searchValue . "%' 
                    OR dr_number ILIKE '%" . $searchValue . "%' OR operator_remarks ILIKE '%" . $searchValue . "%' OR mode_delivery ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();

            // * ======== Ordering ========
            $sqlstring .= " GROUP BY jobentryid,date_receive,job_cutoff,customer_name,jonumber,job_description,job_filename,job_quantity,release_date,dr_number,operator_remarks,mode_delivery,job_status
                    ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();

            // * ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['job_cutoff'] == 'Beyond Cut-Off') {
                    $jobCutOff = '<span class="badge bg-danger col-sm-12">Beyond Cut-Off</span>';
                } else if ($row['job_cutoff'] == 'Within Cut-Off') {
                    $jobCutOff = '<span class="badge bg-success col-sm-12">Within Cut-Off</span>';
                } else {
                    $jobCutOff = '-';
                }
                $job_status = processStatus($row['job_status']); //* ======== Set Process Status ========
                $dateRelease = releaseDateColor($row['release_date']); //* ======== Set Release Date Color ========

                $nestedData = array();
                $nestedData[] = $row['date_receive'] == '' ? '-' : date_format(date_create($row['date_receive']), 'm-d-Y');
                $nestedData[] = $jobCutOff;
                $nestedData[] = $row['customer_name'];
                $nestedData[] = $row['jonumber'];
                $nestedData[] = $row['job_description'];
                $nestedData[] = $row['job_filename'];
                $nestedData[] = number_format($row['job_quantity']);
                $nestedData[] = $row['release_date'] == '' ? '-' : $dateRelease;
                $nestedData[] = $row['dr_number'] == '' ? '-' : $row['dr_number'];
                $nestedData[] = $row['operator_remarks'] == '' ? '-' : $row['operator_remarks'];
                $nestedData[] = $row['mode_delivery'];
                $resultData_List[] = $nestedData;
            }
            // * ======== Output Data ========
            $output = array(
                'draw'                  =>  intval($_POST['draw']),
                'iTotalRecords'         =>  $result_total_record,
                'iTotalDisplayRecords'  =>  $result_total_record_filtered,
                'data'                  =>  $resultData_List
            );
            // * ======== Send Data as JSON Format ========
            echo json_encode($output);
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_dropdown_filter':
            $inField = trim($_POST['inField']);
            $inTable = trim($_POST['inTable']);

            $result_sql = "SELECT DISTINCT " . $inField . " FROM " . $inTable . " WHERE job_status <> 'Done'";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->execute();
            $result_row = $result_stmt->fetchAll();
            echo '<option value="">Choose...</option>';
            foreach ($result_row as $row) {
                echo '<option value="' . $row[$inField] . '">' . $row[$inField] . '</option>';
            }
            $perso = null; //* ======== Close Connection ========
            break;
    }
}
