<?php
date_default_timezone_set('Asia/Manila');
class ITHarwareIssued
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
    public function actionButton($status, $id)
    {
        switch ($status) {
            case 'Issued':
                $result  = '<button class="btn btn-dark" onclick="actionHardwareIssuance(' . $id . ', \'Retrieve\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Retrieve">
                                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                            </button>
                            <button class="btn btn-light text-danger border border-left-danger" onclick="actionHardwareIssuance(' . $id . ', \'Defective\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Defective">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </button>
                            <button class="btn btn-danger" onclick="actionHardwareIssuance(' . $id . ', \'Delete\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>';
                break;
            case 'Retrieved':
                $result  = '<button class="btn btn-light border border-left-dark" onclick="actionHardwareIssuance(' . $id . ', \'Return\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Return to Warehouse">
                                <i class="fa-solid fa-warehouse"></i>
                            </button>
                            <button class="btn btn-light text-danger border border-left-danger" onclick="actionHardwareIssuance(' . $id . ', \'Defective\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Defective">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </button>
                            <button class="btn btn-danger" onclick="actionHardwareIssuance(' . $id . ', \'Delete\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>';
                break;
            case 'Defective':
                $result  = '<span class="badge rounded-pill shadow text-bg-danger w-100">No Action</span>';
                break;
            case 'Recycled':
                $result  = '<span class="badge rounded-pill shadow text-bg-danger w-100">No Action</span>';
                break;
            case 'Returned':
                $result  = '<span class="badge rounded-pill shadow text-bg-danger w-100">No Action</span>';
                break;
        }
        return $result;
    }
    public function badgeStatus($status)
    {
        switch ($status) {
            case 'Issued':
                $result = '<span class="badge rounded-pill shadow text-bg-dark w-100">' . $status . '</span>';
                break;
            case 'Retrieved':
                $result = '<span class="badge rounded-pill shadow text-bg-secondary w-100">' . $status . '</span>';
                break;
            case 'Returned':
                $result = '<span class="badge rounded-pill shadow text-bg-light w-100">' . $status . '</span>';
                break;
            case 'Defective':
                $result = '<span class="badge rounded-pill shadow text-bg-danger w-100">' . $status . '</span>';
                break;
            case 'Recycled':
                $result = '<span class="badge rounded-pill shadow text-bg-dark w-100">' . $status . '</span>';
                break;
        }
        return $result;
    }
    public function loadTableHardwareIssuance($php_fetch_itasset_api, $filterValue, $controlno, $search)
    {
        $columns = array(
            0 => 'employee',
            1 => 'cpu_control_no',
            2 => 'description',
            3 => 'issued_by',
            4 => 'status',
            5 => 'date_issued',
        );
        //* =========== Fetch Total Record Data ===========
        $query = "SELECT hardware_issuance_employee_id, tblit_assign_cpu.employee, barcode, tblit_assign_cpu.cpu_control_no, tblit_hardware_issuance_employee.description, issued_by, date_issued, status 
                        FROM tblit_hardware_issuance_employee 
                        INNER JOIN tblit_assign_cpu 
                        ON tblit_assign_cpu.cpu_control_no_id = tblit_hardware_issuance_employee.cpu_control_no_id
                        WHERE ";
        if ($controlno != '') {
            $query .= "tblit_assign_cpu.cpu_control_no = '{$controlno}' AND status IN ('{$filterValue}') ";
        } else {
            $query .= "status IN ('{$filterValue}') ";
        }
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalRecord = array_sum(array_map("count", $data_result));

        //* =========== Fetch Total Filtered Record Data ===========
        if (!empty($search)) {
            $query .= "AND (employee ILIKE '%{$search}%'
                    OR barcode ILIKE '%{$search}%'
                    OR cpu_control_no ILIKE '%{$search}%'
                    OR tblit_hardware_issuance_employee.description ILIKE '%{$search}%'
                    OR issued_by ILIKE '%{$search}%'
                    OR TO_CHAR(date_issued, 'YYYY-MM-DD' ) ILIKE '%{$search}%') ";
        }
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalFilteredRecord = array_sum(array_map("count", $data_result));

        //* ======== Ordering ========
        $query .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $data = array();

        //* ======== Record Data Array ========
        foreach ($data_result['data'] as $row) {
            $data[] = array(
                $row['employee'],
                $row['cpu_control_no'],
                $row['description'],
                $row['issued_by'],
                $row['date_issued'],
                self::badgeStatus($row['status']),
                self::actionButton($row['status'], $row['hardware_issuance_employee_id'])
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
    public function loadInputData($php_fetch_bannerweb_api, $php_fetch_itasset_api)
    {
        $department = '<option value="" selected>Select a Department:</option>';
        $issuer = '<option value="" selected>Select an Issuer:</option>';
        $machines = '<option value="" selected>Select a Machine:</option>';
        $items = '<option value="" selected>Select an item:</option>';
        // *========== Department ==========
        $query = "SELECT department, dept_code
                        FROM bpi_department";
        $data_result = self::sqlQuery($query, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $department .= '<option value="' . $row['dept_code'] . '">' . $row['department'] . '</option>';
        }

        //* =========== Get the list of IT employees fullname ===========
        $queryFirstname = "SELECT DISTINCT emp_fn||' '||emp_sn AS fullname 
                        FROM prl_employee 
                      WHERE emp_stat NOT IN ('Resigned','Terminated','End Contract','Project Completion', 'Retired', 'Resigned Non-compliance')
                      AND dept_code = 'ITD'
                      ORDER BY 1";
        $data_result = self::sqlQuery($queryFirstname, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $issuer .= '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
        }

        //* =========== Get the list of machines ===========
        $queryMachine = "SELECT * FROM tblit_machines ORDER BY 2;";
        $data_result = self::sqlQuery($queryMachine, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $machines .= '<option value="' . $row['machines'] . '">' . $row['machines'] . '</option>';
        }

        //* =========== Get the list of machines ===========
        $queryItems = "SELECT * FROM tblit_items ORDER BY 2;";
        $data_result = self::sqlQuery($queryItems, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $items .= '<option value="' . $row['items'] . '">' . $row['items'] . '</option>';
        }

        // *========== Input Data JSON Format ==========
        $data = array(
            "issuer" => $issuer,
            "department" => $department,
            "machines" => $machines,
            "items" => $items
        );

        return json_encode($data);
    }
    public function loadEmployee($php_fetch_bannerweb_api, $deptCode)
    {
        $employee = '<option value="" selected>Select an Employee:</option>';
        $deptCode = $_POST['deptCode']; //* =========== Variable ===========
        //* =========== Get the list of employees fullname ===========
        $query = "SELECT DISTINCT emp_fn||' '||emp_sn AS fullname 
                        FROM prl_employee 
                      WHERE emp_stat NOT IN ('Resigned', 'Terminated', 'End Contract', 'Project Completion', 'Retired', 'Resigned Non-compliance')
                      AND dept_code = '{$deptCode}'
                      ORDER BY 1;";
        $data_result = self::sqlQuery($query, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $employee .= '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
        }
        return $employee;
    }
    public function loadCpuControlNo($php_fetch_itasset_api,  $employeeName)
    {
        $cpuControlNumber = '<option value="" selected>Select a CPU Control No. :</option>';
        //* =========== Get the list of employees fullname ===========
        $query = "SELECT cpu_control_no FROM tblit_assign_cpu WHERE employee = '{$employeeName}';";
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $cpuControlNumber .= '<option value="' . $row['cpu_control_no'] . '">' . $row['cpu_control_no'] . '</option>';
        }
        return $cpuControlNumber;
    }
    public function newHardwareIssuance($php_fetch_itasset_api, $php_insert_itasset_api,  $WHPO, $cpuControlNumber, $item, $barcodeNumber, $issuer, $dateIssued)
    {
        $queryControl = "SELECT cpu_control_no_id FROM tblit_assign_cpu WHERE cpu_control_no = '{$cpuControlNumber}'";
        // $stmt = $WHPO->prepare($queryControl);
        // $stmt->execute();
        // $result_Res = $stmt->fetchAll();
        // $row_count = $stmt->rowCount();
        $data_result = self::sqlQuery($queryControl, $php_fetch_itasset_api);
        $rowCount = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($rowCount > 0) {
            foreach ($data_result['data'] as $rowData) {
                $cpuControlNoId = trim($rowData['cpu_control_no_id']);
            }
        }
        $query = "SELECT bpbarcodegen, description FROM apinvoicedetail
                INNER JOIN tblpw_itembarcode_gen ON apinvoicedetail.keybarcode = tblpw_itembarcode_gen.bpbarcode
                INNER JOIN banner_warehouse_inventory_non_production ON apinvoicedetail.itemcode = banner_warehouse_inventory_non_production.itemcode
                WHERE bpbarcodegen = '{$barcodeNumber}';";
        $stmt = $WHPO->prepare($query);
        $stmt->execute();
        $result_Res = $stmt->fetchAll();
        $row_count = $stmt->rowCount();
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($result_Res as $rowData) {
                $description = trim($rowData['description']);
            }
            $sqlstring = "SELECT * FROM tblit_hardware_issuance_machine WHERE barcode = '{$barcodeNumber}'";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
            $rowCount = array_sum(array_map("count", $data_result));
            $sqlstringEmployee = "SELECT * FROM tblit_hardware_issuance_employee WHERE barcode = '{$barcodeNumber}'";
            $data_result = self::sqlQuery($sqlstringEmployee, $php_fetch_itasset_api);
            $rowCountEmployee = array_sum(array_map("count", $data_result));
            if ($rowCount > 0 || $rowCountEmployee > 0) {
                echo "This item has already been issued.";
            } else {
                $sqlstring = "INSERT INTO tblit_hardware_issuance_employee(
                                    barcode,
                                    issued_by,
                                    date_issued,
                                    cpu_control_no_id,
                                    description,
                                    status,
                                    item
                                ) 
                                VALUES(
                                    '{$barcodeNumber}',
                                    '{$issuer}',
                                    '{$dateIssued}',
                                    '{$cpuControlNoId}',
                                    '{$description}',
                                    'Issued',
                                    '$item'
                                );";
                self::sqlQuery($sqlstring, $php_insert_itasset_api);
                echo true;
            }
        } else {
            echo "No Matching Record Found for Barcode Number!";
        }
        $WHPO = null;
    }
    public function actionHardwareIssuance($php_update_itasset_api,  $id, $status)
    {
        switch ($status) {
            case 'Retrieve':
                $query = "UPDATE tblit_hardware_issuance_employee SET status = 'Retrieved'  WHERE hardware_issuance_employee_id = '{$id}';";
                $msg = "Retrieved";
                break;
            case 'Defective':
                $query = "UPDATE tblit_hardware_issuance_employee SET status = 'Defective' WHERE hardware_issuance_employee_id = '{$id}';";
                $msg = "Moved to Defective";
                break;
            case 'Return':
                $query = "UPDATE tblit_hardware_issuance_employee SET status = 'Returned' WHERE hardware_issuance_employee_id = '{$id}';";
                $msg = "Returned to Warehouse";
                break;
            case 'Delete':
                $query = "DELETE FROM tblit_hardware_issuance_employee WHERE hardware_issuance_employee_id = '{$id}';";
                $msg = "Deleted";
                break;
        }
        self::sqlQuery($query, $php_update_itasset_api);
        return $msg;
    }
    public function getDescription($WHPO, $barcode)
    {
        $query = "SELECT bpbarcodegen, description FROM apinvoicedetail
                INNER JOIN tblpw_itembarcode_gen ON apinvoicedetail.keybarcode = tblpw_itembarcode_gen.bpbarcode
                INNER JOIN banner_warehouse_inventory_non_production ON apinvoicedetail.itemcode = banner_warehouse_inventory_non_production.itemcode
                WHERE bpbarcodegen = '{$barcode}';";
        $stmt = $WHPO->prepare($query);
        $stmt->execute();
        $result_Res = $stmt->fetchAll();
        $row_count = $stmt->rowCount();
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($result_Res as $rowData) {
                $description = trim($rowData['description']);
            }
        }
        return $description;
    }
    public function getItemControlNumber($php_fetch_itasset_api, $item)
    {
        $items = strtolower(str_replace(" ", "_", $item));
        $itemsFilter = $items == 'cd-rw/dvd' ? 'dvd' : $items;
        $sqlstring = "SELECT $itemsFilter FROM tblit_control_no_item";;
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $cpuControlNumberYear = substr($row[$itemsFilter], 0, 3);
            $cpuControlNumberTrack = substr($row[$itemsFilter], 3);
            return $cpuControlNumberYear .  sprintf("%03d", $cpuControlNumberTrack + 1);
        }
    }
}
