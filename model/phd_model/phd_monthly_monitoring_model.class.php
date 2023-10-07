<?php
class PhdMonthlyMonitoring
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_monthly_monitoring_header";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['monthly_monitoring_header'];
            $nestedData[] = $row['interlocking_performed_by'] == '' ? '-' : $row['interlocking_performed_by'];
            $nestedData[] = $row['interlocking_checked_by'] == '' ? '-' : $row['interlocking_checked_by'];
            $nestedData[] = $row['electric_performed_by'] == '' ? '-' : $row['electric_performed_by'];
            $nestedData[] = $row['electric_checked_by'] == '' ? '-' : $row['electric_checked_by'];
            $nestedData[] = $row['emergency_performed_by'] == '' ? '-' : $row['emergency_performed_by'];
            $nestedData[] = $row['emergency_checked_by'] == '' ? '-' : $row['emergency_checked_by'];
            $nestedData[] = $row['roomtemp_performed_by'] == '' ? '-' : $row['roomtemp_performed_by'];
            $nestedData[] = $row['roomtemp_checked_by'] == '' ? '-' : $row['roomtemp_checked_by'];
            $nestedData[] = $row['monitoring_noted_by'] == '' ? '-' : $row['monitoring_noted_by'];
            $nestedData[] = $row['monthlymonitoringid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadDataInterlockingRudTable($PHD)
    {
        $resultData_List = array();

        $sqlstring = "SELECT device_category_name,category_name,location_name FROM phd_device_category_assign
                INNER JOIN phd_device_category ON phd_device_category.devicecategoryid = phd_device_category_assign.devicecategory_id
                INNER JOIN phd_loc_category_assign ON phd_loc_category_assign.phdloccat_id = phd_device_category_assign.phdloccat_id
                INNER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_device_category_assign.phdloccat_id
                INNER JOIN phd_location ON phd_location.phdlocationid = phd_loc_category_assign.phdlocation_id ORDER BY devicecatassignid DESC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List[$row['category_name']][] = $row; //* ======== 2D Array
        }
        return json_encode($resultData_List);
        // $cat_count = 0;
        // foreach ($resultData_List as $tablecategory => $category_name) {
        //     echo '<tr>';
        //     echo '<td style="text-align:center;" colspan="4"><input type="text" name="interlock_category_name[]" class="form-control fw-bold interlock_category_name" id="interlock_category_name" value="' . $tablecategory . '" disabled></td>';
        //     foreach ($category_name as $locationname) {
        //         echo '<tr>';
        //         echo '<td><input type="hidden" class="btnActivate' . $cat_count . ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' . $cat_count . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' . $cat_count . ',\'' . $locationname['location_name'] . '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
        //         echo '<td style="vertical-align:middle;"><input type="text" name="interlock_location_name' . $cat_count . '[]" class="form-control fw-bold interlock_location_name' . $cat_count . '" id="interlock_location_name' . $cat_count . '" value="' . $locationname['location_name']  . '" disabled></td>';
        //         echo '<td style="width:30%;"><input type="text" name="interlock_status' . $cat_count . '[]" class="form-control text-center fw-bold interlock_status' . $cat_count . '" id="interlock_status' . $cat_count . '" disabled></td>';
        //         echo '<td style="width:30%;"><input type="text" name="interlock_remarks' . $cat_count . '[]" class="form-control text-center fw-bold interlock_remarks' . $cat_count . '" id="interlock_remarks' . $cat_count . '" disabled></td>';
        //         echo '</tr>';
        //     }
        //     echo '</tr>';
        //     $cat_count++;
        // }
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadDataElectricFenceTable($PHD)
    {
        $sqlstring = "SELECT devicecatassignid,device_category_name,location_name FROM phd_device_category_assign
                LEFT JOIN phd_device_category ON phd_device_category.devicecategoryid = phd_device_category_assign.devicecategory_id
                LEFT JOIN phd_location ON phd_location.phdlocationid = phd_device_category_assign.phdlocation_id
                LEFT JOIN phd_location_category ON phd_location_category.phdloccatid = phd_device_category_assign.phdloccat_id
                WHERE device_category_name ILIKE '%Electric Fence%' ORDER BY devicecatassignid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        // $count = 0;
        // while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
        //     $count++;
        //     echo '<tr>';
        //     echo '<td><input type="hidden" class="btnActivate' . $count . ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' . $count . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' . $count . ',\'' . $row['location_name'] . '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
        //     echo '<td style="vertical-align:middle;">' . $row['location_name'] . '</td>';
        //     echo '<td style="vertical-align:middle;display:none;"><input type="text" name="electric_location_name[]" class="form-control fw-bold electric_location_name" id="electric_location_name' . $count . '" value="' . $row['location_name']  . '" disabled></td>';
        //     echo '<td style="width:30%;"><input type="text" name="electric_fence_status[]" class="form-control text-center fw-bold electric_fence_status" id="electric_fence_status' . $count . '" disabled></td>';
        //     echo '<td style="width:30%;"><input type="text" name="electric_fence_remarks[]" class="form-control text-center fw-bold electric_fence_remarks" id="electric_fence_remarks' . $count . '" disabled></td>';
        //     echo '</tr>';
        // }
    }
    public function loadDataEmergenceEvalSwitchTable($PHD)
    {
        $sqlstring = "SELECT devicecatassignid,location_name FROM phd_device_category_assign
                LEFT JOIN phd_device_category ON phd_device_category.devicecategoryid = phd_device_category_assign.devicecategory_id
                LEFT JOIN phd_location ON phd_location.phdlocationid = phd_device_category_assign.phdlocation_id
                LEFT JOIN phd_location_category ON phd_location_category.phdloccatid = phd_device_category_assign.phdloccat_id
                WHERE device_category_name ILIKE '%Emergency Evaluation Switch%' ORDER BY devicecatassignid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);
        // $count = 0;
        // while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
        //     $count++;
        //     echo '<tr>';
        //     echo '<td><input type="hidden" class="btnActivate' . $count . ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' . $count . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' . $count . ',\'' . $row['location_name'] . '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
        //     echo '<td style="vertical-align:middle;">' . $row['device_name'] . '</td>';
        //     echo '<td style="vertical-align:middle; display:none;"><input type="text" name="emergency_switch[]" class="form-control fw-bold emergency_switch" id="emergency_switch' . $count . '" value="' . $row['device_name']  . '" disabled></td>';
        //     echo '<td style="vertical-align:middle;width:32%;"><input type="text" name="emergency_eval_status[]" class="form-control text-center fw-bold emergency_eval_status" id="emergency_eval_status' . $count . '" disabled></td>';
        //     echo '<td style="vertical-align:middle;width:32%;"><input type="text" name="emergency_eval_remarks[]" class="form-control text-center fw-bold emergency_eval_remarks" id="emergency_eval_remarks' . $count . '" disabled></td>';
        //     echo '</tr>';
        // }
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadDataRoomTempTable($PHD)
    {
        $sqlstring = "SELECT devicecatassignid,location_name FROM phd_device_category_assign
                LEFT JOIN phd_device_category ON phd_device_category.devicecategoryid = phd_device_category_assign.devicecategory_id
                LEFT JOIN phd_location ON phd_location.phdlocationid = phd_device_category_assign.phdlocation_id
                LEFT JOIN phd_location_category ON phd_location_category.phdloccatid = phd_device_category_assign.phdloccat_id
                WHERE device_category_name ILIKE '%Room Temperature%' ORDER BY devicecatassignid ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[] = $row;
        }
        return json_encode($itemData_List);

        // $count = 0;
        // while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
        //     $count++;
        //     echo '<tr>';
        //     echo '<td><input type="hidden" class="btnActivate' . $count . ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' . $count . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' . $count . ',\'' . $row['location_name'] . '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
        //     echo '<td style="vertical-align:middle;">' . $row['location_name'] . '</td>';
        //     echo '<td style="vertical-align:middle;display:none;"><input type="text" name="roomtemp_location_name[]" class="form-control fw-bold roomtemp_location_name" id="roomtemp_location_name' . $count . '" value="' . $row['location_name'] . '" disabled></td>';
        //     echo '<td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_reading_one[]" class="form-control text-center fw-bold roomtemp_reading_one" id="roomtemp_reading_one' . $count . '" disabled></td>';
        //     echo '<td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_reading_two[]" class="form-control text-center fw-bold roomtemp_reading_two" id="roomtemp_reading_two' . $count . '" disabled></td>';
        //     echo '<td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_temp_alarm[]" class="form-control text-center fw-bold roomtemp_temp_alarm" id="roomtemp_temp_alarm' . $count . '" disabled></td>';
        //     echo '<td style="vertical-align:middle;width:25%;"><input type="text" name="roomtemp_remarks[]" class="form-control text-center fw-bold roomtemp_remarks" id="roomtemp_remarks' . $count . '" disabled></td>';
        //     echo '</tr>';
        // }
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataMonthlyHeader($PHD, $performed_by, $checked_by, $noted_by, $category, $monitoring_header, $date_created, $performBySignature_row, $checkedBySignature_row, $NotedBySignature_row, $currentDate)
    {
        //* ======== Check if Header already Exist During the month ========
        $chkExist = "SELECT * FROM phd_monthly_monitoring_header WHERE TO_CHAR(date_created,'MM-YYYY') = :date_created";
        $chkExist_stmt = $PHD->prepare($chkExist);
        $chkExist_stmt->bindParam(':date_created', $date_created);
        $chkExist_stmt->execute();
        if ($chkExist_stmt->rowCount() > 0) {
            //* ======== If Header already exist, fetch only ID and ref_no ========
            $chkExist_row = $chkExist_stmt->fetch(PDO::FETCH_ASSOC);
            $itemData_List['monthlymonitoring_id'] = $chkExist_row['monthlymonitoringid'];
            $itemData_List['monitoring_ref_no'] = $chkExist_row['monitoring_ref_no'];

            //* ======== Update employee performed by,checked by,noted by ========
            if ($category == 'interlock') {
                $inTable = 'phd_monthly_monitoring_interlocking';
                $employee_responsible = 'interlocking_performed_by = ?, interlocking_performed_by_sign = ?, interlocking_checked_by = ?, interlocking_checked_by_sign = ?, interlocking_date_created = ?';
            } else if ($category == 'electric') {
                $inTable = 'phd_monthly_monitoring_electric';
                $employee_responsible = 'electric_performed_by = ?, electric_performed_by_sign = ?, electric_checked_by = ?, electric_checked_by_sign = ?, electric_date_created = ?';
            } else if ($category == 'emergency') {
                $inTable = 'phd_monthly_monitoring_emergency';
                $employee_responsible = 'emergency_performed_by = ?, emergency_performed_by_sign = ?, emergency_checked_by = ?, emergency_checked_by_sign = ?,emergency_date_created = ?';
            } else {
                $inTable = 'phd_monthly_monitoring_roomtemp';
                $employee_responsible = 'roomtemp_performed_by = ?, roomtemp_performed_by_sign = ?, roomtemp_checked_by = ?, roomtemp_checked_by_sign = ?, roomtemp_date_created = ?';
            }
            $sqlupdate = "UPDATE phd_monthly_monitoring_header SET " . $employee_responsible . " WHERE monthlymonitoringid = ?";
            $sqlupdate_stmt = $PHD->prepare($sqlupdate);
            $sqlupdate_stmt->execute([$performed_by, $performBySignature_row, $checked_by, $checkedBySignature_row, $currentDate, $chkExist_row['monthlymonitoringid']]);

            //* ======== Delete Details ========
            $sqldelete = "DELETE FROM " . $inTable . " WHERE monthlymonitoring_id = ?";
            $sqldelete_stmt = $PHD->prepare($sqldelete);
            $sqldelete_stmt->execute([$chkExist_row['monthlymonitoringid']]);
        } else {
            //* ======== Generate Monitoring Ref No ========
            $fetchRefNo = "SELECT monitoring_ref_no FROM phd_monthly_monitoring_ref_no";
            $fetchRefNo_stmt = $PHD->prepare($fetchRefNo);
            $fetchRefNo_stmt->execute();
            $fetchRefNo_row = $fetchRefNo_stmt->fetch(PDO::FETCH_ASSOC);
            $currYear = date('y');
            $getYear =  substr($fetchRefNo_row['monitoring_ref_no'], 5, 2);
            if ($currYear != $getYear) {
                $monitoring_ref_no = '0001-' . $currYear;
            } else {
                $currCount = substr($fetchRefNo_row['monitoring_ref_no'], 0, 4);
                $counter = intval($currCount) + 1;
                $monitoring_ref_no = str_pad($counter, 4, '0', STR_PAD_LEFT) . '-' . $currYear;
            }
            //* ======== Update Monitoring Ref No ========
            $updateRefno = "UPDATE phd_monthly_monitoring_ref_no SET monitoring_ref_no = :monitoring_ref_no";
            $updateRefno_stmt = $PHD->prepare($updateRefno);
            $updateRefno_stmt->bindParam(':monitoring_ref_no', $monitoring_ref_no);
            $updateRefno_stmt->execute();
            //* ======== Insert Data ========
            if ($category == 'interlock') {
                $employee_responsible = 'interlocking_performed_by,interlocking_performed_by_sign,interlocking_checked_by,interlocking_checked_by_sign,interlocking_date_created';
            } else if ($category == 'electric') {
                $employee_responsible = 'electric_performed_by,electric_performed_by_sign, electric_checked_by, electric_checked_by_sign,electric_date_created';
            } else if ($category == 'emergency') {
                $employee_responsible = 'emergency_performed_by,emergency_performed_by_sign,emergency_checked_by,emergency_checked_by_sign,emergency_date_created';
            } else {
                $employee_responsible = 'roomtemp_performed_by,roomtemp_performed_by_sign,roomtemp_checked_by,roomtemp_checked_by_sign,roomtemp_date_created';
            }
            $sqlstring = "INSERT INTO phd_monthly_monitoring_header(monthly_monitoring_header,date_created,monitoring_noted_by,monitoring_noted_by_sign,monitoring_ref_no," . $employee_responsible . ") 
                    VALUES(?,?,?,?,?,?,?,?,?,?) RETURNING monthlymonitoringid";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$monitoring_header, $currentDate, $noted_by, $NotedBySignature_row, $monitoring_ref_no, $performed_by, $performBySignature_row, $checked_by, $checkedBySignature_row, $currentDate]);

            $itemData_List['monthlymonitoring_id'] = $PHD->lastInsertId();
            $itemData_List['monitoring_ref_no'] = $monitoring_ref_no;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataInterlockingRudl($PHD, $interlock_category_name, $interlock_location_name, $interlock_status, $interlock_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate)
    {
        $sqlstring = "INSERT INTO phd_monthly_monitoring_interlocking(monthlymonitoring_id,interlock_category_name,interlock_location_name,interlock_status,interlock_remarks,date_created,monitoring_ref_no)
                VALUES(?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$monthlymonitoringid, $interlock_category_name, $interlock_location_name, $interlock_status, $interlock_remarks, $currentDate, $monitoring_ref_no]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteData($PHD, $monthlymonitoringid)
    {
        $sqlstring = "DELETE FROM phd_monthly_monitoring_header WHERE monthlymonitoringid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$monthlymonitoringid]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataFenceDetails($PHD, $electric_location_name, $electric_status, $electric_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate)
    {
        $sqlstring = "INSERT INTO phd_monthly_monitoring_electric(monthlymonitoring_id,electric_location_name,electric_status,electric_remarks,date_created,monitoring_ref_no)
                VALUES(?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$monthlymonitoringid, $electric_location_name, $electric_status, $electric_remarks, $currentDate, $monitoring_ref_no]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataEmergenceDetails($PHD, $emergency_switch, $emergency_status, $emergency_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate)
    {
        $sqlstring = "INSERT INTO phd_monthly_monitoring_emergency(monthlymonitoring_id,emergency_switch,emergency_status,emergency_remarks,date_created,monitoring_ref_no)
                VALUES(?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$monthlymonitoringid, $emergency_switch, $emergency_status, $emergency_remarks, $currentDate, $monitoring_ref_no]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataRoomtempDetails($PHD, $roomtemp_location_name, $roomtemp_reading1, $roomtemp_reading2, $roomtemp_temperature_alarm, $roomtemp_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate)
    {
        $sqlstring = "INSERT INTO phd_monthly_monitoring_roomtemp(monthlymonitoring_id,roomtemp_location_name,roomtemp_reading1,roomtemp_reading2,roomtemp_temperature_alarm,roomtemp_remarks,date_created,monitoring_ref_no)
                VALUES(?,?,?,?,?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$monthlymonitoringid, $roomtemp_location_name, $roomtemp_reading1, $roomtemp_reading2, $roomtemp_temperature_alarm, $roomtemp_remarks, $currentDate, $monitoring_ref_no]);
        $PHD = null; //* ======== Close Connection ========
    }
}
