<?php
include '../../configuration/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');
$ITA = $conn->db_conn_it_asset(); //* IT Asset Database connection
$BannerWeb = $conn->db_conn_bannerweb(); //* Banner Web Database connection

// todo whpo connection


function actionButton($status, $id)
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
            $result  = '<button class="btn btn-dark" onclick="actionHardwareIssuance(' . $id . ', \'Print\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print">
                            <i class="fa-solid fa-file-pdf"></i>
                        </button>';
            break;

        case 'Returned':
            $result  = '<span class="text-muted">No Action</span>';
            break;
    }
    return $result;
}

function badgeStatus($status)
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
    }
    return $result;
}


if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadTableHardwareIssuance':
            //* =========== Variables ===========
            $search = $_POST['search']['value'];
            $filterValue = trim($_POST['filterValue']);
            $filterValue = !empty($filterValue) ? $filterValue : "Issued', 'Retrieved', 'Returned', 'Defective";
            $columns = array(
                0 => 'employee',
                1 => 'cpu_control_no',
                2 => 'description',
                3 => 'issued_by',
                4 => 'status',
                5 => 'date_issued',
            );

            //* =========== Fetch Total Record Data ===========
            $query = "SELECT t2.employee, t2.cpu_control_no, t1.*
                        FROM tblit_hardware_issuance_employee AS t1
                      INNER JOIN tblit_cpu_control_no AS t2
                        ON t2.cpu_control_no_id = t1.cpu_control_no_id
                      WHERE status IN ('{$filterValue}') ";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $totalRecord = $stmt->rowCount();

            //* =========== Fetch Total Filtered Record Data ===========
            if (!empty($search)) {
                $query .= "AND (employee ILIKE '%{$search}%'
                    OR employee_barcode ILIKE '%{$search}%'
                    OR cpu_control_no ILIKE '%{$search}%'
                    OR t1.description ILIKE '%{$search}%'
                    OR issued_by ILIKE '%{$search}%'
                    OR TO_CHAR(date_issued, 'YYYY-MM-DD' ) ILIKE '%{$search}%') ";
            }
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $totalFilteredRecord = $stmt->rowCount();

            //* ======== Ordering ========
            $query .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            //* ======== Record Data Array ========
            foreach ($rowData as $row) {
                $data[] = array(
                    $row['employee'],
                    $row['cpu_control_no'],
                    $row['description'],
                    $row['issued_by'],
                    $row['date_issued'],
                    badgeStatus($row['status']),
                    actionButton($row['status'], $row['hardware_issuance_employee_id'])
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
            echo json_encode($json);
            $ITA = null;
            break;

        case 'loadInputData':

            // *========== Department ==========
            $query = "SELECT department, dept_code
                        FROM bpi_department";
            $stmt = $BannerWeb->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $department = '<option value="" selected>Select a Department:</option>';
            foreach ($resultRow as $row) {
                $department .= '<option value="' . $row['dept_code'] . '">' . $row['department'] . '</option>';
            }

            //* =========== Get the list of IT employees fullname ===========
            $query = "SELECT DISTINCT emp_fn||' '||emp_sn AS fullname 
                        FROM prl_employee 
                      WHERE emp_stat NOT IN ('Resigned','Terminated','End Contract','Project Completion', 'Retired', 'Resigned Non-compliance')
                      AND dept_code = 'ITD'
                      ORDER BY 1";
            $stmt = $BannerWeb->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $issuer = '<option value="" selected>Select an Issuer:</option>';
            foreach ($resultRow as $row) {
                $issuer .= '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
            }

            //* =========== Get the list of machines ===========
            $query = "SELECT * FROM tblit_machines ORDER BY 2";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $machines = '<option value="" selected>Select a Machine:</option>';
            foreach ($resultRow as $row) {
                $machines .= '<option value="' . $row['machines'] . '">' . $row['machines'] . '</option>';
            }

            //* =========== Get the list of machines ===========
            $query = "SELECT * FROM tblit_items ORDER BY 2";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $items = '<option value="" selected>Select an item:</option>';
            foreach ($resultRow as $row) {
                $items .= '<option value="' . $row['items'] . '">' . $row['items'] . '</option>';
            }

            // *========== Input Data JSON Format ==========
            $data = array(
                "issuer" => $issuer,
                "department" => $department,
                "machines" => $machines,
                "items" => $items
            );

            echo json_encode($data);
            $ITA = null;
            $BannerWeb = null;
            break;

        case 'loadEmployee':
            //* =========== Variable ===========
            $deptCode = $_POST['deptCode'];

            //* =========== Get the list of employees fullname ===========
            $query = "SELECT DISTINCT emp_fn||' '||emp_sn AS fullname 
                        FROM prl_employee 
                      WHERE emp_stat NOT IN ('Resigned', 'Terminated', 'End Contract', 'Project Completion', 'Retired', 'Resigned Non-compliance')
                      AND dept_code = '{$deptCode}'
                      ORDER BY 1";
            $stmt = $BannerWeb->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $employee = '<option value="" selected>Select an Employee:</option>';

            foreach ($resultRow as $row) {
                $employee .= '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
            }

            echo $employee;
            $ITA = null;
            break;

        case 'loadCpuControlNo':
            //* =========== Variable ===========
            $employeeName = $_POST['employeeName'];

            //* =========== Get the list of employees fullname ===========
            $query = "SELECT cpu_control_no FROM tblit_cpu_control_no WHERE employee = '{$employeeName}'";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $cpuControlNumber = '<option value="" selected>Select a CPU Control No. :</option>';

            foreach ($resultRow as $row) {
                $cpuControlNumber .= '<option value="' . $row['cpu_control_no'] . '">' . $row['cpu_control_no'] . '</option>';
            }

            echo $cpuControlNumber;
            $ITA = null;
            break;

        case 'newHardwareIssuance':
            $cpuControlNumber = trim($_POST['cpuControlNumber']);
            $barcodeNumber = trim($_POST['barcodeNumber']);
            $issuer = trim($_POST['issuer']);
            $dateIssued = trim($_POST['dateIssued']);

            //Query for the employee and CPU control number.
            $query = "SELECT cpu_control_no_id FROM tblit_cpu_control_no WHERE cpu_control_no = '{$cpuControlNumber}'";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            $cpuControlNoId = trim($rowData['cpu_control_no_id']);

            // Query for getting the description and inventory measure for an item.
            $query = "SELECT bpbarcodegen, description FROM apinvoicedetail
                INNER JOIN tblpw_itembarcode_gen ON apinvoicedetail.keybarcode = tblpw_itembarcode_gen.bpbarcode
                INNER JOIN banner_warehouse_inventory_non_production ON apinvoicedetail.itemcode = banner_warehouse_inventory_non_production.itemcode
                WHERE bpbarcodegen = '{$barcodeNumber}'";
            $stmt = $WHPO->prepare($query);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if the barcode number has no matching data.
            if ($rowCount > 0) {
                $description = trim($rowData['description']);

                $sqlstring = "SELECT * FROM tblit_hardware_issuance_employee WHERE employee_barcode = '{$barcodeNumber}'";
                $result_stmt = $ITA->prepare($sqlstring);
                $result_stmt->execute();
                $rowCount = $result_stmt->rowCount();

                // Check if the data already exists.
                if ($rowCount == 0) {

                    // Query for the insert of an issued item.
                    $sqlstring = "INSERT INTO tblit_hardware_issuance_employee(
                                    employee_barcode,
                                    issued_by,
                                    date_issued,
                                    cpu_control_no_id,
                                    description,
                                    status
                                ) 
                                VALUES(
                                    '{$barcodeNumber}',
                                    '{$issuer}',
                                    '{$dateIssued}',
                                    '{$cpuControlNoId}',
                                    '{$description}',
                                    'Issued'
                                )";
                    $result_stmt = $ITA->prepare($sqlstring);
                    $result_stmt->execute();
                    echo true;
                } else {
                    echo "This item has already been issued.";
                }
            } else {
                echo "No Matching Record Found for Barcode Number!";
            }

            $WHPO = null;
            $ITA = null;
            break;

        case 'actionHardwareIssuance':
            $id = trim($_POST['id']);
            $status = trim($_POST['status']);

            switch ($status) {
                case 'Retrieve':
                    $query = "UPDATE tblit_hardware_issuance_employee SET status = 'Retrieved'  WHERE hardware_issuance_employee_id = '{$id}'";
                    $msg = "Retrieved";
                    break;
                case 'Defective':
                    $query = "UPDATE tblit_hardware_issuance_employee SET status = 'Defective' WHERE hardware_issuance_employee_id = '{$id}'";
                    $msg = "Moved to Defective";
                    break;
                case 'Return':
                    $query = "UPDATE tblit_hardware_issuance_employee SET status = 'Returned' WHERE hardware_issuance_employee_id = '{$id}'";
                    $msg = "Returned to Warehouse";
                    break;
                case 'Delete':
                    $query = "DELETE FROM tblit_hardware_issuance_employee WHERE hardware_issuance_employee_id = '{$id}'";
                    $msg = "Deleted";
                    break;
                    // case 'Print':
                    //     $query = "";
                    //     break;
            }
            $stmt = $ITA->prepare($query);
            $stmt->execute();

            echo $msg;
            $ITA = null;
            break;

        case 'loadTableMachineIssuance':
            //* =========== Variables ===========
            $search = $_POST['search']['value'];
            $filterValue = trim($_POST['filterValue']);
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
            $query = "SELECT * FROM tblit_hardware_issuance_machine
                        WHERE status IN ('{$filterValue}') ";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $totalRecord = $stmt->rowCount();

            //* =========== Fetch Total Filtered Record Data ===========
            if (!empty($search)) {
                $query .= "AND (machine ILIKE '%{$search}%'
                    OR item ILIKE '%{$search}%'
                    OR description ILIKE '%{$search}%'
                    OR issued_by ILIKE '%{$search}%'
                    OR status ILIKE '%{$search}%'
                    OR TO_CHAR(date_issued, 'YYYY-MM-DD' ) ILIKE '%{$search}%') ";
            }
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $totalFilteredRecord = $stmt->rowCount();

            //* ======== Ordering ========
            $query .= "ORDER BY {$columns[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']}";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = array();

            //* ======== Record Data Array ========
            foreach ($rowData as $row) {
                $data[] = array(
                    $row['machine'],
                    $row['item'],
                    $row['description'],
                    $row['issued_by'],
                    $row['date_issued'],
                    badgeStatus($row['status']),
                    actionButton($row['status'], $row['hardware_issuance_machine_id'])
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
            echo json_encode($json);
            $ITA = null;
            break;

        case 'newMachineIssuance':
            $machine = trim($_POST['machine']);
            $item = trim($_POST['item']);
            $description = trim($_POST['description']);
            $issuer = trim($_POST['issuer']);
            $dateIssued = trim($_POST['dateIssued']);

            $query = "INSERT INTO tblit_hardware_issuance_machine(machine, item, description, issued_by, date_issued, status)
                        VALUES('{$machine}', '{$item}', {$ITA->quote($description)}, '{$issuer}', '{$dateIssued}', 'Issued')";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            echo true;

            $ITA = null;
            break;

        case 'actionMachineIssuance':
            $id = trim($_POST['id']);
            $status = trim($_POST['status']);

            switch ($status) {
                case 'Retrieve':
                    $query = "UPDATE tblit_hardware_issuance_machine SET status = 'Retrieved'  WHERE hardware_issuance_machine_id = '{$id}'";
                    $msg = "Retrieved";
                    break;
                case 'Defective':
                    $query = "UPDATE tblit_hardware_issuance_machine SET status = 'Defective' WHERE hardware_issuance_machine_id = '{$id}'";
                    $msg = "Moved to Defective";
                    break;
                case 'Return':
                    $query = "UPDATE tblit_hardware_issuance_machine SET status = 'Returned' WHERE hardware_issuance_machine_id = '{$id}'";
                    $msg = "Returned to Warehouse";
                    break;
                case 'Delete':
                    $query = "DELETE FROM tblit_hardware_issuance_machine WHERE hardware_issuance_machine_id = '{$id}'";
                    $msg = "Deleted";
                    break;
                    // case 'Print':
                    //     $query = "";
                    //     break;
            }
            $stmt = $ITA->prepare($query);
            $stmt->execute();

            echo $msg;
            $ITA = null;
            break;
    }
}
