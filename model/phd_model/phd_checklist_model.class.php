<?php
class PhdChecklistModule
{
    public function fetchDataChecklistName($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_checklist_name";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['checklist_name'];
            $nestedData[] = $row['phdchklistid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchDataAssignTable($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_checklist_assign 
                                LEFT OUTER JOIN phd_checklist_name ON phd_checklist_name.phdchklistid = phd_checklist_assign.phdchklist_id
                                LEFT OUTER JOIN phd_location ON phd_location.phdlocationid = phd_checklist_assign.phdlocation_id
                                LEFT OUTER JOIN phd_location_category ON phd_location_category.phdloccatid = phd_checklist_assign.phdloccat_id";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['checklist_name'];
            $nestedData[] = $row['location_name'] == '' ? "-" : $row['location_name'];
            $nestedData[] = $row['category_name'] == '' ? "-" : $row['category_name'];
            $nestedData[] = $row['phdchklistassignid'];
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
    public function saveDataChecklistName($PHD, $add_checklist_name)
    {
        $sqlstring = "SELECT * FROM phd_checklist_name WHERE checklist_name = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_checklist_name]);
        if ($result_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'empty';
        } else {
            $sqlstring = "INSERT INTO phd_checklist_name(checklist_name)VALUES(?)";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_checklist_name]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataChecklistName($PHD, $add_checklist_name, $checklistPreview)
    {
        $sqlstring = "UPDATE phd_checklist_name SET checklist_name = ? WHERE phdchklistid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$add_checklist_name, $checklistPreview]);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function previewDataChecklistName($PHD, $id)
    {
        $sqlstring = "SELECT * FROM phd_checklist_name WHERE phdchklistid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List['checklist_name'] = $row['checklist_name'];
        }
        return json_encode($resultData_List);
    }
    public function saveDataAssign($PHD, $add_checklist_assign, $add_category_name, $add_location_name)
    {
        if ($add_category_name == '') {
            $add_category_name = NULL;
            $sqlstringCategoryValidate = "SELECT * FROM phd_checklist_assign 
                WHERE phdchklist_id = '" . $add_checklist_assign . "' AND phdlocation_id ='" . $add_location_name . "'";
        } else if ($add_location_name == '') {
            $add_location_name = NULL;
            $sqlstringCategoryValidate = "SELECT * FROM phd_checklist_assign 
                WHERE phdchklist_id = '" . $add_checklist_assign . "' AND phdloccat_id ='" . $add_category_name . "'";
        }
        $result_stmt_validate = $PHD->prepare($sqlstringCategoryValidate);
        $result_stmt_validate->execute();
        if ($result_stmt_validate->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO phd_checklist_assign(phdchklist_id,phdloccat_id,phdlocation_id)VALUES(?,?,?)";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_checklist_assign, $add_category_name, $add_location_name]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function updateDataAssign($PHD, $add_checklist_assign_name, $add_category_name, $add_location_name, $assignPreview)
    {
        if ($add_category_name == '') {
            $sqlstring = "SELECT * FROM phd_checklist_assign WHERE phdchklist_id = ? AND phdlocation_id = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_checklist_assign_name, $add_location_name]);
            if ($result_stmt->rowCount() > 0) {
                $itemData_List['result'] = 'existing';
            } else {
                $sqlstring = "UPDATE phd_checklist_assign SET phdchklist_id = ?, phdlocation_id = ? WHERE phdchklistassignid = ?";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute([$add_checklist_assign_name, $add_location_name, $assignPreview]);
                $itemData_List['result'] = '';
            }
        } else {
            $sqlstring = "SELECT * FROM phd_checklist_assign WHERE phdchklist_id = ? AND phdloccat_id = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$add_checklist_assign_name, $add_category_name]);
            if ($result_stmt->rowCount() > 0) {
                $itemData_List['result'] = 'existing';
            } else {
                $sqlstring = "UPDATE phd_checklist_assign SET phdchklist_id = ?, phdloccat_id = ? WHERE phdchklistassignid = ?";
                $result_stmt = $PHD->prepare($sqlstring);
                $result_stmt->execute([$add_checklist_assign_name, $add_category_name, $assignPreview]);
                $itemData_List['result'] = '';
            }
        }
        return json_encode($itemData_List);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function previewDataAssign($PHD, $id)
    {
        $sqlstring = "SELECT * FROM phd_checklist_assign WHERE phdchklistassignid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultData_List['phdchklist_id'] = $row['phdchklist_id'];
            $resultData_List['phdlocation_id'] = $row['phdlocation_id'];
            $resultData_List['phdloccat_id'] = $row['phdloccat_id'];
        }
        return json_encode($resultData_List);
    }
    public function deleteDataChecklist($PHD, $id)
    {
        $sqlstring = "DELETE FROM phd_checklist_name WHERE phdchklistid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function deleteDataAssign($PHD, $id)
    {
        $sqlstring = "DELETE FROM phd_checklist_assign WHERE phdchklistassignid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        ## CLOSE CONNECTION
        $PHD = null;
    }
    public function loadDataChecklist($BannerWebLive)
    {
        $sqlstring = "SELECT * FROM bpi_app_menu_module 
            INNER JOIN bpi_app_menu ON bpi_app_menu.appid = bpi_app_menu_module.app_id 
            WHERE app_name ILIKE '%Physical Security%' ORDER BY appmenuid ASC";
        $result_stmt = $BannerWebLive->prepare($sqlstring);
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[$row['app_menu_title']] = $row['app_menu_title'];
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
}
