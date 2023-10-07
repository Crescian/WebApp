<?php
date_default_timezone_set('Asia/Manila');
class PersoJobEntry
{
    public function jobStatus($jobStats)
    {
        switch ($jobStats) {
            case 'Pending':
                $entryStatus = '<span class="badge bg-warning col-sm-12">Pending</span>';
                break;
            case 'On-Going':
                $entryStatus = '<span class="badge bg-success col-sm-12">On-Going</span>';
                break;
            case 'Process Done':
                $entryStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Done':
                $entryStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Hold':
                $entryStatus = '<span class="badge bg-danger col-sm-12">On Hold</span>';
                break;
            case 'Process Hold':
                $entryStatus = '<span class="badge bg-danger col-sm-12">Process Hold</span>';
                break;
        }
        return $entryStatus;
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

    public function loadJobEntryTableData($perso)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,JobEntry.customer_name,JobEntry.jonumber,JobEntry.job_description,JobEntry.date_entry, JobEntry.date_receive,JobEntry.job_filename, JobEntry.job_quantity, JobEntry.target_date_finish,JobEntry.release_date,TempName.template_name,JobEntry.job_status,JobEntry.job_cutoff
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_template_name TempName ON TempName.templateid = JobEntry.template_id
            WHERE job_status <> 'Done'";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['date_receive'] == '' ? '-' : $row['date_receive'];
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['target_date_finish'] == '' ? '-' : $row['target_date_finish'];
            $nestedData[] = $row['release_date'] == '' ? '-' : self::releaseDateColor($row['release_date']);
            $nestedData[] = $row['template_name'];
            $nestedData[] = self::jobStatus($row['job_status']);
            $nestedData[] = $row['job_cutoff'] == 'Beyond Cut-Off' ? '<span class="badge bg-danger col-sm-12">Beyond Cut-Off</span>' : ($row['job_cutoff'] == 'Within Cut-Off' ? '<span class="badge bg-success col-sm-12">Within Cut-Off</span>' : '-');
            $nestedData[] = array($row['jobentryid'], $row['job_status']);
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobEntryTableDataDone($perso)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid, JobEntry.customer_name,JobEntry.jonumber,JobEntry.job_description,JobEntry.date_entry, JobEntry.date_receive, JobEntry.job_filename, JobEntry.job_quantity, JobEntry.target_date_finish,JobEntry.release_date,TempName.template_name,JobEntry.job_status
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_template_name TempName ON TempName.templateid = JobEntry.template_id
            WHERE job_status = 'Done'";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = date_format(date_create($row['date_entry']), 'Y-m-d h:i:s A');
            $nestedData[] = $row['date_receive'] == '' ? '-' : $row['date_receive'];
            $nestedData[] = $row['customer_name'];
            $nestedData[] = $row['jonumber'];
            $nestedData[] = $row['job_description'];
            $nestedData[] = $row['job_filename'];
            $nestedData[] = number_format($row['job_quantity']);
            $nestedData[] = $row['target_date_finish'];
            $nestedData[] = self::releaseDateColor($row['release_date']);
            $nestedData[] = $row['template_name'];
            $nestedData[] = $row['jobentryid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadSelectValueDefault($perso, $inField, $inTable, $inOrder)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT " . $inField . " FROM " . $inTable . " ORDER BY " . $inField . " " . $inOrder . "";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row[$inField]] = $row[$inField];
            }
        } else {
            $itemData_List['customer_name'] = 'empty';
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadSelectValueCondition($perso, $inField, $inTable, $inCondition, $inConditionValue, $inOrder)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT " . $inField . " FROM " . $inTable . " WHERE " . $inCondition . " = ? ORDER BY " . $inField . " " . $inOrder . "";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$inConditionValue]);
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row[$inField]] = $row[$inField];
            }
        } else {
            $itemData_List['customer_name'] = 'empty';
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobNumberDescriptions($bannerData, $jonumber)
    {
        $itemData_List = array();

        $sqlstring = "SELECT descriptions,orderid FROM ordersinformation WHERE jonumber = ? ";
        $result_stmt = $bannerData->prepare($sqlstring);
        $result_stmt->execute([$jonumber]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['descriptions'] = $row['descriptions'];
            $itemData_List['orderid'] = $row['orderid'];
        }
        return json_encode($itemData_List);
        $bannerData = null; //* ======== Close Connection ========
    }

    public function loadJobTemplate($perso, $company, $jonumber, $orderid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT TempName.templateid,TempName.template_name FROM bpi_perso_template_assign TempAssign
            INNER JOIN bpi_perso_template_name TempName ON TempName.templateid = TempAssign.template_id
            WHERE TempAssign.customer_name = ? AND TempAssign.orderid = ? AND TempAssign.jonumber = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$company, $orderid, $jonumber]);
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['templateid']] = $row['template_name'];
            }
        } else {
            $itemData_List['templateid'] = 'empty';
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadTemplateProcess($perso, $templateid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT ListProc.process_name,TempProc.process_sequence FROM bpi_perso_template_name TempName
            INNER JOIN bpi_perso_template_process TempProc ON TempProc.template_id = TempName.templateid
            INNER JOIN bpi_perso_process_list ListProc ON ListProc.processid = TempProc.process_id
            WHERE TempName.templateid = ? ORDER BY TempProc.process_sequence";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$templateid]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadTemplateMaterial($perso, $templateid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT ListMat.material_name FROM bpi_perso_template_name TempName
            INNER JOIN bpi_perso_template_material TempMat ON TempMat.template_id = TempName.templateid
            INNER JOIN bpi_perso_material_list ListMat ON ListMat.materialid = TempMat.material_id
            WHERE TempName.templateid = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$templateid]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobCourier($cms_data, $company_name)
    {
        $itemData_List = array();
        $sqlstring = "SELECT courier FROM cms_authorization_courier WHERE company = ? AND courier <> ''";
        $result_stmt = $cms_data->prepare($sqlstring);
        $result_stmt->execute([$company_name]);
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['courier']] = $row['courier'];
            }
        } else {
            $itemData_List['courier'] = 'empty';
        }
        return json_encode($itemData_List);
        $cms_data = null; //* ======== Close Connection ========
    }

    public function saveJobEntry($perso, $company, $jonumber, $orderid, $job_description, $job_filename, $job_quantity, $releaseDate, $dateReceive, $mode_delivery, $job_template, $job_chk_hold, $pickup_courier, $job_cutoff)
    {
        $itemData_List = array();
        $date_entry = date('Y-m-d H:i:s');
        $job_priority = 'Priority 1';
        if ($job_chk_hold == 'true') {
            $jobstatus = 'Hold';
        } else {
            $jobstatus = 'Pending';
        }
        //* ======== Check Existing Filename ========
        $chkExist_sql = "SELECT * FROM bpi_perso_job_entry WHERE job_filename = ?";
        $chkExist_stmt = $perso->prepare($chkExist_sql);
        $chkExist_stmt->execute([$job_filename]);

        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['jobentryid'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO bpi_perso_job_entry(customer_name,jonumber,orderid,job_description,job_filename,job_quantity,target_date_finish,
                release_date,mode_delivery,job_priority,template_id,date_entry,date_receive,job_status,pickup_courier,job_cutoff)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING jobentryid";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute([$company, $jonumber, $orderid, $job_description, $job_filename, $job_quantity, $releaseDate, $releaseDate, $mode_delivery, $job_priority, $job_template, $date_entry, $dateReceive, $jobstatus, $pickup_courier, $job_cutoff]);

            $itemData_List['jobentryid'] = $perso->lastInsertId();
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function saveProcessAndMaterial($perso, $jobentryid, $templateid, $job_chk_hold)
    {
        if ($job_chk_hold == 'true') {
            $process_status = 'Hold';
            $material_status = 'Hold';
            $processPriority = '1000';
        } else {
            $process_status = 'Pending';
            $material_status = 'Pending';
            $processPriority = '500';
        }

        //* ======== Insert Job Process Data ========
        $result_fetch_process_sql = "SELECT * FROM bpi_perso_template_process WHERE template_id = ? ORDER BY process_sequence ASC";
        $result_fetch_process_stmt = $perso->prepare($result_fetch_process_sql);
        $result_fetch_process_stmt->execute([$templateid]);
        foreach ($result_fetch_process_stmt->fetchAll() as $result_fetch_process_row) {
            $result_job_process_sql = "INSERT INTO bpi_perso_job_process(jobentry_id,process_id,process_sequence,process_status,process_priority) VALUES(?,?,?,?,?)";
            $result_job_process_stmt = $perso->prepare($result_job_process_sql);
            $result_job_process_stmt->execute([$jobentryid, $result_fetch_process_row['process_id'], $result_fetch_process_row['process_sequence'], $process_status, $processPriority]);
        }

        //* ======== Insert Job Material Data ========
        $result_fetch_material_sql = "SELECT * FROM bpi_perso_template_material WHERE template_id = ?";
        $result_fetch_material_stmt = $perso->prepare($result_fetch_material_sql);
        $result_fetch_material_stmt->execute([$templateid]);
        foreach ($result_fetch_material_stmt->fetchAll() as $result_fetch_material_row) {
            $result_job_material_sql = "INSERT INTO bpi_perso_job_material(jobentry_id,material_id,material_status) VALUES(?,?,?)";
            $result_job_material_stmt = $perso->prepare($result_job_material_sql);
            $result_job_material_stmt->execute([$jobentryid, $result_fetch_material_row['material_id'], $material_status]);
        }
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobEntryInfo($perso, $jobentryid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_job_entry WHERE jobentryid = '" . $jobentryid . "'";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['customer_name'] = $row['customer_name'];
            $itemData_List['jonumber'] = $row['jonumber'];
            $itemData_List['job_description'] = $row['job_description'];
            $itemData_List['orderid'] = $row['orderid'];
            $itemData_List['job_filename'] = $row['job_filename'];
            $itemData_List['date_entry'] = date_format(date_create($row['date_entry']), 'Y-m-d');
            $itemData_List['job_quantity'] = $row['job_quantity'];
            $itemData_List['release_date'] = $row['release_date'];
            $itemData_List['date_receive'] = $row['date_receive'];
            $itemData_List['job_cutoff'] = $row['job_cutoff'];
            $itemData_List['mode_delivery'] = $row['mode_delivery'];
            $itemData_List['template_id'] = $row['template_id'];
            $itemData_List['job_status'] = $row['job_status'];
            $itemData_List['pickup_courier'] = $row['pickup_courier'];
        }
        echo json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function updateJobEntry($perso, $jobentryid, $company, $jonumber, $orderid, $job_description, $job_filename, $job_quantity, $date_entry, $releaseDate, $dateReceive, $mode_delivery, $job_template, $job_status, $pickup_courier, $job_cutoff)
    {
        $itemData_List = array();
        if ($job_status == 'Hold') {
            $process_status = 'Hold';
            $material_status = 'Hold';
            $processPriority = '1000';
        } else if ($job_status == 'Pending') {
            $process_status = 'Pending';
            $material_status = 'Pending';
            $processPriority = '500';
        }

        //* ======== Check Existing Filename ========
        $chkExist_sql = "SELECT * FROM bpi_perso_job_entry WHERE job_filename = ? AND jobentryid <> ?";
        $chkExist_stmt = $perso->prepare($chkExist_sql);
        $chkExist_stmt->execute([$job_filename, $jobentryid]);

        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $result_chktemplateid_sql = "SELECT template_id FROM bpi_perso_job_entry WHERE jobentryid = ?";
            $result_chktemplateid_stmt = $perso->prepare($result_chktemplateid_sql);
            $result_chktemplateid_stmt->execute([$jobentryid]);
            while ($result_fetch_templateid = $result_chktemplateid_stmt->fetch(PDO::FETCH_ASSOC)) {
                $curr_templateid = $result_fetch_templateid['template_id'];
            }

            $sqlstring = "UPDATE bpi_perso_job_entry SET customer_name = ?, jonumber = ?, orderid = ?, job_description = ?, job_filename = ?,
                job_quantity = ?, date_entry = ?, target_date_finish = ?, release_date = ?, date_receive = ?, mode_delivery = ?, 
                template_id = ?, job_status = ?, pickup_courier = ?, job_cutoff = ? WHERE jobentryid = ?";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute([$company, $jonumber, $orderid, $job_description, $job_filename, $job_quantity, $date_entry, $releaseDate, $releaseDate, $dateReceive, $mode_delivery, $job_template, $job_status, $pickup_courier, $job_cutoff, $jobentryid]);

            if ($curr_templateid != $job_template) {
                //* ======== Delete Job Process Data ========
                $delete_process_sql = "DELETE FROM bpi_perso_job_process WHERE jobentry_id = ?";
                $delete_process_stmt = $perso->prepare($delete_process_sql);
                $delete_process_stmt->execute([$jobentryid]);
                //* ======== Insert Job Process Data ========
                $result_fetch_process_sql = "SELECT * FROM bpi_perso_template_process WHERE template_id = ? ORDER BY process_sequence ASC";
                $result_fetch_process_stmt = $perso->prepare($result_fetch_process_sql);
                $result_fetch_process_stmt->execute([$job_template]);
                foreach ($result_fetch_process_stmt->fetchAll() as $result_fetch_process_row) {
                    $result_job_process_sql = "INSERT INTO bpi_perso_job_process(jobentry_id,process_id,process_sequence,process_status,process_priority) VALUES(?,?,?,?,?)";
                    $result_job_process_stmt = $perso->prepare($result_job_process_sql);
                    $result_job_process_stmt->execute([$jobentryid, $result_fetch_process_row['process_id'], $result_fetch_process_row['process_sequence'], $process_status, $processPriority]);
                }

                //* ======== Delete Job Material Data ========
                $delete_material_sql = "DELETE FROM bpi_perso_job_material WHERE jobentry_id = ?";
                $delete_material_stmt = $perso->prepare($delete_material_sql);
                $delete_material_stmt->execute([$jobentryid]);
                //* ======== Insert Job Material Data ========
                $result_fetch_material_sql = "SELECT * FROM bpi_perso_template_material WHERE template_id = ?";
                $result_fetch_material_stmt = $perso->prepare($result_fetch_material_sql);
                $result_fetch_material_stmt->execute([$job_template]);
                foreach ($result_fetch_material_stmt->fetchAll() as $result_fetch_material_row) {
                    $result_job_material_sql = "INSERT INTO bpi_perso_job_material(jobentry_id,material_id,material_status) 
                            VALUES(?,?,?)";
                    $result_job_material_stmt = $perso->prepare($result_job_material_sql);
                    $result_job_material_stmt->execute([$jobentryid, $result_fetch_material_row['material_id'], $material_status]);
                }
            } else {
                $result_upt_process_sql = "UPDATE bpi_perso_job_process SET process_status = ?, operator_remarks = NULL WHERE jobentry_id = ? AND process_status <> 'Process Done' AND process_status <> 'Done' AND process_status <> 'On-Going'";
                $result_upt_process_stmt = $perso->prepare($result_upt_process_sql);
                $result_upt_process_stmt->execute([$process_status, $jobentryid]);

                $result_upt_material_sql = "UPDATE bpi_perso_job_material SET material_status = ? WHERE jobentry_id = ? AND material_status <> 'Process Done' AND material_status <> 'Done'";
                $result_upt_material_stmt = $perso->prepare($result_upt_material_sql);
                $result_upt_material_stmt->execute([$material_status, $jobentryid]);
            }
            $itemData_List['result'] = 'updated';
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function deleteJobEntry($perso, $jobentryid)
    {
        $sqlstring = "DELETE FROM bpi_perso_job_entry WHERE jobentryid = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentryid]);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobEntryProcessData($perso, $processDivision, $jobentryid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,ProcessList.processid,JobProcess.process_sequence,ProcessList.process_name,JobEntry.job_quantity,JobProcess.process_status,JobProcess.operator_remarks
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_division = ? AND jobentryid = ?
            GROUP BY  JobEntry.jobentryid,ProcessList.processid,JobProcess.process_sequence,ProcessList.process_name,JobEntry.job_quantity,JobProcess.process_status,JobProcess.operator_remarks
            ORDER BY JobEntry.jobentryid,JobProcess.process_sequence ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$processDivision, $jobentryid]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $nestedData = array();
            $nestedData['jobentryid'] = $row['jobentryid'];
            $nestedData['processid'] = $row['processid'];
            $nestedData['job_quantity'] = number_format($row['job_quantity']);
            $nestedData['process_name'] = $row['process_name'];
            $nestedData['operator_remarks'] = $row['operator_remarks'] == '' ? '-' : $row['operator_remarks'];
            $nestedData['process_status'] = $row['process_status'];
            $nestedData['processStatus'] = self::jobStatus($row['process_status']);
            $itemData_List[] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadJobEntryMaterialData($perso, $materialSection, $jobentryid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobEntry.jobentryid,MaterialList.materialid,MaterialList.material_name,JobMaterial.material_status,JobEntry.job_quantity,JobMaterial.operator_remarks
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_material JobMaterial ON JobMaterial.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_material_list MaterialList ON MaterialList.materialid = JobMaterial.material_id
            WHERE MaterialList.material_section = ? AND jobentryid = ?
            GROUP BY  JobEntry.jobentryid,MaterialList.materialid,MaterialList.material_name,JobMaterial.material_status,JobEntry.job_quantity,JobMaterial.operator_remarks
            ORDER BY JobEntry.jobentryid ASC";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$materialSection, $jobentryid]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $nestedData = array();
            $nestedData['jobentryid'] = $row['jobentryid'];
            $nestedData['materialid'] = $row['materialid'];
            $nestedData['job_quantity'] = number_format($row['job_quantity']);
            $nestedData['material_name'] = $row['material_name'];
            $nestedData['operator_remarks'] = $row['operator_remarks'] == '' ? '-' : $row['operator_remarks'];
            $nestedData['material_status'] = $row['material_status'];
            $nestedData['materialStatus'] = self::jobStatus($row['material_status']);
            $itemData_List[] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function resetProcessData($perso, $jobentryid, $processid)
    {
        $sqlstring = "UPDATE bpi_perso_job_process SET date_time_start = NULL, date_time_end = NULL, operator_remarks = NULL, process_status = 'Pending' WHERE jobentry_id = ? AND process_id = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentryid, $processid]);

        $result_fetch_total = "SELECT COUNT(*) AS total_count, (SELECT COUNT(*) AS pending_count FROM bpi_perso_job_process WHERE jobentry_id = ? AND process_status = 'Pending') 
            FROM bpi_perso_job_process WHERE jobentry_id = ?";
        $result_fetch_stmt = $perso->prepare($result_fetch_total);
        $result_fetch_stmt->execute([$jobentryid, $jobentryid]);

        while ($result_fetch_row = $result_fetch_stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_count = $result_fetch_row['total_count'];
            $pending_count = $result_fetch_row['pending_count'];
        }

        if ($total_count == $pending_count) {
            $result_update_sql = "UPDATE bpi_perso_job_entry SET job_status = 'Pending' WHERE jobentryid = ?";
            $result_update_stmt = $perso->prepare($result_update_sql);
            $result_update_stmt->execute([$jobentryid]);
        }
        $perso = null; //* ======== Close Connection ========
    }

    public function resetMaterialData($perso, $jobentryid, $materialid)
    {
        $sqlstring = "UPDATE bpi_perso_job_material SET material_status = 'Pending', date_time_end = NULL, operator_remarks = NULL WHERE jobentry_id = ? AND material_id = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentryid, $materialid]);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadProcessDoneInfo($perso, $jobentryid, $processid, $processDivision)
    {
        $itemData_List = array();
        $sqlstring = "SELECT JobProcess.operator_remarks,JobProcess.process_status,JobProcess.date_time_start,JobProcess.date_time_end,JobProcess.process_instructions
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_division = ? AND jobentryid = ? AND process_id = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$processDivision, $jobentryid, $processid]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['date_time_start'] = $row['date_time_start'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_start']), 'Y-m-d h:i:s A');
            $itemData_List['date_time_end'] = $row['date_time_end'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_end']), 'Y-m-d h:i:s A');
            $itemData_List['process_status'] = $row['process_status'];
            $itemData_List['process_instructions'] = $row['process_instructions'] == '' ? '-' : $row['process_instructions'];
            $itemData_List['operator_remarks'] = $row['operator_remarks'] == '' ? '-' : $row['operator_remarks'];
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadProcessDoneInfoOperator($perso, $jobentryid, $processid, $processSequence, $jobCategory)
    {
        $itemData_List = array();
        $sqlstring = "SELECT process_operator FROM bpi_perso_job_process_operator WHERE jobentry_id = ? AND process_id = ? AND job_category = ? AND partial_sequence = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentryid, $processid, $jobCategory, $processSequence]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $nestedData = array();
            $nestedData['process_operator'] = $row['process_operator'];
            $itemData_List[] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadMaterialDoneInfo($perso, $jobentryid, $materialid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_job_material WHERE jobentry_id = ? AND material_id = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentryid, $materialid]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['date_time_end'] = $row['date_time_end'] == '' ? '-- : -- : --' : date_format(date_create($row['date_time_end']), 'Y-m-d h:i:s A');
            $itemData_List['material_status'] = $row['material_status'];
            $itemData_List['operator_remarks'] = $row['operator_remarks'];
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function loadMaterialDoneInfoOperator($perso, $jobentryid, $materialid, $jobCategory)
    {
        $itemData_List = array();
        $sqlstring = "SELECT material_operator FROM bpi_perso_job_material_operator WHERE jobentry_id = ? AND material_id = ? AND job_category = ?";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$jobentryid, $materialid, $jobCategory]);
        //* ======== Prepare Array ========
        foreach ($result_stmt->fetchAll() as $row) {
            $nestedData = array();
            $nestedData['material_operator'] = $row['material_operator'];
            $itemData_List[] = $nestedData;
        }
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
    }

    public function archiveJobEntryDone($perso, $perso_archive, $month_from, $month_to)
    {
        $itemData_List = array();
        $sqlstring = "SELECT jobentryid FROM bpi_perso_job_entry WHERE job_status = 'Done' AND TO_CHAR(date_entry, 'YYYY MM') BETWEEN ? AND ? ORDER BY jobentryid DESC LIMIT 80";
        $result_stmt = $perso->prepare($sqlstring);
        $result_stmt->execute([$month_from, $month_to]);
        //* ======== Prepare Array ========
        $rowCount = $result_stmt->rowCount();
        if ($rowCount > 0) {
            $itemData_List = 'meron';
            foreach ($result_stmt->fetchAll() as $row) {
                //* ======== Check if Already Existing in Archive ========
                $chkExist = "SELECT * FROM bpi_perso_job_entry WHERE jobentryid = ?";
                $chkExist_stmt = $perso_archive->prepare($chkExist);
                $chkExist_stmt->execute([$row['jobentryid']]);
                $chkExistCount = $chkExist_stmt->rowCount();
                if ($chkExistCount > 0) {
                    //* ======== Delete Job Entry ========
                    $jobDel = "DELETE FROM bpi_perso_job_entry WHERE jobentryid = ?";
                    $jobDel_stmt = $perso->prepare($jobDel);
                    $jobDel_stmt->execute([$row['jobentryid']]);
                } else {
                    //* ======== Archive Job Entry ========
                    $jobentry_sql = "SELECT * FROM bpi_perso_job_entry WHERE jobentryid = ?";
                    $jobentry_stmt = $perso->prepare($jobentry_sql);
                    $jobentry_stmt->execute([$row['jobentryid']]);
                    while ($row_jobentry = $jobentry_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $job_archive_sql = "INSERT INTO bpi_perso_job_entry(jobentryid, customer_name, jonumber, orderid, job_filename, job_quantity, target_date_finish, release_date, mode_delivery, 
                        job_remarks, job_priority, template_id, date_entry, job_status, dr_number, job_description, servicereportno, servicereportno_preparedby, pickup_courier, date_receive, job_cutoff)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $job_archive_stmt = $perso_archive->prepare($job_archive_sql);
                        $job_archive_stmt->execute([
                            $row_jobentry['jobentryid'], $row_jobentry['customer_name'], $row_jobentry['jonumber'], $row_jobentry['orderid'], $row_jobentry['job_filename'], $row_jobentry['job_quantity'], $row_jobentry['target_date_finish'],
                            $row_jobentry['release_date'], $row_jobentry['mode_delivery'], $row_jobentry['job_remarks'], $row_jobentry['job_priority'], $row_jobentry['template_id'], $row_jobentry['date_entry'], $row_jobentry['job_status'],
                            $row_jobentry['dr_number'], $row_jobentry['job_description'], $row_jobentry['servicereportno'], $row_jobentry['servicereportno_preparedby'], $row_jobentry['pickup_courier'], $row_jobentry['date_receive'], $row_jobentry['job_cutoff']
                        ]);
                    }
                    //* ======== Archive Job Process ========
                    $jobprocess_sql = "SELECT * FROM bpi_perso_job_process WHERE jobentry_id = ?";
                    $jobprocess_stmt = $perso->prepare($jobprocess_sql);
                    $jobprocess_stmt->execute([$row['jobentryid']]);
                    while ($row_jobprocess = $jobprocess_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $process_archive_sql = "INSERT INTO bpi_perso_job_process(jobentry_id, process_id, process_sequence, date_time_start, date_time_end, operator_remarks, process_status, process_priority, process_machine, process_instructions)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $process_archive_stmt = $perso_archive->prepare($process_archive_sql);
                        $process_archive_stmt->execute([
                            $row_jobprocess['jobentry_id'], $row_jobprocess['process_id'], $row_jobprocess['process_sequence'], $row_jobprocess['date_time_start'], $row_jobprocess['date_time_end'],
                            $row_jobprocess['operator_remarks'], $row_jobprocess['process_status'], $row_jobprocess['process_priority'], $row_jobprocess['process_machine'], $row_jobprocess['process_instructions']
                        ]);
                    }
                    //* ======== Archive Job Process Operator ========
                    $process_opt_sql = "SELECT * FROM bpi_perso_job_process_operator WHERE jobentry_id = ?";
                    $process_opt_stmt = $perso->prepare($process_opt_sql);
                    $process_opt_stmt->execute([$row['jobentryid']]);
                    while ($row_process_opt = $process_opt_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $process_opt_archive_sql = "INSERT INTO bpi_perso_job_process_operator(jobentry_id, process_id, process_operator, job_category, process_section, partial_sequence)
                        VALUES (?, ?, ?, ?, ?, ?)";
                        $process_opt_archive_stmt = $perso_archive->prepare($process_opt_archive_sql);
                        $process_opt_archive_stmt->execute([
                            $row_process_opt['jobentry_id'], $row_process_opt['process_id'], $row_process_opt['process_operator'],
                            $row_process_opt['job_category'], $row_process_opt['process_section'], $row_process_opt['partial_sequence']
                        ]);
                    }

                    //* ======== Archive Job Material ========
                    $jobmaterial_sql = "SELECT * FROM bpi_perso_job_material WHERE jobentry_id = ?";
                    $jobmaterial_stmt = $perso->prepare($jobmaterial_sql);
                    $jobmaterial_stmt->execute([$row['jobentryid']]);
                    while ($row_jobmaterial = $jobmaterial_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $material_archive_sql = "INSERT INTO bpi_perso_job_material(jobentry_id, material_id, date_time_end, operator_remarks, material_status)
                        VALUES (?, ?, ?, ?, ?)";
                        $material_archive_stmt = $perso_archive->prepare($material_archive_sql);
                        $material_archive_stmt->execute([
                            $row_jobmaterial['jobentry_id'], $row_jobmaterial['material_id'], $row_jobmaterial['date_time_end'], $row_jobmaterial['operator_remarks'], $row_jobmaterial['material_status']
                        ]);
                    }
                    //* ======== Archive Job Material Operator ========
                    $material_opt_sql = "SELECT * FROM bpi_perso_job_material_operator WHERE jobentry_id = ?";
                    $material_opt_stmt = $perso->prepare($material_opt_sql);
                    $material_opt_stmt->execute([$row['jobentryid']]);
                    while ($row_material_opt = $material_opt_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $material_opt_archive_sql = "INSERT INTO bpi_perso_job_material_operator(jobentry_id, material_id, material_operator, job_category, material_section)
                        VALUES (?, ?, ?, ?, ?)";
                        $material_opt_archive_stmt = $perso_archive->prepare($material_opt_archive_sql);
                        $material_opt_archive_stmt->execute([
                            $row_material_opt['jobentry_id'], $row_material_opt['material_id'], $row_material_opt['material_operator'], $row_material_opt['job_category'], $row_material_opt['material_section']
                        ]);
                    }
                    //* ======== Delete Job Entry ========
                    $jobDel = "DELETE FROM bpi_perso_job_entry WHERE jobentryid = ?";
                    $jobDel_stmt = $perso->prepare($jobDel);
                    $jobDel_stmt->execute([$row['jobentryid']]);
                }
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
        $perso = null; //* ======== Close Connection ========
        $perso_archive = null; //* ======== Close Connection ========
    }

    public function loadScanCodeValue($bannerData, $scan_code)
    {
        $itemData_List = array();
        $category = substr($scan_code, 0, 3);
        $control_no = substr($scan_code, 0, strripos($scan_code, "-"));

        if ($category == 'PKG') {
            $cat_section = substr($scan_code, -2);
        }

        switch ($category) {
            case 'DAC':
                $inTable = 'tblper_datacard_jb_child';
                $inField = 'controlnoc';
                $inTable2 = 'tblper_datacard_jb_details';
                $inField2 = 'datacardchildid';
                break;
            case 'EMB':
                $inTable = 'tbleb_embossing_child';
                $inField = 'controlnoc';
                $inTable2 = 'tbleb_embossing_child_details';
                $inField2 = 'embchildid';
                break;
            case 'THE':
                $inTable = 'tbleb_thermal_child';
                $inField = 'controlnoc';
                $inTable2 = 'tbleb_thermal_details';
                $inField2 = 'thermalid';
                break;
            case 'MEM':
                $inTable = 'tblper_memjet';
                $inField = 'controlno';
                $inTable2 = 'tblper_memjet_details';
                $inField2 = 'controlno';
                break;
            case 'PKG':
                if ($cat_section == 'IS') {
                    $inTable = 'packagingchild';
                    $inField = 'controlnoc';
                    $inTable2 = 'tblper_packdetail';
                    $inField2 = 'pkgchildid';
                } else if ($cat_section == 'PS') {
                    $inTable = 'tblper_shrinkchild';
                    $inField = 'controlnoc';
                    $inTable2 = 'tblper_shrinkchilddetail';
                    $inField2 = 'shrinkchildid';
                } else {
                    $inTable = 'tblper_wrapchild';
                    $inField = 'controlnoc';
                    // $inTable2 = 'tblper_inkjetchilddetail';
                    // $inField2 = 'inkchildid';
                }
                break;
            default:
                $inTable = 'inkjetchild';
                $inField = 'controlnoc';
                $inTable2 = 'tblper_inkjetchilddetail';
                $inField2 = 'inkchildid';
                break;
        }

        $sqlstring = "SELECT {$inTable}.{$inField2},{$inTable}.orderid,companyname,jonumber,descriptions,jo_quantity,filename FROM {$inTable}
        INNER JOIN banner_job_structure ON banner_job_structure.orderid = {$inTable}.orderid
        INNER JOIN {$inTable2} ON {$inTable2}.{$inField2} = CAST({$inTable}.{$inField2} AS varchar)
        WHERE {$inTable}.{$inField} = ?;";
        $result_stmt = $bannerData->prepare($sqlstring);
        $result_stmt->execute([$control_no]);
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['childid'] = $row[$inField2];
            $itemData_List['companyname'] = $row['companyname'];
            $itemData_List['jonumber'] = $row['jonumber'];
            $itemData_List['jo_quantity'] = $row['jo_quantity'];
            $itemData_List['filename'][$row['filename']] = $row['filename'];
        }
        return json_encode($itemData_List);
    }

    public function loadFilenameQuantity($bannerData, $childid, $filename)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM tblper_inkjetchilddetail WHERE inkchildid = ? AND filename = ?";
        $result_stmt = $bannerData->prepare($sqlstring);
        $result_stmt->execute([$childid, $filename]);
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['filequantity'] = $row['filequantity'];
        }
        return json_encode($itemData_List);
    }
}
