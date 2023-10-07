<?php
class PhdDeviceModule
{
    public function fetchDataCategoryList($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_device_category";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['device_category_name'];
            $nestedData[] = $row['devicecategoryid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataAssignDevice($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT devicecatassignid,device_category_name,location_name,category_name,device_name FROM phd_device_category_assign
                LEFT JOIN phd_device_category ON phd_device_category.devicecategoryid = phd_device_category_assign.devicecategory_id
                LEFT JOIN phd_location ON phd_location.phdlocationid = phd_device_category_assign.phdlocation_id
                LEFT JOIN phd_location_category ON phd_location_category.phdloccatid = phd_device_category_assign.phdloccat_id";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['device_category_name'];
            $nestedData[] = $row['location_name'] == '' ? '-' : $row['location_name'];
            $nestedData[] = $row['category_name'] == '' ? '-' : $row['category_name'];
            $nestedData[] = $row['device_name'] == '' ? '-' : $row['device_name'];
            $nestedData[] = $row['devicecatassignid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataAssignDeviceUnits($PHD)
    {
        $itemData_List = array();
        // $sqlstring = "SELECT assignunitsid,units,location_name FROM phd_assign_units 
        //         INNER JOIN phd_location ON phd_location.phdlocationid = phd_assign_units.phdlocation_id";
        $sqlstring = "SELECT assignunitsid,units,phdlocation_name FROM phd_assign_units";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['phdlocation_name'];
            $nestedData[] = $row['units'];
            $nestedData[] = $row['assignunitsid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataDeviceCategory($PHD, $device_category_name)
    {
        $sqlstring = "INSERT INTO phd_device_category(device_category_name) VALUES(?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$device_category_name]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteDataDeviceCategory($PHD, $devicecategoryid)
    {
        try {
            $sqlstring = "DELETE FROM phd_device_category WHERE devicecategoryid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$devicecategoryid]);
            $itemData_List['result'] = 'success';
        } catch (Exception $e) {
            $itemData_List['result'] = $e->getMessage();
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataAssignList($PHD, $assign_category_name, $location_name, $category_name, $device_name)
    {
        $sqlstring = "INSERT INTO phd_device_category_assign(devicecategory_id,phdlocation_id,phdloccat_id,device_name) 
                VALUES(?,?,?,?)";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$assign_category_name, $location_name, $category_name, $device_name]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteDataAssignList($PHD, $devicecatassignid)
    {
        $sqlstring = "DELETE FROM phd_device_category_assign WHERE devicecatassignid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$devicecatassignid]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataUnits($PHD, $location_units, $units)
    {
        $sqlstringScan = "SELECT * FROM phd_assign_units WHERE phdlocation_name = ? AND units = ?";
        $result_stmt_scan = $PHD->prepare($sqlstringScan);
        $result_stmt_scan->execute([$location_units, $units]);
        if ($result_stmt_scan->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO phd_assign_units(phdlocation_name,units)VALUES(?,?)";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$location_units, $units]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function deleteDataUnits($PHD, $assignunitsid)
    {
        $sqlstring = "DELETE FROM phd_assign_units WHERE assignunitsid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$assignunitsid]);
    }
    public  function loadSelectDevice($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_location ORDER BY location_name ASC";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        if ($result_stmt->rowCount() > 0) {
            foreach ($result_stmt->fetchAll() as $row) {
                $itemData_List[$row['location_name']] = $row['location_name'];
            }
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
    }
    public function loadPreviewCategory($PHD, $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_device_category WHERE devicecategoryid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['result'] = $row['device_category_name'];
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
    }
    public function updateDataCategory($PHD, $category_name, $id)
    {
        $sqlstring = "UPDATE phd_device_category SET device_category_name = ? WHERE devicecategoryid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$category_name, $id]);
    }
    public function loadPreviewAssign($PHD, $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_device_category_assign WHERE devicecatassignid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['devicecategory_id'] = $row['devicecategory_id'];
            $itemData_List['phdlocation_id'] = $row['phdlocation_id'];
            $itemData_List['phdloccat_id'] = $row['phdloccat_id'];
            $itemData_List['device_name'] = $row['device_name'];
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
    }
    public function updateDataAssign($PHD, $assign_category_name, $location_name, $category_name, $device_name, $id)
    {
        $sqlstring = "UPDATE phd_device_category_assign SET devicecategory_id = ?, phdlocation_id = ?, phdloccat_id  = ?, device_name = ? WHERE devicecatassignid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$assign_category_name, $location_name, $category_name, $device_name, $id]);
    }
    public function loadPreviewAssignUnits($PHD, $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_assign_units WHERE assignunitsid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List['units'] = $row['units'];
            $itemData_List['phdlocation_name'] = $row['phdlocation_name'];
        }
        $itemData_List ??= null;
        return json_encode($itemData_List);
    }
    public function updateDataAssignUnits($PHD, $location_units, $units, $id)
    {
        $sqlstring = "UPDATE phd_assign_units SET units = ?, phdlocation_name = ? WHERE assignunitsid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$units, $location_units, $id]);
    }
}
