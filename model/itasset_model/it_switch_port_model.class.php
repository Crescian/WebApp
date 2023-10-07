<?php
date_default_timezone_set('Asia/Manila');
class ITSwitchAndPort
{
    public function sqlQuery($sqlstring, $connection)
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
    public function load_table_switch($php_fetch_itasset_api, $searchValue)
    {
        $itemData_List = array();

        $col = array(
            0 => 'lan_cable',
            1 => 'location',
            2 => 'switch',
            3 => 'port',
            4 => 'switchmoduleid'
        );
        $sqlstring = "SELECT * FROM tblit_switch_module_assign_switch INNER JOIN tblit_switch_module 
        ON tblit_switch_module.assignswitchid = tblit_switch_module_assign_switch.switch WHERE 1 = 1";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        $result_total_record = array_sum(array_map("count", $data_result));
        if (!empty($searchValue)) {
            $sqlstring .= " AND (lan_cable ILIKE '%{$searchValue}%' OR location ILIKE '%{$searchValue}%' OR switch ILIKE '%{$searchValue}%' OR CAST(port AS TEXT) ILIKE '%{$searchValue}%')";
            $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        }
        $result_total_record_filtered = array_sum(array_map("count", $data_result));
        $sqlstring .= " ORDER BY {$col[$_POST['order'][0]['column']]} {$_POST['order'][0]['dir']} LIMIT {$_POST['length']} OFFSET {$_POST['start']};";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $itemData_List[] = array(
                $row['lan_cable'],
                $row['location'],
                $row['switch'],
                $row['port'],
                $row['switchmoduleid']
            );
        }
        $output = array(
            'draw'                  =>  intval($_POST['draw']),
            'iTotalRecords'         =>  $result_total_record,
            'iTotalDisplayRecords'  =>  $result_total_record_filtered,
            'data'                  =>  $itemData_List
        );
        return json_encode($output);
    }

    public function loadSwitch($php_fetch_itasset_api)
    {
        $sqlstring = "SELECT assignswitchid,switch FROM tblit_switch_module;";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $data[$row['assignswitchid']] = $row['switch'];
        }
        return json_encode($data);
    }
    public function switchAssign($php_insert_itasset_api, $switch, $port)
    {
        $sqlstring = "INSERT INTO tblit_switch_module(switch,port)VALUES('{$switch}','{$port}');";
        self::sqlQuery($sqlstring, $php_insert_itasset_api);
    }
    public function getThePort($php_fetch_itasset_api, $letter)
    {
        $sqlstring = "SELECT * FROM tblit_switch_module WHERE assignswitchid = '{$letter}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        $data = [];
        foreach ($data_result['data'] as $row) {
            $data['data'][] = $row['port'];
        }

        $sqlstringGetPort = "SELECT port FROM tblit_switch_module_assign_switch WHERE switch = '{$letter}' AND status = true;";
        $data_result = self::sqlQuery($sqlstringGetPort, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $data['portOkay'][] = $row['port'];
        }

        $sqlstringGetPortNotOkay = "SELECT port FROM tblit_switch_module_broken_port WHERE switch = '{$letter}';";
        $data_result = self::sqlQuery($sqlstringGetPortNotOkay, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $data['portNotOkay'][] = $row['port'];
        }

        return json_encode($data);
    }
    public function saveAssign($php_insert_itasset_api, $lan_cable, $location, $switchLocation, $portLocation)
    {
        $sqlstring = "INSERT INTO tblit_switch_module_assign_switch(lan_cable,location,switch,port)VALUES('{$lan_cable}','{$location}','{$switchLocation}','{$portLocation}');";
        self::sqlQuery($sqlstring, $php_insert_itasset_api);
    }
    public function editFunction($php_fetch_itasset_api, $id)
    {
        $sqlstring = "SELECT * FROM tblit_switch_module_assign_switch WHERE switchmoduleid = '{$id}';";
        $data_result = self::sqlQuery($sqlstring, $php_fetch_itasset_api);
        foreach ($data_result['data'] as $row) {
            $data['lan_cable'] = $row['lan_cable'];
            $data['location'] = $row['location'];
            $data['switch'] = $row['switch'];
            $data['port'] = $row['port'];
        }
        return json_encode($data);
    }
    public function updateAssign($php_update_itasset_api, $php_insert_itasset_api, $lan_cable, $location, $switch, $port, $portReplica, $status, $id)
    {
        $sqlstring = "UPDATE tblit_switch_module_assign_switch SET lan_cable = '{$lan_cable}', location = '{$location}', switch = '{$switch}' , port = '{$port}', status = true WHERE switchmoduleid = '{$id}';";
        self::sqlQuery($sqlstring, $php_update_itasset_api);
        if ($status == 'false') {
            $sqlstringBroken = "INSERT INTO tblit_switch_module_broken_port(switchmodule_id,switch,port)VALUES('{$id}','{$switch}','{$portReplica}');";
            self::sqlQuery($sqlstringBroken, $php_insert_itasset_api);
        }
    }
}
