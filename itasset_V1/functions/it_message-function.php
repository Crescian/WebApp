<?php
include '../../configuration/connection.php';
session_start();
date_default_timezone_set('Asia/Manila');
$ITR = $conn->db_conn_it_repair_request(); //* IT Repair Request Database connection

function timeAgo($timestamp)
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

if (isset($_POST['action'])) {
    $action = trim($_POST['action']);

    switch ($action) {
        case 'loadConversationList':
            // * =========== Fetch All Incoming Messages ===========
            $query = "SELECT  t2.conversation_id, t2.sender, username, message, t1.created_at, unread_count
                        FROM tblit_messages t1
                      INNER JOIN tblit_conversation t2 -- get details
                        ON t2.conversation_id = t1.conversation_id
                      INNER JOIN (SELECT MAX(message_id) last_message_id FROM tblit_messages GROUP BY conversation_id) t3 -- get last message
                        ON t1.message_id = t3.last_message_id
                      LEFT JOIN (SELECT conversation_id, count(view) unread_count FROM tblit_messages WHERE view = false GROUP BY conversation_id) t4 -- get unread messages count
                        ON t4.conversation_id = t1.conversation_id
                      ORDER BY t1.created_at DESC";
            $stmt = $ITR->prepare($query);
            $stmt->execute();
            $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rowData as $row) {
                $data[] = array(
                    "conversation_id" => $row['conversation_id'],
                    "sender" => $row['sender'],
                    "last_sender" => $row['username'],
                    "message" => $row['message'],
                    "created_at" => timeAgo(strtotime($row['created_at'])),
                    "unread_count" => $row['unread_count'] ?? ''
                );
            }
            $data ??= null;
            echo json_encode($data);
            $ITR = null;
            break;

        case 'loadConversationHeader':
            // * =========== Fetch User Details(Name & Ref. No.) ===========
            $id = $_POST['id'];
            $query = "SELECT sender, requested_by 
                      FROM tblit_conversation
                      INNER JOIN
                        (SELECT queue_number, requested_by FROM tblit_request
                            UNION
                        SELECT queue_number, requested_by FROM tblit_repair
                         ) t2
                            ON t2.queue_number = tblit_conversation.sender 
                      WHERE conversation_id = ?";
            $stmt = $ITR->prepare($query);
            $stmt->execute([$id]);
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = $rowData;
            echo json_encode($data);
            $ITR = null;
            break;

        case 'loadMessages':
            $id = $_POST['id'];
            if (!empty($id)) {
                // * =========== Update all unread messages ===========
                $updateQuery = "UPDATE tblit_messages SET VIEW = true WHERE view <> true AND conversation_id = ?";
                $ITR->prepare($updateQuery)->execute([$id]);

                // * =========== Fetch messages ===========
                $query = "SELECT message, created_at, username FROM tblit_messages WHERE conversation_id = ? ORDER BY message_id DESC LIMIT 20";
                $stmt = $ITR->prepare($query);
                $stmt->execute([$id]);
                $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rowData as $row) {
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
                echo json_encode($data); //todo
                $ITR = null;
            }
            break;

        case 'sendMessages':
            $conversationId = $_POST['conversation_id'];
            $message = $_POST['message'];

            $query = "INSERT INTO tblit_messages(conversation_id, username, message) VALUES(?, ?, ?)";
            $stmt = $ITR->prepare($query)->execute([$conversationId, "ITD", $message]);
            echo true;
            $ITR = null;
            break;
    }
}
