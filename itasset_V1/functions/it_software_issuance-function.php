<?php
include '../../configuration/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');

$ITA = $conn->db_conn_it_asset(); //* IT Asset Database connection
$BannerWeb = $conn->db_conn_bannerweb(); //* Banner Web Database connection

function actionButton($status, $id)
{
    switch ($status) {
        case 'Issued':
            $result  = '<button class="btn btn-dark" onclick="actionSoftwareIssuance(' . $id . ', \'Retrieve\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Retrieve">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i>
                        </button>
                        <button class="btn btn-danger" onclick="actionSoftwareIssuance(' . $id . ', \'Delete\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>';
            break;

        case 'Retrieved':
            $result  = '<button class="btn btn-light border border-left-dark" onclick="actionSoftwareIssuance(' . $id . ', \'Return\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Return to Warehouse">
                            <i class="fa-solid fa-warehouse"></i>
                        </button>
                        <button class="btn btn-light text-danger border border-left-danger" onclick="actionSoftwareIssuance(' . $id . ', \'Defective\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Defective">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </button>
                        <button class="btn btn-danger" onclick="actionSoftwareIssuance(' . $id . ', \'Delete\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>';
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
    }
    return $result;
}


if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadTableSoftwareIssuance':
            //* =========== Variables ===========
            $search = $_POST['search']['value'];
            $filterValue = trim($_POST['filterValue']);
            $filterValue = !empty($filterValue) ? $filterValue : "Issued', 'Retrieved";
            $columns = array(
                0 => 'employee',
                1 => 'cpu_control_no',
                2 => 'software_type',
                3 => 'software',
                4 => 'serial',
                5 => 'issuer',
                6 => 'date_issued',
                7 => 'status',
            );

            //* =========== Fetch Total Record Data ===========
            $query = "SELECT t2.employee, t2.cpu_control_no, t3.*, status
                        FROM  tblit_software_issuance AS t1
                      INNER JOIN tblit_cpu_control_no AS t2 
                        ON t2.cpu_control_no_id = t1.cpu_control_no_id
                        INNER JOIN tblit_software_available AS t3
                        ON t3.software_available_id = t1.software_available_id
                      WHERE status IN ('{$filterValue}') ";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $totalRecord = $stmt->rowCount();

            //* =========== Fetch Total Filtered Record Data ===========
            if (!empty($search)) {
                $query .= "AND (employee ILIKE '%{$search}%'
                    OR cpu_control_no ILIKE '%{$search}%'
                    OR software_type ILIKE '%{$search}%'
                    OR software ILIKE '%{$search}%'
                    OR serial ILIKE '%{$search}%'
                    OR issuer ILIKE '%{$search}%'
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
                    $row['employee'],
                    $row['cpu_control_no'],
                    $row['software_type'],
                    $row['software'],
                    $row['serial'],
                    $row['issuer'],
                    $row['date_issued'],
                    badgeStatus($row['status']),
                    actionButton($row['status'], $row['software_issuance_id'])
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

            // *========== Software Type ==========
            $query = "SELECT software_type FROM tblit_software_type ORDER BY 1";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $softwareType = '<option value="" selected>Select a Software Type:</option>';
            foreach ($resultRow as $row) {
                $softwareType .= '<option value="' . $row['software_type'] . '">' . $row['software_type'] . '</option>';
            }

            // *========== Input Data JSON Format ==========
            $data = array(
                "issuer" => $issuer,
                "department" => $department,
                "softwareType" => $softwareType,
            );

            echo json_encode($data);
            $ITA = null;
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
        case 'loadSoftwareAndSerial':
            //* =========== Variable ===========
            $softwareType = $_POST['softwareType'];

            //* =========== Get the list of employees fullname ===========
            $query = "SELECT software_available_id, software, serial FROM tblit_software_available WHERE software_type = '{$softwareType}' AND active = true";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $softwareAndSerial = '<option value="" selected>Select a Software :</option>';

            foreach ($resultRow as $row) {
                $softwareAndSerial .= '<option value="' . $row['software_available_id'] . '">' . $row['software'] . ' (' . $row['serial'] . ')</option>';
            }

            echo $softwareAndSerial;
            $ITA = null;
            break;
    }
}
