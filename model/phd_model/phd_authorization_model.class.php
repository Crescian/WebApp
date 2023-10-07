<?php
class PhdAuthorizedModule
{
    public function fetchCheckByData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_authorized_checked_by";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['checked_by_name'];
            $nestedData[] = $row['phdcheckedbyid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function fetchNotedByData($PHD)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM phd_authorized_noted_by";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {

            $nestedData = array();
            $nestedData[] = $row['noted_by_name'];
            $nestedData[] = $row['phdnotedbyid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveDataCheckAndNoted($PHD, $employee_name, $inTable, $inField)
    {
        $chkExist = "SELECT * FROM " . $inTable . " WHERE " . $inField . " = ?";
        $chkExist_stmt = $PHD->prepare($chkExist);
        $chkExist_stmt->execute([$employee_name]);
        if ($chkExist_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'existing';
        } else {
            $sqlstring = "INSERT INTO " . $inTable . "(" . $inField . ") VALUES(?)";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$employee_name]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteDataChecked($PHD, $phdcheckedbyid)
    {
        $sqlstring = "DELETE FROM phd_authorized_checked_by WHERE phdcheckedbyid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$phdcheckedbyid]);
        $PHD = null; //* ======== Close Connection ========
    }
    public function deleteDataNoted($PHD, $phdnotedbyid)
    {
        $sqlstring = "DELETE FROM phd_authorized_noted_by WHERE phdnotedbyid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$phdnotedbyid]);
        $PHD = null; //* ======== Close Connection ========
    }
}
