<?php
include '../../includes/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadTableAssignCPU':
            //* =========== Variables ===========
            $search = $_POST['search']['value'];
            $columns = array(
                0 => 'employee',
                1 => 'cpu_control_no',
                2 => 'description',
                3 => 'location',
                4 => 'switch_tag',
                5 => 'lan_cable_tag',
                6 => 'ip_address',
                7 => 'date_updated',
            );

            //* =========== Fetch Total Record Data ===========
            $query = "SELECT * FROM tblit_cpu_control_no WHERE active_pc = true ";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $totalRecord = $stmt->rowCount();

            //* =========== Fetch Total Filtered Record Data ===========
            if (!empty($search)) {
                $query .= "AND (employee ILIKE '%{$search}%'
                        OR cpu_control_no ILIKE '%{$search}%'
                        OR description ILIKE '%{$search}%'
                        OR location ILIKE '%{$search}%'
                        OR switch_tag ILIKE '%{$search}%'
                        OR lan_cable_tag ILIKE '%{$search}%'
                        OR ip_address ILIKE '%{$search}%'
                        OR TO_CHAR(date_updated, 'YYYY-MM-DD') ILIKE '%{$search}%') ";
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
                    $row['location'],
                    $row['switch_tag'],
                    $row['lan_cable_tag'],
                    $row['ip_address'],
                    $row['date_updated'],
                    '<button class="btn btn-danger" onclick="editAssignCPU(' . $row['cpu_control_no_id'] . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                        <i class="fa-solid fa-file-pen"></i>
                    </button>
                    <button class="btn btn-dark" onclick="pdfAssignCPU(' . $row['cpu_control_no_id'] . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print">
                        <i class="fa-solid fa-file-pdf"></i>
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

            // *========== CPU Control Number ==========
            $query = "SELECT cpu_control_no FROM tblit_cpu_control_no ORDER BY cpu_control_no DESC LIMIT 1";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $cpuControlNumberYear = substr($resultRow['cpu_control_no'], 2, 2);
            $cpuControlNumberTrack = substr($resultRow['cpu_control_no'], 4);

            if ($cpuControlNumberYear == date('y')) {
                $cpuControlNumber = "PC" . date('y') . sprintf("%03d", $cpuControlNumberTrack + 1);
            } else {
                $cpuControlNumber = "PC" . date('y') . "001";
            }

            // *========== Input Data JSON Format ==========
            $data = array(
                "cpuControlNumber" => $cpuControlNumber,
                "department" => $department
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
                      WHERE emp_stat NOT IN ('Resigned','Terminated','End Contract','Project Completion', 'Retired', 'Resigned Non-compliance')
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

        case 'newAssignCPU':
            $activeComputer = trim($_POST['activeComputer']);
            $cpuControlNumber = trim($_POST['cpuControlNumber']);
            $employee = trim($_POST['employee']);
            $description = trim($_POST['description']);
            $location = trim($_POST['location']);
            $switchTag = trim($_POST['switchTag']);
            $lanCableTag = trim($_POST['lanCableTag']);
            $ipAddress = trim($_POST['ipAddress']);
            $dateToday = trim($_POST['dateToday']);

            $query = "INSERT INTO tblit_cpu_control_no(active_pc, cpu_control_no, employee, description, location, switch_tag, lan_cable_tag, ip_address, date_updated)
                        VALUES('{$activeComputer}', '{$cpuControlNumber}', '{$employee}', '{$description}', '{$location}', '{$switchTag}', '{$lanCableTag}', '{$ipAddress}', '{$dateToday}')";
            $stmt = $ITA->prepare($query);
            $stmt->execute();

            echo true;
            $ITA = null;
            break;

        case 'editAssignCPU':
            $activeComputer = trim($_POST['activeComputer']);
            $cpuControlNumber = trim($_POST['cpuControlNumber']);
            $employee = trim($_POST['employee']);
            $description = trim($_POST['description']);
            $location = trim($_POST['location']);
            $switchTag = trim($_POST['switchTag']);
            $lanCableTag = trim($_POST['lanCableTag']);
            $ipAddress = trim($_POST['ipAddress']);
            $dateToday = trim($_POST['dateToday']);

            $query = "UPDATE tblit_cpu_control_no 
                        SET active_pc = '{$activeComputer}', 
                            employee = '{$employee}', 
                            description = '{$description}', 
                            location = '{$location}', 
                            switch_tag = '{$switchTag}', 
                            lan_cable_tag = '{$lanCableTag}', 
                            ip_address = '{$ipAddress}', 
                            date_updated = '{$dateToday}'
                        WHERE cpu_control_no = '{$cpuControlNumber}'";
            $stmt = $ITA->prepare($query);
            $stmt->execute();

            echo true;
            $ITA = null;
            break;

        case 'fetchByIdAssignCPU':
            $cpuID = trim($_POST['cpuID']);
            $query = "SELECT * FROM tblit_cpu_control_no WHERE cpu_control_no_id = {$cpuID}";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);

            $queryDeptCode = "SELECT DISTINCT dept_code FROM prl_employee where emp_fn||' '||emp_sn = '{$rowData['employee']}'";
            $stmtDeptCode = $BannerWeb->prepare($queryDeptCode);
            $stmtDeptCode->execute();
            $deptCode = $stmtDeptCode->fetch(PDO::FETCH_ASSOC);

            $data = array(
                "deptCode" => $deptCode['dept_code'],
                "employee" => $rowData['employee'],
                "cpuControlNumber" => $rowData['cpu_control_no'],
                "description" => $rowData['description'],
                "location" => $rowData['location'],
                "switchTag" => $rowData['switch_tag'],
                "lanCableTag" => $rowData['lan_cable_tag'],
                "ipAddress" => $rowData['ip_address'],
                "active" => $rowData['active_pc'],
            );

            echo json_encode($data);
            $ITA = null;
            break;
    }
}
