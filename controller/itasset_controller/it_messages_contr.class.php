<?php
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/itasset_model/it_messages_model.class.php';
    $ITR = $conn->db_conn_it_repair_request(); //* IT REPAIR AND REQUEST Database connection
    $ITMessage = new ITMessages();
    $action = trim($_POST['action']);

    switch ($action) {
        case 'load_conversation_list':
            echo $ITMessage->loadConversationList($php_fetch_it_repair_api, $ITR);
            break;

        case 'load_conversation_header':
            $id = trim($_POST['id']);
            echo $ITMessage->loadConversationHeader($php_fetch_it_repair_api, $ITR, $id);
            break;

        case 'load_messages':
            $id = trim($_POST['id']);
            echo $ITMessage->loadMessages($php_fetch_it_repair_api, $ITR, $id);
            break;

        case 'send_messages':
            $conversation_id = trim($_POST['conversation_id']);
            $message = trim($_POST['message']);
            echo $ITMessage->sendMessages($php_fetch_it_repair_api, $ITR, $conversation_id, $message);
            break;
    }
}
