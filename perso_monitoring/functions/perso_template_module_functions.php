<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $bannerData = $conn->db_conn_bannerdata(); //* BannerData Database connection
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    function fill_process_select_box($perso, $processid)
    {
        $output = '';
        $result_sql = "SELECT * FROM bpi_perso_process_list ORDER BY processid ASC";
        $result_stmt = $perso->prepare($result_sql);
        $result_stmt->execute();
        $output .= '<option value="">Choose...</option>';
        foreach ($result_stmt->fetchAll() as $row) {
            $selected = $row["processid"] == $processid ? "selected" : "";
            $output .= '<option value"' . $row["processid"] . '" ' . $selected . '>' . $row["process_name"] . '</option>';
        }
        return $output;
        $perso = null; //* ======== Close Connection ========
    }

    function fill_material_select_box($perso, $materialid)
    {
        $output = '';
        $result_sql = "SELECT * FROM bpi_perso_material_list ORDER BY materialid ASC";
        $result_stmt = $perso->prepare($result_sql);
        $result_stmt->execute();
        $output .= '<option value="">Choose...</option>';
        foreach ($result_stmt->fetchAll() as $row) {
            $selected = ($row["materialid"] == $materialid) ? "selected" : "";
            $output .= '<option value"' . $row["materialid"] . '" ' . $selected . '>' . $row["material_name"] . '</option>';
        }
        return $output;
        $perso = null; //* ======== Close Connection ========
    }

    switch ($action) {
        case 'load_template_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'template_name'
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT * FROM bpi_perso_template_name";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT * FROM bpi_perso_template_name WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND (template_name ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .=
                " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                //* ======== Check if Template is In use ========
                $result_chk_sql = "SELECT * FROM bpi_perso_job_entry WHERE template_id = :templateid";
                $result_chk_stmt = $perso->prepare($result_chk_sql);
                $result_chk_stmt->bindParam(':templateid', $row['templateid']);
                $result_chk_stmt->execute();

                if ($result_chk_stmt->rowCount() > 0) {
                    $templateStatus = '<span class="badge bg-success col-sm-12 fs-6">In use</span>';
                    $btnAction = '<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-pen-to-square"></i></button>
                        <button type="button" class="btn btn-warning col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Clone Template" onclick="cloneTemplate(\'' . $row["templateid"] . '\');"><i class="fa-solid fa-clone fa-flip"></i></button> 
                        <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-trash-can"></i></button>';
                } else {
                    $templateStatus = '<span class="badge bg-dark col-sm-12 fs-6">Unused</span>';
                    $btnAction = '<button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Template" onclick="editTemplate(\'' . $row["templateid"] . '\');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button>
                        <button type="button" class="btn btn-warning col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Clone Template" onclick="cloneTemplate(\'' . $row["templateid"] . '\');"><i class="fa-solid fa-clone fa-flip"></i></button>
                        <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Template" onclick="deleteTemplate(\'' . $row["templateid"] . '\');"><i class="fa-solid fa-trash-can fa-shake"></i></button>';
                }

                $nestedData = array();
                $nestedData[] = $row['template_name'];
                $nestedData[] = $templateStatus;
                $nestedData[] = $btnAction;
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

        case 'load_customer_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'companyname'
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT DISTINCT companyname FROM ordersinformation
                INNER JOIN po ON po.poid = ordersinformation.poid
                INNER JOIN company ON company.companyid = po.customerid";
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT DISTINCT companyname FROM ordersinformation
                INNER JOIN po ON po.poid = ordersinformation.poid
                INNER JOIN company ON company.companyid = po.customerid 
                WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND companyname ILIKE '%" . $searchValue . "%' ";
            }
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .=
                " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $btnAction = '<button type="button" class="btn btn-primary col-sm-12 btnAssignTemplate" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Template" onclick="assignTemplate(\'' . str_replace("'", "\'", $row['companyname']) . '\');"><i class="fa-solid fa-plus"></i></button>';

                $nestedData = array();
                $nestedData[] = $row['companyname'];
                $nestedData[] = $btnAction;
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
            $bannerData = null; //* ======== Close Connection ========
            break;

        case 'save_template_name':
            $template_name = trim($_POST['template_name']);

            $chkExistsql = "SELECT * FROM bpi_perso_template_name WHERE template_name = :template_name";
            $chkExistsql_stmt = $perso->prepare($chkExistsql);
            $chkExistsql_stmt->bindParam(':template_name', $template_name);
            $chkExistsql_stmt->execute();

            if ($chkExistsql_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_perso_template_name(template_name) VALUES(:template_name) RETURNING templateid";
                $result_stmt = $perso->prepare($sqlstring);
                $result_stmt->bindParam(':template_name', $template_name);
                $result_stmt->execute();
                $templateid = $perso->lastInsertId();
                echo $templateid;
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'save_template_process':
            $templateid = trim($_POST['templateid']);
            $process_name = trim($_POST['process_name']);
            $process_sequence = trim($_POST['process_sequence']);
            //* ======== Fetch Process ID ========
            $fetchprocess_sql = "SELECT processid FROM bpi_perso_process_list WHERE process_name = :process_name";
            $fetchprocess_stmt = $perso->prepare($fetchprocess_sql);
            $fetchprocess_stmt->bindParam(':process_name', $process_name);
            $fetchprocess_stmt->execute();
            $fetchprocess_row = $fetchprocess_stmt->fetch(PDO::FETCH_ASSOC);
            $processid = $fetchprocess_row['processid'];
            //* ======== Insert Process Data ========
            $result_sql = "INSERT INTO bpi_perso_template_process(template_id,process_id,process_sequence) VALUES(:template_id,:process_id,:process_sequence)";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':template_id', $templateid);
            $result_stmt->bindParam(':process_id', $processid);
            $result_stmt->bindParam(':process_sequence', $process_sequence);
            $result_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;

        case 'save_template_material':
            $templateid = trim($_POST['templateid']);
            $material_name = trim($_POST['material_name']);
            //* ======== Fetch Material ID ========
            $fetchmaterial_sql = "SELECT materialid FROM bpi_perso_material_list WHERE material_name = :material_name";
            $fetchmaterial_stmt = $perso->prepare($fetchmaterial_sql);
            $fetchmaterial_stmt->bindParam(':material_name', $material_name);
            $fetchmaterial_stmt->execute();
            $fetchmaterial_res = $fetchmaterial_stmt->fetch(PDO::FETCH_ASSOC);
            $materialid = $fetchmaterial_res['materialid'];
            //* ======== Insert Material Data ========
            $result_sql = "INSERT INTO bpi_perso_template_material(template_id,material_id) VALUES(:template_id,:material_id)";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':template_id', $templateid);
            $result_stmt->bindParam(':material_id', $materialid);
            $result_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_template_name':
            $templateid = trim($_POST['templateid']);
            $itemData_List = array();

            $result_sql = "SELECT * FROM bpi_perso_template_name WHERE templateid = :templateid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();

            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $itemData_List['template_name'] = $row['template_name'];
            }
            echo json_encode($itemData_List);
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_template_process':
            $templateid = trim($_POST['templateid']);

            $sqlstring = "SELECT TempProc.process_id,ListProc.process_name,TempProc.process_sequence
                FROM bpi_perso_template_name TempName
                INNER JOIN bpi_perso_template_process TempProc ON TempProc.template_id = TempName.templateid
                INNER JOIN bpi_perso_process_list ListProc ON ListProc.processid = TempProc.process_id
                WHERE TempName.templateid = :templateid ORDER BY TempProc.process_sequence";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<tr>';
                echo '<td><input type="text" name="sequence_number[]" class="form-control fw-bold text-center sequence_number" value="' . $row['process_sequence'] . '" disabled></td>';
                echo '<td><select name="process_name[]" class="form-select fw-bold process_name" id="process_name' . $row['process_sequence'] . '" onchange="processValidation('  . $row['process_sequence'] . ',this.value);">' . fill_process_select_box($perso, $row['process_id']) . '</select></td>';
                echo '<td style="text-align:center;"><button type="button" name="removeProcess" class="btn btn-danger btn-sm btnRemoveProcess"><i class="fa-solid fa-minus"></i></button></td>';
                echo '</tr>';
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_template_material':
            $templateid = trim($_POST['templateid']);

            $sqlstring = "SELECT TempMat.material_id ,ListMat.material_name
                FROM bpi_perso_template_name TempName
                INNER JOIN bpi_perso_template_material TempMat ON TempMat.template_id = TempName.templateid
                INNER JOIN bpi_perso_material_list ListMat ON ListMat.materialid = TempMat.material_id
                WHERE TempName.templateid = :templateid";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            $materialCount = 0;
            foreach ($result_stmt->fetchAll() as $row) {
                $materialCount++;
                echo '<tr>';
                echo '<td><input type="text" name="material_number[]" class="form-control fw-bold text-center material_number" value="' . $materialCount . '" disabled></td>';
                echo '<td><select name="material_name[]" class="form-select fw-bold material_name" id="material_name' . $materialCount . '" onchange="materialValidation(' . $materialCount . ',this.value);">' . fill_material_select_box($perso, $row['material_id']) . '</select></td>';
                echo '<td style="text-align:center;"><button type="button" name="removeMaterial" class="btn btn-danger btn-sm btnRemoveMaterial"><i class="fa-solid fa-minus"></i></button></td>';
                echo '</tr>';
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'update_template_name':
            $templateid = trim($_POST['templateid']);
            $template_name = trim($_POST['template_name']);

            $chkExist_sql = "SELECT * FROM bpi_perso_template_name WHERE template_name = :template_name AND templateid <> :templateid";
            $chkExist_stmt = $perso->prepare($chkExist_sql);
            $chkExist_stmt->bindParam(':templateid', $templateid);
            $chkExist_stmt->bindParam(':template_name', $template_name);
            $chkExist_stmt->execute();

            if ($chkExist_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "UPDATE bpi_perso_template_name SET template_name = :template_name WHERE templateid = :templateid";
                $result_stmt = $perso->prepare($sqlstring);
                $result_stmt->bindParam(':templateid', $templateid);
                $result_stmt->bindParam(':template_name', $template_name);
                $result_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'delete_process_material_update':
            $templateid = trim($_POST['templateid']);
            //* ======== Delete Record For Process Update ========
            $result_delete_process_sql = "DELETE FROM bpi_perso_template_process WHERE template_id = :templateid";
            $result_delete_process_stmt = $perso->prepare($result_delete_process_sql);
            $result_delete_process_stmt->bindParam(':templateid', $templateid);
            $result_delete_process_stmt->execute();
            //* ======== Delete Record For Material Update ========
            $result_delete_material_sql = "DELETE FROM bpi_perso_template_material WHERE template_id = :templateid";
            $result_delete_material_stmt = $perso->prepare($result_delete_material_sql);
            $result_delete_material_stmt->bindParam(':templateid', $templateid);
            $result_delete_material_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;

        case 'delete_template':
            $templateid = trim($_POST['templateid']);

            $result_sql = "DELETE FROM bpi_perso_template_name WHERE templateid = :templateid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_job_order_number':
            $companyname = str_replace("'", "", trim($_POST['companyname']));

            $sqlstring = "SELECT jonumber FROM ordersinformation 
                INNER JOIN po ON po.poid = ordersinformation.poid 
                INNER JOIN company ON company.companyid = po.customerid 
                WHERE REPLACE(companyname,'''','') = :companyname
                GROUP BY jonumber, ordersinformation.orderid 
                ORDER BY ordersinformation.orderid DESC";
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->bindParam(':companyname', $companyname);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<option value="' . $row['jonumber'] . '">' . $row['jonumber'] . '</option>';
            }
            $bannerData = null; //* ======== Close Connection ========
            break;

        case 'load_job_description':
            $jonumber = trim($_POST['jonumber']);
            $itemData_List = array();

            $sqlstring = "SELECT descriptions,orderid FROM ordersinformation WHERE jonumber = :jonumber";
            $result_stmt = $bannerData->prepare($sqlstring);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->execute();

            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List['descriptions'] = $row['descriptions'];
                $itemData_List['orderid'] = $row['orderid'];
            }
            echo json_encode($itemData_List);
            $bannerData = null; //* ======== Close Connection ========
            break;

        case 'load_assigned_template_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $companyname = trim($_POST['companyname']);
            $jonumber = trim($_POST['jonumber']);
            $orderid = trim($_POST['orderid']);
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'template_name'
            );
            //* ======== Fetch Record ========
            $sqlstring = "SELECT TempAssign.tempassignid,TempName.template_name FROM bpi_perso_template_assign TempAssign
                INNER JOIN bpi_perso_template_name TempName ON TempName.templateid = TempAssign.template_id
                WHERE TempAssign.customer_name = :customer AND TempAssign.jonumber = :jonumber AND TempAssign.orderid = :orderid";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':customer', $companyname);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->bindParam(':orderid', $orderid);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT TempAssign.tempassignid,TempName.template_name FROM bpi_perso_template_assign TempAssign
                INNER JOIN bpi_perso_template_name TempName ON TempName.templateid = TempAssign.template_id
                WHERE 1 = 1 AND TempAssign.customer_name = :customer AND TempAssign.jonumber = :jonumber AND TempAssign.orderid = :orderid";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= " AND (template_name ILIKE '%" . $searchValue . "%') ";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':customer', $companyname);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->bindParam(':orderid', $orderid);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'] . "";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->bindParam(':customer', $companyname);
            $result_stmt->bindParam(':jonumber', $jonumber);
            $result_stmt->bindParam(':orderid', $orderid);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $btnAction = '<button type="button" class="btn btn-danger col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remove Template" onclick="removeAssignedTemplate(\'' . $row["tempassignid"] . '\');"><i class="fa-solid fa-trash-can fa-shake"></i></button>';
                $nestedData = array();
                $nestedData[] = $row['template_name'];
                $nestedData[] = $btnAction;
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

        case 'load_assign_template_name':
            $result_sql = "SELECT * FROM bpi_perso_template_name ORDER BY template_name ASC";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->execute();
            echo '<option value="">Choose...</option>';
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<option value="' . $row['templateid'] . '">' . $row['template_name'] . '</option>';
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_assign_template_process':
            $templateid = trim($_POST['templateid']);

            $result_sql = "SELECT ListProc.process_name,TempProc.process_sequence
                FROM bpi_perso_template_name TempName
                INNER JOIN bpi_perso_template_process TempProc ON TempProc.template_id = TempName.templateid
                INNER JOIN bpi_perso_process_list ListProc ON ListProc.processid = TempProc.process_id
                WHERE TempName.templateid = :templateid ORDER BY TempProc.process_sequence";

            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            foreach ($result_stmt->fetchAll() as $row) {
                echo '<tr>';
                echo '<td><input type="text" class="form-control fw-bold text-center"  value="' . $row['process_sequence'] . '" disabled></td>';
                echo '<td><input type="text" class="form-control fw-bold" value="' . $row['process_name'] . '" disabled></td>';
                echo '</tr>';
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_assign_template_material':
            $templateid = trim($_POST['templateid']);

            $result_sql = "SELECT ListMat.material_name
                FROM bpi_perso_template_name TempName
                INNER JOIN bpi_perso_template_material TempMat ON TempMat.template_id = TempName.templateid
                INNER JOIN bpi_perso_material_list ListMat ON ListMat.materialid = TempMat.material_id
                WHERE TempName.templateid = :templateid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':templateid', $templateid);
            $result_stmt->execute();
            $materialCount = 0;
            foreach ($result_stmt->fetchAll() as $row) {
                $materialCount++;
                echo '<tr>';
                echo '<td><input type="text" class="form-control fw-bold text-center"  value="' . $materialCount . '" disabled></td>';
                echo '<td><input type="text" class="form-control fw-bold" value="' . $row['material_name'] . '" disabled></td>';
                echo '</tr>';
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'save_assign_template':
            $customer_name = trim($_POST['customer_name']);
            $jonumber = trim($_POST['jonumber']);
            $orderid = trim($_POST['orderid']);
            $templateid = trim($_POST['templateid']);
            //* ======== Check Existing Template Name Within J.O Number ========
            $result_chkExisting_sql = "SELECT * FROM bpi_perso_template_assign WHERE template_id = :templateid AND customer_name = :customer_name AND jonumber = :jonumber AND orderid = :orderid";
            $result_chkExisting_stmt = $perso->prepare($result_chkExisting_sql);
            $result_chkExisting_stmt->bindParam(':customer_name', $customer_name);
            $result_chkExisting_stmt->bindParam(':jonumber', $jonumber);
            $result_chkExisting_stmt->bindParam(':orderid', $orderid);
            $result_chkExisting_stmt->bindParam(':templateid', $templateid);
            $result_chkExisting_stmt->execute();

            if ($result_chkExisting_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $sqlstring = "INSERT INTO bpi_perso_template_assign(customer_name,jonumber,orderid,template_id) VALUES(:customer_name,:jonumber,:orderid,:templateid)";
                $result_stmt = $perso->prepare($sqlstring);
                $result_stmt->bindParam(':customer_name', $customer_name);
                $result_stmt->bindParam(':jonumber', $jonumber);
                $result_stmt->bindParam(':orderid', $orderid);
                $result_stmt->bindParam(':templateid', $templateid);
                $result_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'remove_assigned_template':
            $tempassignid = trim($_POST['tempassignid']);

            $result_del_sql = "DELETE FROM bpi_perso_template_assign WHERE tempassignid = :tempassignid";
            $result_del_stmt = $perso->prepare($result_del_sql);
            $result_del_stmt->bindParam(':tempassignid', $tempassignid);
            $result_del_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;
    }
}
