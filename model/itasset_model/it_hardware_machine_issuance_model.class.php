<?php
date_default_timezone_set('Asia/Manila');
class ITHarwareMachineIssued
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

    public function loadTableMachineIssuance($php_fetch_itasset_api, $filterValue, $machine, $search)
    {
        $filterValue = !empty($filterValue) ? $filterValue : "Issued', 'Retrieved', 'Returned', 'Defective";
        $columns = array(
            0 => 'machine',
            1 => 'item',
            2 => 'description',
            3 => 'issued_by',
            4 => 'date_issued',
            5 => 'status',
        );
        //* =========== Fetch Total Record Data ===========
        $query =
            "SELECT * FROM tblit_hardware_issuance_machine
                        WHERE ";
        if ($machine != '') {
            $query .= "machine = '{$machine}' AND status IN ('{$filterValue}') ";
        } else {
            $query .= "status IN ('{$filterValue}') ";
        }
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalRecord = array_sum(array_map("count", $data_result));
        if (!empty($search)) { //* =========== Fetch Total Filtered Record Data ===========
            $query .= "AND (machine ILIKE '%{$search}%'
                    OR item ILIKE '%{$search}%'
                    OR description ILIKE '%{$search}%'
                    OR issued_by ILIKE '%{$search}%'
                    OR status ILIKE '%{$search}%'
                    OR TO_CHAR(date_issued, 'YYYY-MM-DD' ) ILIKE '%{$search}%') ";
        }
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $totalFilteredRecord = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $query .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data = array();
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        // //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $data[] = array(
                $row['machine'],
                $row['item'],
                $row['description'],
                $row['issued_by'],
                $row['date_issued'],
                self::badgeStatus($row['status']),
                self::actionButton($row['status'], $row['hardware_issuance_machine_id'])
            );
        }
        $json = array( //* ====== Output Data Array ======
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
        $sqltsring = "SELECT department, dept_code
                        FROM bpi_department;";
        $data_result = self::sqlQuery($sqltsring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $department .= '<option value="' . $row['dept_code'] . '">' . $row['department'] . '</option>';
        }
        //* =========== Get the list of IT employees fullname ===========
        $sqlstringFirstname = "SELECT DISTINCT emp_fn||' '||emp_sn AS fullname 
                        FROM prl_employee 
                      WHERE emp_stat NOT IN ('Resigned','Terminated','End Contract','Project Completion', 'Retired', 'Resigned Non-compliance')
                      AND dept_code = 'ITD'
                      ORDER BY 1;";
        $data_result = self::sqlQuery($sqlstringFirstname, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $issuer .= '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
        }
        //* =========== Get the list of machines ===========
        $sqlstringMachine = "SELECT * FROM tblit_machines ORDER BY 2;";
        $data_result = self::sqlQuery($sqlstringMachine, $php_fetch_itasset_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $machines .= '<option value="' . $row['machines'] . '">' . $row['machines'] . '</option>';
            }
        }
        //* =========== Get the list of machines ===========
        $query = "SELECT * FROM tblit_items ORDER BY 2;";
        $data_result = self::sqlQuery($query, $php_fetch_itasset_api);
        $row_count = array_sum(array_map("count", $data_result));
        //* ======== Prepare Array ========
        if ($row_count > 0) {
            foreach ($data_result['data'] as $row) {
                $items .= '<option value="' . $row['items'] . '">' . $row['items'] . '</option>';
            }
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
    public function newMachineIssuance($php_fetch_itasset_api, $php_insert_itasset_api,  $WHPO, $machine, $item, $barcodeNumber, $issuer, $dateIssued)
    {
        $query = "SELECT bpbarcodegen, description FROM apinvoicedetail
                INNER JOIN tblpw_itembarcode_gen ON apinvoicedetail.keybarcode = tblpw_itembarcode_gen.bpbarcode
                INNER JOIN banner_warehouse_inventory_non_production ON apinvoicedetail.itemcode = banner_warehouse_inventory_non_production.itemcode
                WHERE bpbarcodegen = '{$barcodeNumber}';";
        $stmt = $WHPO->prepare($query);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $rowData = $stmt->fetchAll();
        if ($rowCount > 0) {
            foreach ($rowData as $rowData) {
                $description = trim($rowData['description']);
            }
            $sqlstring = "SELECT * FROM tblit_hardware_issuance_machine WHERE barcode = '{$barcodeNumber}';";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
            $rowCount = array_sum(array_map("count", $data_result));
            $sqlstringEmployee = "SELECT * FROM tblit_hardware_issuance_employee WHERE barcode = '{$barcodeNumber}';";
            $data_result = self::sqlQuery($sqlstringEmployee, $php_fetch_itasset_api);
            $rowCountEmployee = array_sum(array_map("count", $data_result));
            if ($rowCount > 0 || $rowCountEmployee > 0) {
                echo "This item has already been issued.";
            } else {
                $sqlstring = "INSERT INTO tblit_hardware_issuance_machine(barcode, machine, item, description, issued_by, date_issued, status)
                            VALUES('{$barcodeNumber}', '{$machine}', '{$item}', '{$description}', '{$issuer}', '{$dateIssued}', 'Issued');";
                self::sqlQuery($sqlstring, $php_insert_itasset_api);
                echo true;
            }
        } else {
            echo "No Matching Record Found for Barcode Number!";
        }
        $WHPO = null;
    }
    public function actionMachineIssuance($php_update_itasset_api, $id, $status)
    {
        switch ($status) {
            case 'Retrieve':
                $query = "UPDATE tblit_hardware_issuance_machine SET status = 'Retrieved'  WHERE hardware_issuance_machine_id = '{$id}';";
                $msg = "Retrieved";
                break;
            case 'Defective':
                $query = "UPDATE tblit_hardware_issuance_machine SET status = 'Defective' WHERE hardware_issuance_machine_id = '{$id}';";
                $msg = "Moved to Defective";
                break;
            case 'Return':
                $query = "UPDATE tblit_hardware_issuance_machine SET status = 'Returned' WHERE hardware_issuance_machine_id = '{$id}';";
                $msg = "Returned to Warehouse";
                break;
            case 'Delete':
                $query = "DELETE FROM tblit_hardware_issuance_machine WHERE hardware_issuance_machine_id = '{$id}';";
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
                WHERE bpbarcodegen = '{$barcode}'";
        $stmt = $WHPO->prepare($query);
        $stmt->execute();
        $result_res = $stmt->fetchAll();
        $rowCount = $stmt->rowCount();
        if ($rowCount) {
            foreach ($result_res as $row) {
                $description = trim($row['description']);
            }
        } else {
            $description = '';
        }
        return $description;
    }
}
