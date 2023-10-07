<script>
    loadAppMenu();
    idleLogout();
    //* ======================= Check User Logged In =======================
    setInterval(function() {
        isLoggedIn();
    }, 5000);

    //* ======================= Idle Timer =======================
    function idleLogout() {
        var t;
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onmousedown = resetTimer; //* catches touchscreen presses as well      
        window.ontouchstart = resetTimer; //* catches touchscreen swipes as well      
        window.ontouchmove = resetTimer; //* required by some devices 
        window.onclick = resetTimer; //* catches touchpad clicks as well
        window.onkeydown = resetTimer;
        window.addEventListener('scroll', resetTimer, true);

        function doInactive() {
            //* your function for too long inactivity goes here
            location.href = '../functions/logout.php';
        }

        function resetTimer() {
            clearTimeout(t);
            t = setTimeout(doInactive, 3600000); //* time is in milliseconds - 1hr
        }
    }
    //* ======================= Idle Timer End =======================

    // ======================= Disable Back to previous page =======================
    // window.history.pushState(null, "", window.location.href);
    // window.onpopstate = function () {
    //     window.history.pushState(null, "", window.location.href);
    // };
    // history.pushState(null, null, document.URL);
    // window.addEventListener('popstate', function () {
    //     history.pushState(null, null, document.URL);
    // });
    // ======================= Disable Back to previous page End =======================

    function isLoggedIn() {
        $.ajax({
            url: '../functions/login.php',
            type: 'POST',
            data: {
                action: 'isLoggedIn'
            },
            success: function(result) {
                if (result == 0) { // Log Out
                    location.href = '../functions/logout.php';
                }
            }
        });
    }

    function loadAppMenu() {
        $.ajax({
            url: '../functions/Menu.functions.php',
            type: 'POST',
            data: {
                action: 'loadAppMenu',
                app_id: <?= $_GET['app_id'] ?>
            },
            success: function(result) {
                $('.menu').html(result);
                $('.main-menu .list-group-item').click((e) => {
                    $('.main-menu .list-group-item').removeClass('menu-active').children().removeClass('fa-folder-open');
                    $('.main-menu [aria-expanded="true"]').addClass('menu-active').children().addClass('fa-folder-open');
                });
            }
        });
    }

    function menuNav() {
        $('.content').addClass('d-none');
        $('.menu-card').removeClass('d-none').addClass('d-sm-block');
    }

    function menuPanelClose() {
        $('.content').removeClass('d-none');
        $('.menu-card').removeClass('d-sm-block').addClass('d-none');
    }

    //* ======================= Card Hover Action =======================
    $('.card_hover').on('click', (event) => {
        $('.card_hover').removeClass('active');
        $(event.currentTarget).addClass('active');
    })
    //* ======================= Card Hover Action End =======================
</script>