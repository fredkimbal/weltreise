module Verify {

    export class VerifyManager {

        public static Verify(token: string) {
            $.ajax({
                url: "REST/api/v1/Startseite/Notification/" + token,
                type: "UPDATE",
                success:
                function (data) {
                    $("#maincontentArea").html(data);
                }
            });
        }

        public static Unsubscribe(token: string) {
            $.ajax({
                url: "REST/api/v1/Startseite/Notification/" + token,
                type: "DELETE",
                success:
                function (data) {
                    $("#maincontentArea").html(data);
                }
            });
        }

    }

}
