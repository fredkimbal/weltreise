module GPX {
    
    export class GpxManager {

        public static show() {
            $.get("../REST/api/v1/GPX/site", function (data) {
                $("#maincontentArea").html(data);
                var uploader = new FileUploader.FileUploaderClass();
                uploader.Initialize("importForm", {
                    restTarget: "../REST/api/v1/GPX/File"
                });
            });
        }
    }
}