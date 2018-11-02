var GPX;
(function (GPX) {
    var GpxManager = (function () {
        function GpxManager() {
        }
        GpxManager.show = function () {
            $.get("../REST/api/v1/GPX/site", function (data) {
                $("#maincontentArea").html(data);
                var uploader = new FileUploader.FileUploaderClass();
                uploader.Initialize("importForm", {
                    restTarget: "../REST/api/v1/GPX/File"
                });
            });
        };
        return GpxManager;
    }());
    GPX.GpxManager = GpxManager;
})(GPX || (GPX = {}));
