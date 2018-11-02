module Startseite {
    export class StartseiteManager {

        public static Show() {
            $.get("REST/api/v1/Startseite/Site", function (data) {
                $("#maincontentArea").html(data);
                $("#addNotificationButton").click(function () {
                    StartseiteManager.addNotificationMail();
                });
            });
        }

        private static addNotificationMail() {
            $.ajax({
                url: "REST/api/v1/Startseite/Notification/"+ $("#notificationAddress").val(),
                type: "PUT",                
                success:
                function () {
                    alert("Ein Mail zur Verifizierung deiner E-Mail Adresse wurde versendet.");
                }
            });
        }

    }

}