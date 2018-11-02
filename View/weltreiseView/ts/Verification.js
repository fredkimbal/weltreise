var Verify;
(function (Verify) {
    var VerifyManager = (function () {
        function VerifyManager() {
        }
        VerifyManager.Verify = function (token) {
            $.ajax({
                url: "REST/api/v1/Startseite/Notification/" + token,
                type: "UPDATE",
                success: function (data) {
                    $("#maincontentArea").html(data);
                }
            });
        };
        VerifyManager.Unsubscribe = function (token) {
            $.ajax({
                url: "REST/api/v1/Startseite/Notification/" + token,
                type: "DELETE",
                success: function (data) {
                    $("#maincontentArea").html(data);
                }
            });
        };
        return VerifyManager;
    }());
    Verify.VerifyManager = VerifyManager;
})(Verify || (Verify = {}));
