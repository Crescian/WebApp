<?php
class ITPushNotifications
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

    public function fetchNewRepairRequest($php_fetch_it_repair_api, $request_type)
    {
        $itemData_List = array();
        if ($request_type == 'repair') {
            $sqlstring = "SELECT * FROM tblit_repair WHERE status = 'On Hold'";
        } else {
            $sqlstring = "SELECT * FROM tblit_request WHERE status = 'Pending'";
        }
        $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        // * ======== Prepare Array ========
        foreach ($data_result['data'] as $row) {
            $nestedData = array();
            $nestedData['icon'] = 'http://192.107.17.48/BannerWebApp/vendor/images/Banner-Logo.png';
            if ($request_type == 'repair') {
                $nestedData['title'] = 'IT Repair Notification';
                $nestedData['body'] = $row['requested_by'] . ' - ' . $row['remarks'];
                $nestedData['url'] = 'http://192.107.17.48/BannerWebApp/itasset/it_repair_request_main.php?app_id=5';
            } else {
                $nestedData['title'] = 'IT Request Notification';
                $nestedData['body'] = $row['requested_by'] . ' - ' . $row['item'];
                $nestedData['url'] = 'http://192.107.17.48/BannerWebApp/itasset/it_request_main.php?app_id=5';
            }
            $itemData_List[] = $nestedData;
        }
        return json_encode($itemData_List);
    }
}
