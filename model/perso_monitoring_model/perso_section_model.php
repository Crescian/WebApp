<?php
date_default_timezone_set('Asia/Manila');
class PersoSection
{
    private function sqlQuery($sqlstring, $connection)
    {
        $data_base64 = base64_encode($sqlstring);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $connection);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
        $json_response = curl_exec($curl);
        //* ====== Close Connection ======
        curl_close($curl);
        return json_decode($json_response, true);
    }

    public function loadSectionTable($php_fetch_perso_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'perso_section_name',
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT * FROM bpi_perso_section WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND perso_section_name ILIKE '%{$searchValue}%'";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['perso_section_name'],
                $row['perso_sectionid']
            );
        }
        //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
    }

    public function loadSectionAssignedTable($php_fetch_bannerweb_api, $php_fetch_perso_api, $searchValue)
    {
        $itemData_List = array();
        //* ======== Create Array for column same with column names on database for ordering ========
        $col = array(
            0 => 'perso_assigned_name',
            1 => 'perso_section_name'
        );
        //* ======== Fetch Data ========
        $sqlstring = "SELECT perso_sect_assignid,perso_section_name,perso_assigned_name FROM bpi_perso_section_assigned 
            INNER JOIN bpi_perso_section ON bpi_perso_section.perso_sectionid = bpi_perso_section_assigned.perso_section_id WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        //* ======== Fetch Total Filtered Record ========
        if (!empty($searchValue)) {
            $sqlstring .= " AND (perso_section_name ILIKE '%{$searchValue}%' OR perso_assigned_name ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        //* ======== Ordering ========
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        //* ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            //* ======== Fetch Position Name ========
            $posCode = $row['perso_assigned_name'];
            $sqlFetch = "SELECT pos_name FROM prl_position WHERE pos_code = '{$posCode}';";
            $data_result_fetch = self::sqlQuery($sqlFetch, $php_fetch_bannerweb_api);
            foreach ($data_result_fetch['data'] as $row_fetch) {
                $pos_name = $row_fetch['pos_name'];
            }
            //* ======== Fetch Position Name End ========
            $itemData_List[] = array(
                $pos_name,
                $row['perso_section_name'],
                $row['perso_sect_assignid']
            );
        }
        //* ======== Output Data ========
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        //* ======== Send Data as JSON Format ========
        return json_encode($output);
    }

    public function saveSectionName($php_fetch_perso_api, $php_insert_perso_api, $section_name)
    {
        $chkExist = "SELECT * FROM bpi_perso_section WHERE perso_section_name = '{$section_name}';";
        $data_result_exist = self::sqlQuery($chkExist, $php_fetch_perso_api);
        $row_count = array_sum(array_map("count", $data_result_exist));
        if ($row_count == 0) {
            $sqlstring = "INSERT INTO bpi_perso_section(perso_section_name) VALUES('{$section_name}');";
            self::sqlQuery($sqlstring, $php_insert_perso_api);
            return json_encode('save');
        } else {
            return json_encode('existing');
        }
    }

    public function loadSectionName($php_fetch_perso_api, $perso_sectionid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_section WHERE perso_sectionid = '{$perso_sectionid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List['perso_section_name'] = $row['perso_section_name'];
        }
        return json_encode($itemData_List);
    }

    public function updateSectionName($php_fetch_perso_api, $php_update_perso_api, $perso_sectionid, $section_name)
    {
        $chkExist = "SELECT * FROM bpi_perso_section WHERE perso_section_name = '{$section_name}';";
        $data_result_exist = self::sqlQuery($chkExist, $php_fetch_perso_api);
        $row_count = array_sum(array_map("count", $data_result_exist));
        if ($row_count == 0) {
            $sqlstring = "UPDATE bpi_perso_section SET perso_section_name ='{$section_name}' WHERE perso_sectionid = '{$perso_sectionid}';";
            self::sqlQuery($sqlstring, $php_update_perso_api);
            return json_encode('update');
        } else {
            return json_encode('existing');
        }
    }

    public function deleteSectionName($php_update_perso_api, $perso_sectionid)
    {
        $sqlstring = "DELETE FROM bpi_perso_section WHERE perso_sectionid = '{$perso_sectionid}';";
        return json_encode(self::sqlQuery($sqlstring, $php_update_perso_api));
    }

    public function saveAssignedSection($php_fetch_perso_api, $php_insert_perso_api, $perso_section_id, $section_job_title)
    {
        $chkExist = "SELECT * FROM bpi_perso_section_assigned WHERE perso_section_id = '{$perso_section_id}' AND perso_assigned_name = '{$section_job_title}';";
        $data_result_exist = self::sqlQuery($chkExist, $php_fetch_perso_api);
        $row_count = array_sum(array_map("count", $data_result_exist));
        if ($row_count == 0) {
            $sqlstring = "INSERT INTO bpi_perso_section_assigned(perso_section_id,perso_assigned_name) VALUES('{$perso_section_id}','{$section_job_title}');";
            self::sqlQuery($sqlstring, $php_insert_perso_api);
            return json_encode('save');
        } else {
            return json_encode('existing');
        }
    }

    public function loadAssignedSection($php_fetch_perso_api, $perso_sect_assignid)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_section_assigned WHERE perso_sect_assignid = '{$perso_sect_assignid}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List['perso_section_id'] = $row['perso_section_id'];
            $itemData_List['perso_assigned_name'] = $row['perso_assigned_name'];
        }
        return json_encode($itemData_List);
    }

    public function updateAssignedSection($php_fetch_perso_api, $php_update_perso_api, $perso_sect_assignid,  $perso_section_id, $section_job_title)
    {
        $chkExist = "SELECT * FROM bpi_perso_section_assigned WHERE perso_section_id = '{$perso_section_id}' AND perso_assigned_name = '{$section_job_title}';";
        $data_result_exist = self::sqlQuery($chkExist, $php_fetch_perso_api);
        $row_count = array_sum(array_map("count", $data_result_exist));
        if ($row_count == 0) {
            $sqlstring = "UPDATE bpi_perso_section_assigned SET perso_section_id = '{$perso_section_id}', perso_assigned_name = '{$section_job_title}' WHERE perso_sect_assignid = '{$perso_sect_assignid}';";
            self::sqlQuery($sqlstring, $php_update_perso_api);
            return json_encode('update');
        } else {
            return json_encode('existing');
        }
    }

    public function deleteAssignedSection($php_update_perso_api, $perso_sect_assignid)
    {
        $sqlstring = "DELETE FROM bpi_perso_section_assigned WHERE perso_sect_assignid = '{$perso_sect_assignid}';";
        self::sqlQuery($sqlstring, $php_update_perso_api);
    }

    public function loadSelectSectionValues($php_fetch_perso_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM bpi_perso_section;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_perso_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[$row['perso_sectionid']] = $row['perso_section_name'];
        }
        return json_encode($itemData_List);
    }

    public function loadSelectJobTitleValues($php_fetch_bannerweb_api)
    {
        $itemData_List = array();
        $sqlstring = "SELECT * FROM prl_position ORDER BY pos_name ASC;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_bannerweb_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[$row['pos_code']] = $row['pos_name'];
        }
        return json_encode($itemData_List);
    }
}
