var Portrait;
(function (Portrait) {
    var PortraitManager = (function () {
        function PortraitManager() {
        }
        PortraitManager.ShowPortrait = function (name) {
            var uri;
            if (name === "luana") {
                uri = "REST/api/v1/Portrait/Luana";
            }
            else {
                uri = "REST/api/v1/Portrait/Andy";
            }
            $.ajax({
                type: 'GET',
                url: uri,
                success: function (data) {
                    $("#maincontentArea").html(data);
                }
            });
        };
        return PortraitManager;
    }());
    Portrait.PortraitManager = PortraitManager;
})(Portrait || (Portrait = {}));
