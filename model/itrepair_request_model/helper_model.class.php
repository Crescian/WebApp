<?php
class ItRepairRequestHelper
{

    public function onLoadInputs($ITR, $BannerWeb)
    {
        $stmt = $ITR->prepare("SELECT * FROM tblit_item");
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rowData as $row) {
            $data["item"][] = $row["item_name"];
        }

        $stmt = $ITR->prepare("SELECT * FROM tblit_area");
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data["area"][] = $row["area_name"];
        }

        $stmt = $BannerWeb->prepare("SELECT dept_code, department FROM bpi_department WHERE dept_code <> 'IMS' ORDER by department");
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data["department"][$row['dept_code']] = $row['department'];
        }

        return json_encode($data);
        $ITR = null;
        $BannerWeb = null;
    }
    public function loadEditRequest($BannerWeb, $dept_code)
    {
        $itemData_List = array();
        $stmt = $BannerWeb->prepare("SELECT DISTINCT empno,emp_fn || ' ' || emp_sn AS full_name,dept_code FROM prl_employee WHERE dept_code = ?");
        $stmt->execute([$dept_code]);
        foreach ($stmt->fetchAll() as $row) {
            $itemData_List[$row['full_name']] = $row['full_name'];
        }
        // print_r($data);
        return json_encode($itemData_List);
        $BannerWeb = null;
    }
    public function loadLocation($ITR, $area)
    {
        $stmt = $ITR->prepare("SELECT location_name FROM tblit_location
                      INNER JOIN tblit_area ON tblit_area.area_id = tblit_location.area_id
                      WHERE area_name = ?");
        $stmt->execute([$area]);

        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data["location"][] = $row['location_name'];
        }
        return json_encode($data);
        $ITR = null;
    }
    public function loadEmployeeDepartment($BannerWeb, $employee)
    {
        $stmt = $BannerWeb->prepare("SELECT DISTINCT emp_fn || ' ' || emp_sn AS full_name, dept_code FROM prl_employee 
            WHERE emp_fn || ' ' || emp_sn = ?");
        $stmt->execute([$employee]);
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data["department"] = $row['dept_code'];
        }
        $data["department"] ??= [""];

        // print_r($data);
        return json_encode($data);
        $BannerWeb = null;
    }
    public function loadEmployee($BannerWeb, $deptCode)
    {
        $stmt = $BannerWeb->prepare("SELECT DISTINCT emp_fn||' '||emp_sn AS full_name  FROM prl_employee 
                WHERE emp_stat NOT IN ('Resigned','Terminated','End Contract','Project Completion', 'Retired', 'Resigned Non-compliance') 
                AND dept_code = ?
                ORDER BY 1");
        $stmt->execute([$deptCode]);
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data["employee"][] = $row['full_name'];
        }
        $data["employee"] ??= [""];

        return json_encode($data);
        $BannerWeb = null;
    }
    public function loadDepartmentHead($BannerWeb)
    {
        $stmt = $BannerWeb->prepare("SELECT emp_fn||' '||emp_sn AS full_name, pos_code FROM prl_employee WHERE job_level in('Level-08', 'Level-07') ORDER BY full_name");
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data["deptHead"][$row["pos_code"]] = $row["full_name"];
        }

        return json_encode($data);
        $BannerWeb = null;
    }
}
