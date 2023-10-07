<script>
    function createNotification(title, icon, body, url) {
        var notification = new Notification(title, {
            icon: icon,
            body: body,
        });
        //* url that needs to be opened on clicking the notification
        //* finally everything boils down to click and visits right
        notification.onclick = function() {
            window.open(url);
        };
        return notification;
    }
</script>