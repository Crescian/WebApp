<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
// date_default_timezone_set('Asia/Manila');
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach ($result_res as $row) {
    // $data_base64 = base64_encode($sqlstring);
    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    // $json_response = curl_exec($curl);
    // //* ====== Close Connection ======
    // curl_close($curl);
    // // * ======== Prepare Array ========
    // $data_result = json_decode($json_response, true);
    // foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
} ?>
<link rel="stylesheet" type="text/css" href="css/it_messages.css" />
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-4" style="max-height: 100vh;">
            <div class="row mb-4 shadow">
                <span class="page-title-it">Request Messages</span>
            </div>

            <div class="card shadow border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger">Repair & Request Conversation</h4>
                        <!-- <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fa-solid fa-plus"></i> New Conversation</button> -->
                    </div>
                </div>
            </div>
            <div class="card shadow border-0" id="conversation_card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 conversation-list-container">
                            <div class="conversation-list" id="conversation_list"></div>
                        </div>
                        <div class="col-md-8 conversation-card-container">
                            <div class="card border-0 shadow conversation-card">

                                <div class="card-header d-flex justify-content-between align-items-center invisible">
                                    <div>
                                        <h5 class="card-title" id="header_number"></h5>
                                        <div class="text-secondary fs-6" id="header_name"></div>
                                    </div>
                                    <div class="close-conversation" id="close_conversation"><i class="fa-solid fa-xmark"></i></div>
                                </div>
                                <div class="card-body invisible">
                                    <div class="message-body" id="message_body">
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center invisible">
                                    <textarea type="text" class="message-input" id="message_input" placeholder="Enter your message here..."></textarea>
                                    <button class="btn btn-danger rounded-pill" id="message_send"><i class="fa-solid fa-paper-plane"></i> Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-danger border rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
        <!-- ==================== CARD SECTION ==================== -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-1 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">IT ASSET</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
        <!-- ==================== CARD SECTION END ==================== -->
    </div>
</div>

<?php include './../includes/footer.php'; ?>
<script>
    let timeoutId;
    let activeConversation;
    loadConversationList();
    setInterval(loadConversationList, 2000);

    function scrollToLastMessage() {
        setTimeout(() => {
            $('.message-bubble').last()[0].scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }, 250);
    }

    function loadConversationList() {
        $.ajax({
            url: 'functions/it_message-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadConversationList'
            },
            success: res => {
                if (res) {
                    let conversationItems = ``;
                    $.each(res, (index, value) => {
                        conversationItems +=
                            `<div class="conversation-list-item" data-id="${value.conversation_id}">
                                <div class="overflow-hidden">
                                    <div class="name text-nowrap text-truncate">${value.sender}</div>
                                    <div class="last-message text-truncate"><span class="fw-bold">${value.last_sender == value.sender ? value.sender + ': ': 'You: '}</span>${value.message}</div>
                                </div>
                                <div>
                                    <div class="date text-nowrap mb-2">${value.created_at}</div>
                                    <div class="badge rounded-pill text-bg-danger float-end">${value.unread_count <= 10 ? value.unread_count : '10+'}</div>
                                </div>
                            </div>`;
                    });

                    $('#conversation_list').html(conversationItems);
                    $(`.conversation-list-item[data-id="${activeConversation}"]`).addClass('active');

                } else {
                    $('#conversation_list').html(`<h5 class="text-secondary">No Conversation...</h5>`);
                }
            }
        });
    }

    function loadConversationHeader() {
        const activeItem = $('.conversation-list-item.active');
        $.ajax({
            url: 'functions/it_message-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadConversationHeader',
                id: activeItem.data('id')
            },
            success: res => {
                $('#header_number').text(res.sender);
                $('#header_name').text(res.requested_by);
            }
        });
    }

    function loadMessages() {
        const activeItem = $('.conversation-list-item.active');
        const messageBody = $('#message_body');
        if (activeItem[0]) {
            $.ajax({
                url: 'functions/it_message-function.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'loadMessages',
                    id: activeItem.data('id')
                },
                success: res => {
                    let messageArray = res.message;
                    if (res) {
                        const messageContent = Object.entries(messageArray)
                            .reverse()
                            .map(([key, value]) => `<div class="message-bubble" data-date="${value[Object.keys(value)].created_at}" id="${Object.keys(value)}">${value[Object.keys(value)].message}</div>`)
                            .join('');
                        messageBody.html(messageContent);
                        $('.message-bubble').last().css('animation', '');
                    }
                    timeoutId = setTimeout(loadMessages, 2000); //* recursive call after 2 seconds
                },
                error: () => {
                    timeoutId = setTimeout(loadMessages, 5000); //* recursive call after 5 seconds
                }
            });
        } else {
            location.reload();
        }
    }

    function sendMessage() {
        let conversationId = $('.conversation-list-item.active').data('id');
        let messageVal = $('#message_input').val().trim();
        if (messageVal) {
            $('#message_input').prop('disabled', true);
            $('#message_send').prop('disabled', true);
            $.ajax({
                url: 'functions/it_message-function.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'sendMessages',
                    conversation_id: conversationId,
                    message: messageVal
                },
                success: res => {
                    if (res) {
                        $('#message_input').prop('disabled', false);
                        $('#message_send').prop('disabled', false);
                        loadMessages();
                        scrollToLastMessage();
                        $('#message_input').val('').focus();
                        $('.message-bubble').last().css('animation', '.25s forwards expand-bounce');
                    }
                }
            })
        }
    }

    $('#message_send').on('click', () => {
        sendMessage();
    });

    $('#message_input').on('keyup', (event) => {
        if (event.keyCode === 13) {
            sendMessage();
        }
    });

    $('#conversation_list').on('click', '.conversation-list-item', (e) => {
        $('.conversation-card').children().removeClass('invisible').hide().fadeIn();
        $('.conversation-card-container').addClass('show').removeClass('hide');
        $('.conversation-list-container').addClass('hide').removeClass('show');
        let item = $(e.currentTarget);
        activeConversation = item.data('id');
        $('#message_send').attr('data-id', item.data('id'))
        $('.conversation-list-item').toggleClass('active', false);
        item.toggleClass('active', true);
        clearTimeout(timeoutId); //* clear the previous timeout
        loadMessages(); //* start the long polling
        scrollToLastMessage();
        loadConversationHeader();
    });

    $('#close_conversation').click(() => {
        $('.conversation-card-container').addClass('hide').removeClass('show');
        $('.conversation-list-container').addClass('show').removeClass('hide');
    });
</script>