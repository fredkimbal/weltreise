var Startseite;
(function (Startseite) {
    var StartseiteManager = (function () {
        function StartseiteManager() {
        }
        StartseiteManager.Show = function () {
            $.get("REST/api/v1/Startseite/Site", function (data) {
                $("#maincontentArea").html(data);
                $("#addNotificationButton").click(function () {
                    StartseiteManager.addNotificationMail();
                });
            });
        };
        StartseiteManager.addNotificationMail = function () {
            $.ajax({
                url: "REST/api/v1/Startseite/Notification/" + $("#notificationAddress").val(),
                type: "PUT",
                success: function () {
                    alert("Ein Mail zur Verifizierung deiner E-Mail Adresse wurde versendet.");
                }
            });
        };
        return StartseiteManager;
    }());
    Startseite.StartseiteManager = StartseiteManager;
})(Startseite || (Startseite = {}));
