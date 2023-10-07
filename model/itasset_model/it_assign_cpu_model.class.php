<?php
date_default_timezone_set('Asia/Manila');
class ITAssignCpu
{
    public function sqlQuery($sqlstring, $connection)
    {
        $data_base64 = base64_encode($sqlstring);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $connection);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
        $json_response = curl_exec($curl);
        //* ====== Close Connection ======
        curl_close($curl);
        return json_decode($json_response, true);
    }
    public function loadTableAssignCPU($php_fetch_itasset_api,  $search)
    {
        //* =========== Variables ===========
        $columns = array(
            0 => 'employee',
            1 => 'cpu_control_no',
            2 => 'description',
            3 => 'location',
            4 => 'date_updated',
            5 => 'active_pc',
        );

        //* =========== Fetch Total Record Data ===========
        $query = "SELECT * FROM tblit_assign_cpu ";
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalRecord = array_sum(array_map("count", $data_result));

        //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($search)) {
            $query .= "AND (employee ILIKE '%{$search}%'
                        OR cpu_control_no ILIKE '%{$search}%'
                        OR description ILIKE '%{$search}%'
                        OR location ILIKE '%{$search}%'
                        OR TO_CHAR(date_updated, 'YYYY-MM-DD') ILIKE '%{$search}%') ";
        }
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalFilteredRecord = array_sum(array_map("count", $data_result));

        //* ======== Ordering ========
        $query .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $data = array();

        //* ======== Record Data Array ========
        // foreach ($rowData as $row) {
        foreach ($data_result['data'] as $row) {
            $data[] = array(
                $row['employee'],
                $row['cpu_control_no'],
                $row['description'],
                $row['location'],
                $row['date_updated'],
                $row['active_pc'] == 'true' ? '<span class="badge bg-success col-sm-12">Active</span>' : '<span class="badge bg-danger col-sm-12">Not active</span>',
                '<button class="btn btn-danger" onclick="editAssignCPU(' . $row['cpu_control_no_id'] . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                        <i class="fa-solid fa-file-pen fa-bounce"></i>
                    </button>
                    <button class="btn btn-dark" onclick="deleteCPU(' . $row['cpu_control_no_id'] . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                        <i class="fa-solid fa-trash fa-shake"></i>
                    </button>'
            );
        }
        //* ====== Output Data Array ======
        $json = array(
            "draw"                    =>  intval($_POST["draw"]),
            "iTotalRecords"           =>  $totalRecord,
            "iTotalDisplayRecords"    =>  $totalFilteredRecord,
            "data"                    =>  $data
        );

        //* ====== Return Data as JSON Format ======
        return json_encode($json);
    }
    public function loadInputData($php_fetch_bannerweb_api)
    {
        $department = '<option value="" selected>Select a Department:</option>';
        $query = "SELECT department, dept_code
                        FROM bpi_department;";
        $data_result = self::sqlQuery($query, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $department .= '<option value="' . $row['dept_code'] . '">' . $row['department'] . '</option>';
        }
        // *========== Input Data JSON Format ==========
        $data = array(
            "department" => $department
        );
        return json_encode($data);
    }
    public function loadEmployee($php_fetch_bannerweb_api, $deptCode)
    {
        $employee = '<option value="" selected>Select an Employee:</option>';
        $query = "SELECT DISTINCT emp_fn||' '||emp_sn AS fullname 
                        FROM prl_employee 
                      WHERE emp_stat NOT IN ('Resigned','Terminated','End Contract','Project Completion', 'Retired', 'Resigned Non-compliance')
                      AND dept_code = '{$deptCode}'
                      ORDER BY 1;";
        $data_result = self::sqlQuery($query, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $employee .= '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
        }
        return $employee;
    }
    public function newAssignCPU($php_insert_itasset_api, $activePc, $php_update_itasset_api, $cpuControlNumber, $employee, $description, $location, $dateToday)
    {
        $query = "INSERT INTO tblit_assign_cpu(cpu_control_no, employee, description, location, date_updated, active_pc)
                        VALUES('{$cpuControlNumber}', '{$employee}', '{$description}', '{$location}', '{$dateToday}', '{$activePc}');";
        self::sqlQuery($query, $php_insert_itasset_api);
        $type = strtolower(substr($cpuControlNumber, 0, 2));
        $control_number = substr($cpuControlNumber, 3, 6);
        $field = $type == 'pc' ? 'pc_control_no' : 'lt_control_no';
        $sqlstring = "UPDATE tblit_control_no SET {$field} = '{$control_number}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);
        return true;
    }
    public function fetchByIdAssignCPU($php_fetch_itasset_api, $php_fetch_bannerweb_api, $cpuID)
    {
        $query = "SELECT * FROM tblit_assign_cpu WHERE cpu_control_no_id = '{$cpuID}';";
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $employee = $row['employee'];
            $cpu_control_no = $row['cpu_control_no'];
            $description = $row['description'];
            $location = $row['location'];
        }
        $queryDeptCode = "SELECT DISTINCT dept_code FROM prl_employee where emp_fn||' '||emp_sn = '{$employee}';";
        $data_result = self::sqlQuery($queryDeptCode, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $deptCode = $row['dept_code'];
        }
        $data = array(
            "deptCode" => $deptCode,
            "employee" => $employee,
            "cpuControlNumber" => $cpu_control_no,
            "description" => $description,
            "location" => $location
        );
        return json_encode($data);
    }
    public function editAssignCPU($php_update_itasset_api, $cpuControlNumber, $employee, $description, $location, $dateToday)
    {
        $query = "UPDATE tblit_assign_cpu SET employee = '{$employee}', description = '{$description}', location = '{$location}',date_updated = '{$dateToday}'
                    WHERE cpu_control_no = '{$cpuControlNumber}';";
        self::sqlQuery($query, $php_update_itasset_api);
        return true;
    }
    public function setTypeControlNumber($php_fetch_itasset_api, $type)
    {
        $field = $type == 'PC' ? 'pc_control_no' : 'lt_control_no';
        $type = strtolower($type);
        $sqlstring = "SELECT * FROM tblit_control_no";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $cpuControlNumberYear = substr($row[$field], 0, 2);
            $cpuControlNumberTrack = substr($row[$field], 3);
            if ($cpuControlNumberYear == date('y')) {
                return strtoUpper($type) . '-' . date('y') . '-' . sprintf("%03d", $cpuControlNumberTrack + 1);
            } else {
                return strtoUpper($type) . '-' . date('y') . '-' . "001";
            }
        }
    }
    public function deleteCPU($php_update_itasset_api, $id)
    {
        $sqlstring = "DELETE FROM tblit_assign_cpu WHERE cpu_control_no_id = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);
    }
}
