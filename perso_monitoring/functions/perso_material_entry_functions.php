<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    $perso = $conn->db_conn_personalization(); //* Personalization Database connection
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    switch ($action) {
        case 'load_material_list_table':
            //* ======== Read Data ========
            $searchValue = $_POST['search']['value'];
            $resultData_List = array();
            //* ======== Create Array for column same with column names on database for ordering ========
            $col = array(
                0 => 'material_name'
            );
            //* ======== Fetch Data ========
            $sqlstring = "SELECT materialid,material_name FROM bpi_perso_material_list";
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record = $result_stmt->rowCount();
            //* ======== Fetch Total Filtered Record ========
            $sqlstring = "SELECT materialid,material_name FROM bpi_perso_material_list WHERE 1 = 1 ";
            //* ======== Search ========
            if (!empty($searchValue)) {
                $sqlstring .= "AND material_name ILIKE '%" . $searchValue . "%'";
            }
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            $result_total_record_filtered = $result_stmt->rowCount();
            //* ======== Ordering ========
            $sqlstring .= " ORDER BY " . $col[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'] . " LIMIT " . $_POST['length'] . " OFFSET " . $_POST['start'];
            $result_stmt = $perso->prepare($sqlstring);
            $result_stmt->execute();
            //* ======== Prepare Array ========
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $btnAction = '<button type="button" class="btn col-sm-6 btn-primary btnEditMaterial" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Material" onclick="modifyMaterial(\'' . $row["materialid"] . '\');"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                    <button type="button" class="btn col-sm-6 btn-danger btnDeleteMaterial" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Material" onclick="deleteMaterial(\'' . $row["materialid"] . '\');"><i class="fa-solid fa-trash-can fa-shake"></i></button>';

                $nestedData = array();
                $nestedData[] = $row['material_name'];
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

        case 'save_material':
            $strMaterialName = trim($_POST['strMaterialName']);
            $strMaterialSection = trim($_POST['strMaterialSection']);

            $result_chk_existing_sql = "SELECT * FROM bpi_perso_material_list WHERE material_name = :materialname";
            $result_chk_existing_stmt = $perso->prepare($result_chk_existing_sql);
            $result_chk_existing_stmt->bindParam(':materialname', $strMaterialName);
            $result_chk_existing_stmt->execute();

            if ($result_chk_existing_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $result_sql = "INSERT INTO bpi_perso_material_list(material_name,material_section) VALUES(:materialname,:materialSection)";
                $result_stmt = $perso->prepare($result_sql);
                $result_stmt->bindParam(':materialname', $strMaterialName);
                $result_stmt->bindParam(':materialSection', $strMaterialSection);
                $result_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'load_material_information':
            $materialid = trim($_POST['materialid']);

            $result_sql = "SELECT * FROM bpi_perso_material_list WHERE materialid = :materialid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':materialid', $materialid);
            $result_stmt->execute();

            $materialData_List = array();
            while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                $materialData_List['materialname'] = $row['material_name'];
                $materialData_List['materialSection'] = $row['material_section'];
            }
            echo json_encode($materialData_List);
            $perso = null; //* ======== Close Connection ========
            break;

        case 'update_material':
            $materialid = trim($_POST['materialid']);
            $strMaterialName = trim($_POST['strMaterialName']);
            $strMaterialSection = trim($_POST['strMaterialSection']);

            $result_chk_existing_sql = "SELECT * FROM bpi_perso_material_list WHERE material_name = :materialname AND materialid <> :materialid";
            $result_chk_existing_stmt = $perso->prepare($result_chk_existing_sql);
            $result_chk_existing_stmt->bindParam(':materialid', $materialid);
            $result_chk_existing_stmt->bindParam(':materialname', $strModMaterialName);
            $result_chk_existing_stmt->execute();

            if ($result_chk_existing_stmt->rowCount() > 0) {
                echo 'existing';
            } else {
                $result_sql = "UPDATE bpi_perso_material_list SET material_name = :materialname, material_section = :materialSection WHERE materialid = :materialid";
                $result_stmt = $perso->prepare($result_sql);
                $result_stmt->bindParam(':materialid', $materialid);
                $result_stmt->bindParam(':materialname', $strMaterialName);
                $result_stmt->bindParam(':materialSection', $strMaterialSection);
                $result_stmt->execute();
            }
            $perso = null; //* ======== Close Connection ========
            break;

        case 'delete_material_name':
            $materialid = trim($_POST['materialid']);

            $result_sql = "DELETE FROM bpi_perso_material_list WHERE materialid = :materialid";
            $result_stmt = $perso->prepare($result_sql);
            $result_stmt->bindParam(':materialid', $materialid);
            $result_stmt->execute();
            $perso = null; //* ======== Close Connection ========
            break;
    }
}
