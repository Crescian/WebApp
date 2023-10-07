<?php
date_default_timezone_set('Asia/Manila');

class PhdLocation
{
    public function fetchDataLocation($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_location";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['location_name'];
            $nestedData[] = $row['phdlocationid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataCategory($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_location_category";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['category_name'];
            $nestedData[] = $row['phdloccatid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataAssign($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_loc_category_assign 
                            INNER JOIN phd_location ON phd_location.phdlocationid = phd_loc_category_assign.phdlocation_id
                            INNER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_loc_category_assign.phdloccat_id";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['category_name'];
            $nestedData[] = $row['location_name'];
            $nestedData[] = $row['zone_category_name'];
            $nestedData[] = $row['phdloccatassignid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataLocation($PHD, $add_location_name)
    {
        $sqlstring = "SELECT * FROM phd_location WHERE location_name = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_location_name]);
        if ($result_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'empty';
        } else {
            $sqlstring = "INSERT INTO phd_location(location_name)VALUES(?)";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_location_name]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataLocation($PHD, $add_location_name, $locationPreview)
    {
        $sqlstring = "UPDATE phd_location set location_name = ? WHERE phdlocationid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_location_name, $locationPreview]);
    }
    public function previewDataLocation($PHD, $id)
    {
        $sqlstring = "SELECT * FROM phd_location WHERE phdlocationid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List['location_name'] = $row['location_name'];
        }
        return json_encode($resultData_List);
    }
    public function deleteDataLocation($PHD, $id)
    {
        try {
            $itemData_List = array();
            $sqlstring = "DELETE FROM phd_location WHERE phdlocationid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$id]);
            $itemData_List['result'] = 'success';
        } catch (Exception $e) {
            $itemData_List['result'] = $e->getMessage();
        }
        echo json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function saveDataCategory($PHD, $add_category_name)
    {
        $sqlstring = "SELECT * FROM phd_location_category WHERE category_name = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_category_name]);
        if ($result_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'empty';
        } else {
            $sqlstring = "INSERT INTO phd_location_category(category_name)VALUES(?)";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_category_name]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataCategory($PHD, $add_category_name, $categoryPreview)
    {
        $sqlstring = "UPDATE phd_location_category set category_name = ? WHERE phdloccatid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_category_name, $categoryPreview]);
    }
    public function previewDataCategory($PHD, $id)
    {
        $sqlstring = "SELECT * FROM phd_location_category WHERE phdloccatid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List['category_name'] = $row['category_name'];
        }
        return json_encode($resultData_List);
    }
    public function deleteDataCategory($PHD, $id)
    {
        try {
            $itemData_List = array();
            $sqlstring = "DELETE FROM phd_location_category WHERE phdloccatid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$id]);
            $itemData_List['result'] = 'success';
        } catch (Exception $e) {
            $itemData_List['result'] = $e->getMessage();
        }
        echo json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function loadLocation($PHD)
    {
        $sqlstring = "SELECT * FROM phd_location ORDER BY location_name ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        echo '<option value = "">Choose...</option>';
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            echo '<option value = "' . $row['phdlocationid'] . '">' . $row['location_name'] . '</option>';
        }
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function loadCategory($PHD)
    {
        $sqlstring = "SELECT * FROM phd_location_category ORDER BY category_name ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        echo '<option value = "">Choose...</option>';
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            echo '<option value = "' . $row['phdloccatid'] . '">' . $row['category_name'] . '</option>';
        }
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function saveDataAssign($PHD, $add_category_name_assign, $add_location_name_assign, $zone_name)
    {
        if ($zone_name == '') {
            $sqlstring = "SELECT * FROM phd_loc_category_assign WHERE phdloccat_id = ? AND phdlocation_id = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_category_name_assign, $add_location_name_assign]);
            if ($result_stmt->rowCount() > 0) {
                $itemData_List['result'] = 'empty';
            } else {
                $sqlstring = "INSERT INTO phd_loc_category_assign(phdloccat_id,phdlocation_id)VALUES(?,?)";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute([$add_category_name_assign, $add_location_name_assign]);
                $itemData_List['result'] = '';
            }
            return json_encode($itemData_List);
            ## CLOSE CONNECTION
            $PHD = null;
        } else {
            $sqlstring = "SELECT * FROM phd_loc_category_assign WHERE phdloccat_id = ? AND phdlocation_id = ? AND zone_category_name = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_category_name_assign, $add_location_name_assign, $zone_name]);
            if ($result_stmt->rowCount() > 0) {
                $itemData_List['result'] = 'empty';
            } else {
                $sqlstring = "INSERT INTO phd_loc_category_assign(phdloccat_id,phdlocation_id,zone_category_name)VALUES(?,?,?)";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute([$add_category_name_assign, $add_location_name_assign, $zone_name]);
                $itemData_List['result'] = '';
            }
            return json_encode($itemData_List);
            ## CLOSE CONNECTION
            $PHD = null;
        }
    }
    public function updateDataAssign($PHD, $add_category_name_assign, $add_location_name_assign, $zone_name, $assignPreview)
    {
        $sqlstring = "SELECT * FROM phd_loc_category_assign WHERE phdloccat_id = ? AND phdlocation_id = ? AND zone_category_name = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_category_name_assign, $add_location_name_assign, $zone_name]);
        if ($result_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'empty';
        } else {
            $sqlstring = "UPDATE phd_loc_category_assign set phdloccat_id = ?,phdlocation_id = ?, zone_category_name = ? WHERE phdloccatassignid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_category_name_assign, $add_location_name_assign, $zone_name, $assignPreview]);
            $itemData_List['result'] = '';
        }
    }
    public function previewDataAssign($PHD, $id)
    {
        $sqlstring = "SELECT * FROM phd_loc_category_assign WHERE phdloccatassignid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List['phdloccat_id'] = $row['phdloccat_id'];
            $resultData_List['phdlocation_id'] = $row['phdlocation_id'];
            $resultData_List['zone_category_name'] = $row['zone_category_name'];
        }
        return json_encode($resultData_List);
    }
    public function deleteDataAssign($PHD, $id)
    {
        try {
            $sqlstring = "DELETE FROM phd_loc_category_assign WHERE phdloccatassignid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$id]);
            $itemData_List['result'] = '';
        } catch (Exception $e) {
            $itemData_List['result'] = $e->getMessage();
        }
        return json_encode($itemData_List);
    }
}
