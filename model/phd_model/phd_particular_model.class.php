<?php
class PhdParticularModule
{
    public function fetchDataParticular($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_particular";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['particular_name'];
            $nestedData[] = $row['phdparticularid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataAssignParticular($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_particular_assign 
                            INNER JOIN phd_particular ON phd_particular.phdparticularid = phd_particular_assign.phdparticular_id
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_particular_assign.phdlocation_id";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['particular_name'];
            $nestedData[] = $row['location_name'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadSelectValueWithId($PHD, $inTable, $inFieldId, $inField)
    {
        $itemData_List = array();
        $sqlstring = "SELECT " . $inFieldId . ", " . $inField . " FROM " . $inTable . " ORDER BY " . $inField . " ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[$row[$inFieldId]] = $row[$inField];
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataParticular($PHD, $add_particular_name, $location_name)
    {
        $sqlstringValidation = "SELECT * FROM phd_particular WHERE particular_name = ?";
        $result_stmt_valid = $PHD->prepare($sqlstringValidation);
        $result_stmt_valid->execute([$add_particular_name]);
        if ($result_stmt_valid->rowCount() > 0) {
            $result_res_valid = $result_stmt_valid->fetch(PDO::FETCH_ASSOC);
            $phdparticularid = $result_res_valid['phdparticularid'];
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO phd_particular(particular_name)VALUES(?) RETURNING phdparticularid";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_particular_name]);
            $phdparticularid = $PHD->lastInsertId();
            $itemData_List['result'] = '';
        }
        $sqlstringAssignValidation = "SELECT * FROM phd_particular_assign WHERE phdparticular_id = ? AND phdlocation_id = ?";
        $result_stmt_assign_valid = $PHD->prepare($sqlstringAssignValidation);
        $result_stmt_assign_valid->execute([$phdparticularid, $location_name]);
        if ($result_stmt_assign_valid->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstringAssign = "INSERT INTO phd_particular_assign(phdparticular_id,phdlocation_id)VALUES(?,?)";
            $result_stmt_assign = $PHD->prepare($sqlstringAssign);
            $result_stmt_assign->execute([$phdparticularid, $location_name]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataParticular($PHD, $add_particular_name, $particularPreview)
    {
        $sqlstring = "SELECT * FROM phd_particular WHERE particular_name = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_particular_name]);
        if ($result_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'empty';
        } else {
            $sqlstring = "UPDATE phd_particular SET particular_name = ? WHERE phdparticularid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_particular_name, $particularPreview]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function previewDataParticular($PHD, $id)
    {
        $sqlstring = "SELECT * FROM phd_particular WHERE phdparticularid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List['particular_name'] = $row['particular_name'];
        }
        return json_encode($resultData_List);
    }
    public function deleteDataParticular($PHD, $id)
    {
        try {
            $sqlstring = "DELETE FROM phd_particular WHERE phdparticularid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$id]);
            $itemData_List['result'] = 'success';
        } catch (Exception $e) {
            $itemData_List['result'] = $e->getMessage();
        }
        return json_encode($itemData_List);
    }
}
