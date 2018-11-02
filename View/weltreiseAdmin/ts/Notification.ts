module onTour_Notification {
    export class NotificationManager{

        public static Show() {
            $.get("../REST/api/v1/Notification/site", function (data) {
                $("#maincontentArea").html(data);

                $("#sendNotification").click(function () {
                    var data = $('#notificationForm').serialize();
                    $.post("../REST/api/v1/Notification/Send", data, function (data) {
                        alert(data);
                    });
                });

            });
        }

    }
}