<?php

class ProdSectionModule
{
    public function loadSectionTable($prod)
    {
        $itemData_List = array();
        //* ======== Fetch Data ========
        $sqlstring = "SELECT * FROM prod_section_name";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['section_name'];
            $nestedData[] = $row['sectionid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function saveSectionName($prod, $section_name)
    {
        $itemData_List = array();
        $chkExist = "SELECT * FROM prod_section_name WHERE section_name = ?";
        $chkExist_stmt = $prod->prepare($chkExist);
        $chkExist_stmt->execute([$section_name]);

        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO prod_section_name(section_name) VALUES(?)";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([$section_name]);
            $itemData_List['result'] = 'save';
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadSectionInfo($prod, $sectionid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM prod_section_name WHERE sectionid = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$sectionid]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['section_name'] = $row['section_name'];
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function updateSectionName($prod, $sectionid, $section_name)
    {
        $itemData_List = array();
        $chkExist = "SELECT * FROM prod_section_name WHERE section_name = ? AND sectionid <> ?";
        $chkExist_stmt = $prod->prepare($chkExist);
        $chkExist_stmt->execute([$section_name, $sectionid]);

        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "UPDATE prod_section_name SET section_name = ? WHERE sectionid = ?";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([$section_name, $sectionid]);
            $itemData_List['result'] = 'update';
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function removeSectionName($prod, $sectionid)
    {
        try {
            $itemData_List = array();
            $sqlstring = "DELETE FROM prod_section_name WHERE sectionid = ?";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([$sectionid]);
            $itemData_List['result'] = 'success';
        } catch (Exception $e) {
            $itemData_List['result'] = $e->getMessage();
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadAssignListTable($prod)
    {
        $itemData_List = array();
        //* ======== Fetch Data ========
        $sqlstring = "SELECT sectionassignid,section_name,machine_name FROM prod_section_assign
            INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_section_assign.section_id
            INNER JOIN prod_machine_name ON prod_machine_name.machineid = prod_section_assign.machine_id";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['section_name'];
            $nestedData[] = $row['machine_name'];
            $nestedData[] = $row['sectionassignid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function saveAssignMachine($prod, $section_id, $machine_id)
    {
        $itemData_List = array();
        $chkExist = "SELECT * FROM prod_section_assign WHERE section_id = ? AND machine_id = ?";
        $chkExist_stmt = $prod->prepare($chkExist);
        $chkExist_stmt->execute([$section_id, $machine_id]);
        //* ======== Prepare Array ========
        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO prod_section_assign(section_id,machine_id) VALUES(?,?)";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([$section_id, $machine_id]);
            $itemData_List['result'] = 'save';
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadSectionMachine($prod, $sectionassignid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM prod_section_assign WHERE sectionassignid = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$sectionassignid]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['section_id'] = $row['section_id'];
            $itemData_List['machine_id'] = $row['machine_id'];
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function updateSectionMachine($prod, $section_id, $machine_id, $sectionassignid)
    {
        $itemData_List = array();
        $chkExist = "SELECT * FROM prod_section_assign WHERE section_id = ? AND machine_id = ? AND sectionassignid <> ?";
        $chkExist_stmt = $prod->prepare($chkExist);
        $chkExist_stmt->execute([$section_id, $machine_id, $sectionassignid]);

        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "UPDATE prod_section_assign SET section_id = ?,machine_id = ? WHERE sectionassignid = ?";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([$section_id, $machine_id, $sectionassignid]);
            $itemData_List['result'] = 'save';
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function removeSectionMachine($prod, $sectionassignid)
    {
        $sqlstring = "DELETE FROM prod_section_assign WHERE sectionassignid = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$sectionassignid]);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadAssignEmployeeTable($prod)
    {
        $itemData_List = array();
        //* ======== Fetch Data ========
        $sqlstring = "SELECT assignjobid,section_name,job_title FROM prod_section_assign_job
            INNER JOIN prod_section_name ON prod_section_name.sectionid = prod_section_assign_job.section_id";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute();
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['section_name'];
            $nestedData[] = $row['job_title'];
            $nestedData[] = $row['assignjobid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function saveAssignEmployeeJobTitle($prod, $section_id, $pos_code)
    {
        $itemData_List = array();
        $chkExist = "SELECT * FROM prod_section_assign_job WHERE job_title = ? AND section_id = ?";
        $chkExist_stmt = $prod->prepare($chkExist);
        $chkExist_stmt->execute([$pos_code, $section_id]);
        //* ======== Prepare Array ========
        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO prod_section_assign_job(job_title,section_id) VALUES(?,?)";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([$pos_code, $section_id]);
            $itemData_List['result'] = 'save';
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadAssignEmployeeInfo($prod, $assignjobid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM prod_section_assign_job WHERE assignjobid = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$assignjobid]);
        //* ======== Prepare Array ========
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['job_title'] = $row['job_title'];
            $itemData_List['section_id'] = $row['section_id'];
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function updateAssignEmployeeJobTitle($prod, $assignjobid, $section_id, $pos_code)
    {
        $itemData_List = array();
        $chkExist = "SELECT * FROM prod_section_assign_job WHERE job_title = ? AND section_id = ? AND assignjobid <> ?";
        $chkExist_stmt = $prod->prepare($chkExist);
        $chkExist_stmt->execute([$pos_code, $section_id, $assignjobid]);
        //* ======== Prepare Array ========
        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "UPDATE prod_section_assign_job SET job_title = ? ,section_id = ? WHERE assignjobid = ?";
            $result_stmt = $prod->prepare($sqlstring);
            $result_stmt->execute([$pos_code, $section_id, $assignjobid]);
            $itemData_List['result'] = 'save';
        }
        return json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
    }

    public function removeAssignEmployeeJobTitle($prod, $assignjobid)
    {
        $sqlstring = "DELETE FROM prod_section_assign_job WHERE assignjobid = ?";
        $result_stmt = $prod->prepare($sqlstring);
        $result_stmt->execute([$assignjobid]);
        $prod = null; //* ======== Close Connection ========
    }

    public function loadSelectWithId($prod, $BannerWebLive, $category)
    {
        switch ($category) {
            case 'assignSection':
                $inTable = 'prod_section_name';
                $inFieldId = 'sectionid';
                $inField = 'section_name';
                $connection = 'production';
                break;
            case 'assignEmployee':
                $inTable = 'prod_section_name';
                $inFieldId = 'sectionid';
                $inField = 'section_name';
                $connection = 'production';
                break;
            case 'assignMachine':
                $inTable = 'prod_machine_name';
                $inFieldId = 'machineid';
                $inField = 'machine_name';
                $connection = 'production';
                break;
            default:
                $inTable = 'prl_position';
                $inFieldId = 'pos_code';
                $inField = 'pos_name';
                $connection = 'bannerweb';
                break;
        }

        $itemData_List = array();
        $sqlstring = "SELECT " . $inFieldId . ", " . $inField . " FROM " . $inTable . " ORDER BY " . $inField . " ASC";
        switch ($connection) {
            case 'bannerweb':
                $result_stmt = $BannerWebLive->prepare($sqlstring);
                break;
            default:
                $result_stmt = $prod->prepare($sqlstring);
                break;
        }
        $result_stmt->execute();
        foreach ($result_stmt->fetchAll() as $row) {
            $itemData_List[$row[$inFieldId]] = $row[$inField];
        }
        echo json_encode($itemData_List);
        $prod = null; //* ======== Close Connection ========
        $BannerWebLive = null; //* ======== Close Connection ========
    }
}
