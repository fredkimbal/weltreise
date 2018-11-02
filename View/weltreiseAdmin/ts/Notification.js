var onTour_Notification;
(function (onTour_Notification) {
    var NotificationManager = (function () {
        function NotificationManager() {
        }
        NotificationManager.Show = function () {
            $.get("../REST/api/v1/Notification/site", function (data) {
                $("#maincontentArea").html(data);
                $("#sendNotification").click(function () {
                    var data = $('#notificationForm').serialize();
                    $.post("../REST/api/v1/Notification/Send", data, function (data) {
                        alert(data);
                    });
                });
            });
        };
        return NotificationManager;
    }());
    onTour_Notification.NotificationManager = NotificationManager;
})(onTour_Notification || (onTour_Notification = {}));
