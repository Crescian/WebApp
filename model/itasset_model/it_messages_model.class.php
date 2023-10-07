<?php
date_default_timezone_set('Asia/Manila');
class ITMessages
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

    public function timeAgo($timestamp)
    {
        $difference = time() - $timestamp;
        $periods = array("second", "minute", "hour");
        $lengths = array("60", "60", "24");

        if ($difference > 0) { // in the past
            $ending = "ago";
        } else { // in the future
            $ending = "from now";
            $difference = -$difference;
        }

        if ($difference < 86400) { // less than 24 hours ago
            for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
                $difference /= $lengths[$j];
            }
            $difference = round($difference);
            if ($difference != 1) {
                $periods[$j] .= "s";
            }
            return "$difference $periods[$j] $ending";
        } elseif ($difference < 172800) { // exactly 24 hours ago
            return "Yesterday";
        } else { // more than 24 hours ago
            return date("M j, Y H:i:s", $timestamp);
        }
    }

    public function loadConversationList($php_fetch_it_repair_api, $ITR)
    {
        $sqlstring = "SELECT t2.conversation_id, t2.sender, username, message, t1.created_at, unread_count FROM tblit_messages t1
            INNER JOIN tblit_conversation t2 ON t2.conversation_id = t1.conversation_id
            INNER JOIN (SELECT MAX(message_id) last_message_id FROM tblit_messages GROUP BY conversation_id) t3 ON t1.message_id = t3.last_message_id -- get last message
            LEFT JOIN (SELECT conversation_id, count(view) unread_count FROM tblit_messages WHERE view = false GROUP BY conversation_id) t4 ON t4.conversation_id = t1.conversation_id -- get unread messages count
            ORDER BY t1.created_at DESC";
        // $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();
        $result_stmt_res = $result_stmt->fetchAll();
        //* ======== Prepare Array ========
        foreach ($result_stmt_res as $row) {
            // foreach ($data_result['data'] as $row) {
            $data[] = array(
                "conversation_id" => $row['conversation_id'],
                "sender" => $row['sender'],
                "last_sender" => $row['username'],
                "message" => $row['message'],
                "created_at" => $row['created_at'],
                "unread_count" => $row['unread_count'] ?? ''
            );
        }
        $data ??= null;
        return json_encode($data);
    }

    public function loadConversationHeader($php_fetch_it_repair_api, $ITR, $id)
    {
        $itemData_List = array();
        $sqlstring = "SELECT DISTINCT tblit_conversation.conversation_id,sender FROM tblit_conversation 
			INNER JOIN tblit_messages ON tblit_messages.conversation_id = tblit_conversation.conversation_id
			WHERE tblit_conversation.conversation_id = '{$id}'";
        // $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        // foreach ($data_result['data'] as $row) {
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();
        while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemData_List['sender'] = $row['sender'];
            // $itemData_List[] = array(
            //     "sender" => $row['sender'],
            //     "prepared_by" => $row['prepared_by']
            // );
        }
        return json_encode($itemData_List);
    }

    public function loadMessages($php_fetch_it_repair_api, $ITR, $id)
    {
        if (!empty($id)) {
            // * =========== Update all unread messages ===========
            $updateQuery = "UPDATE tblit_messages SET VIEW = true WHERE view <> true AND conversation_id = '{$id}'";
            self::sqlQuery($updateQuery, $php_fetch_it_repair_api);

            // * =========== Fetch messages ===========
            $sqlstring = "SELECT message, created_at, username FROM tblit_messages WHERE conversation_id = '{$id}' ORDER BY message_id DESC LIMIT 20";
            // $data_result = self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
            // foreach ($data_result['data'] as $row) {
            $result_stmt = $ITR->prepare($sqlstring);
            $result_stmt->execute();
            $result_stmt_res = $result_stmt->fetchAll();
            //* ======== Prepare Array ========
            foreach ($result_stmt_res as $row) {
                if ($row['username'] == "ITD") {
                    $data['message'][] =
                        array(
                            "sender" => array(
                                "message" => $row['message'],
                                "created_at" => $row['created_at']
                            )
                        );
                } else {
                    $data['message'][] =
                        array(
                            "receiver" => array(
                                "message" => $row['message'],
                                "created_at" => $row['created_at']
                            )
                        );
                }
            }
            return json_encode($data); //todo
        }
    }

    public function sendMessages($php_fetch_it_repair_api, $ITR, $conversation_id, $message)
    {
        $sqlstring = "INSERT INTO tblit_messages(conversation_id, username, message) VALUES('{$conversation_id}', 'ITD', '{$message}')";
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();
        // self::sqlQuery($sqlstring, $php_fetch_it_repair_api);
        return true;
    }
}
