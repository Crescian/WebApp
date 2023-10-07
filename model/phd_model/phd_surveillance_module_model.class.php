<?php
class PhdSurveillance
{
    public function fetchData($PHD)
    {
        $itemData_List = array();
        // $sqlstring = "SELECT * FROM phd_surveillance_name ORDER BY surveillanceid ASC";
        $sqlstring = "SELECT * FROM phd_surveillance_name";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $nestedData = array();
            $nestedData[] = $row['surveillance_name'];
            $nestedData[] = $row['surveillanceid'];
            $itemData_List['data'][] = $nestedData;
        }
        return json_encode($itemData_List);
        $PHD = null; //* ======== Close Connection ========
    }
    public function saveData($PHD, $surveillance_name)
    {
        $sqlstringScan = "SELECT * FROM phd_surveillance_name WHERE surveillance_name = ?";
        $result_stmt_scan = $PHD->prepare($sqlstringScan);
        $result_stmt_scan->execute([$surveillance_name]);
        if ($result_stmt_scan->rowCount() > 0) {
            $itemData_List['result'] = 'exist';
        } else {
            $sqlstring = "INSERT INTO phd_surveillance_name(surveillance_name)VALUES(?)";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$surveillance_name]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
    }
    public function deleteData($PHD, $id)
    {
        $sqlstring = "DELETE FROM phd_surveillance_name WHERE surveillanceid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
    }
    public function previewData($PHD, $id)
    {
        $sqlstring = "SELECT * FROM phd_surveillance_name WHERE surveillanceid = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$id]);
        foreach ($result_stmt as $row) {
            $itemData_List['result'] = $row['surveillance_name'];
        }
        return json_encode($itemData_List);
    }
    public function updateData($PHD, $surveillance_name, $id)
    {
        $sqlstring = "SELECT * FROM phd_surveillance_name WHERE surveillance_name = ?";
        $result_stmt = $PHD->prepare($sqlstring);
        $result_stmt->execute([$surveillance_name]);
        if ($result_stmt->rowCount() > 0) {
            $itemData_List['result'] = 'exist';
        } else {
            $sqlstring = "UPDATE phd_surveillance_name SET surveillance_name = ? WHERE surveillanceid = ?";
            $result_stmt = $PHD->prepare($sqlstring);
            $result_stmt->execute([$surveillance_name, $id]);
            $itemData_List['result'] = '';
        }
        return json_encode($itemData_List);
    }
}
