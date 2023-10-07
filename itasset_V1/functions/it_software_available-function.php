<?php
include '../../configuration/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');

$ITA = $conn->db_conn_it_asset(); //* IT Asset Database connection
$BannerWeb = $conn->db_conn_bannerweb(); //* Banner Web Database connection

function actionButton($status, $id)
{
    switch ($status) {
        case true:
            $result  = '<button class="btn btn-dark" onclick="editSoftwareAvailable(' . $id . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                        <i class="fa-solid fa-file-pen"></i>
                    </button>
                    <button class="btn btn-danger" onclick="actionSoftwareAvailable(' . $id . ', \'Inactive\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Move to Inactive">
                       <i class="fa-regular fa-circle-xmark"></i>
                    </button>';
            break;
        case false:
            $result  = '<button class="btn btn-dark" onclick="actionSoftwareAvailable(' . $id . ', \'Active\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Move to Active">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    <button class="btn btn-danger" onclick="actionSoftwareAvailable(' . $id . ', \'Delete\');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>';
            break;
    }
    return $result;
}

function badgeStatus($status)
{
    switch ($status) {
        case true:
            $result = '<span class="badge rounded-pill shadow text-bg-dark w-100">Active</span>';
            break;
        case false:
            $result = '<span class="badge rounded-pill shadow text-bg-secondary w-100">Inactive</span>';
            break;
    }
    return $result;
}

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadTableSoftwareAvailable':
            //* =========== Variables ===========
            $search = $_POST['search']['value'];
            $filterValue = trim($_POST['filterValue']);
            $filterValue = !empty($filterValue) ? $filterValue : "true, false";
            $columns = array(
                0 => 'software_type',
                1 => 'software',
                2 => 'description',
                3 => 'serial',
                4 => 'programmer',
            );

            //* =========== Fetch Total Record Data ===========
            $query = "SELECT * FROM  tblit_software_available WHERE active IN ({$filterValue}) ";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $totalRecord = $stmt->rowCount();

            //* =========== Fetch Total Filtered Record Data ===========
            if (!empty($search)) {
                $query .= "AND (software_type ILIKE '%{$search}%'
                    OR software ILIKE '%{$search}%'
                    OR description ILIKE '%{$search}%'
                    OR serial ILIKE '%{$search}%'
                    OR programmer ILIKE '%{$search}%') ";
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
                    $row['software_type'],
                    $row['software'],
                    $row['description'],
                    $row['serial'],
                    $row['programmer'],
                    badgeStatus($row['active']),
                    actionButton($row['active'], $row['software_available_id'])
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

            // *========== Software Type ==========
            $query = "SELECT software_type FROM tblit_software_type ORDER BY 1";
            $stmt = $ITA->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $softwareType = '<option value="" selected>Choose...</option>';
            foreach ($resultRow as $row) {
                $softwareType .= '<option value="' . $row['software_type'] . '">' . $row['software_type'] . '</option>';
            }

            //* =========== Get the list of Programmer's Full Name ===========
            $query = "SELECT DISTINCT emp_fn||' '||emp_sn AS fullname
                        FROM prl_employee 
                      WHERE emp_stat NOT IN ('Resigned','Terminated','End Contract','Project Completion', 'Retired', 'Resigned Non-compliance')
                    --   AND dept_code IN ('ITD', 'ISD')
                      AND pos_code IN ('JRM', 'SRM', 'CIS', 'SPG', 'VPI')
                      ORDER BY 1";
            $stmt = $BannerWeb->prepare($query);
            $stmt->execute();
            $resultRow = $stmt->fetchAll();
            $programmer = '<option value="-" hidden></option><option value="" hidden>Choose...</option>';
            foreach ($resultRow as $row) {
                $programmer .= '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
            }

            // *========== Input Data JSON Format ==========
            $data = array(
                "softwareType" => $softwareType,
                "programmer" => $programmer
            );

            echo json_encode($data);
            $ITA = null;
            break;


        case 'newSoftwareAvailable':
            $softwareType = trim($_POST['softwareType']);
            $software = trim($_POST['software']);
            $description = trim($_POST['description']);
            $serial = trim($_POST['serial']);
            $programmer = trim($_POST['programmer']);

            $sqlstring = "SELECT * FROM tblit_software_available WHERE serial ILIKE '{$serial}' AND active = true";
            $result_stmt = $ITA->prepare($sqlstring);
            $result_stmt->execute();
            $rowCount = $result_stmt->rowCount();

            // Check if the data already exists.
            if ($rowCount == 0) {
                // Query for the insert of an issued item.
                $sqlstring = "INSERT INTO tblit_software_available(
                                    software_type,
                                    software,
                                    description,
                                    serial,
                                    programmer,
                                    active
                                ) 
                                VALUES(
                                    '{$softwareType}',
                                    '{$software}',
                                    '{$description}',
                                    '{$serial}',
                                    '{$programmer}',
                                    true
                                )";
                $result_stmt = $ITA->prepare($sqlstring);
                $result_stmt->execute();
                echo true;
            } else {
                echo "Item Already Added.";
            }

            $ITA = null;
            break;

        case 'actionSoftwareAvailable':
            $id = trim($_POST['id']);
            $status = trim($_POST['status']);

            switch ($status) {
                case 'Active':
                    $query = "UPDATE tblit_software_available SET active = true WHERE software_available_id = '{$id}'";
                    $msg = "Activated";
                    break;

                case 'Inactive':
                    $query = "UPDATE tblit_software_available SET active = false  WHERE software_available_id = '{$id}'";
                    $msg = "Inactivated";
                    break;

                case 'Delete':
                    $query = "DELETE FROM tblit_software_available WHERE software_available_id = '{$id}'";
                    $msg = "Deleted";
                    break;
            }
            $stmt = $ITA->prepare($query);
            $stmt->execute();

            echo $msg;
            $ITA = null;
            break;
    }
}
