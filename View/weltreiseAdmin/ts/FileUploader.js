var FileUploader;
(function (FileUploader) {
    var FileUploaderClass = (function () {
        function FileUploaderClass() {
        }
        FileUploaderClass.prototype.getForm = function (settings) {
            var form = '<form action="' + settings.restTarget + '" method="POST" enctype="multipart/form-data" target="upload-target">';
            form = form + '<div class="w3-row w3-margin-top">';
            form = form + '<input name="filename" id="FileUploader_fileInput" class="w3-input w3-border w3-twothird" placeholder="File" type="text">';
            form = form + '<button id="FileUploader_FileSelectionButton" class="w3-btn w3-third buttoncolor button-hover" style="max-width: 150px" type="button">Datei auswählen</button>';
            form = form + '<input name="uploadedfile" id="FileUploader_hiddenFileInput" style="visibility:hidden" type="file">';
            form = form + '<input name="doctype" id="doctype" type="hidden">';
            form = form + '</div>';
            form = form + '<div class="w3-row w3-margin-top">';
            form = form + '<button id="FileUploadButton" type="submit" class="w3-btn buttoncolor button-hover">Datei hochladen</button>';
            form = form + '</div>';
            form = form + '</form>';
            form = form + '<iframe id="upload-target" name="upload-target" src="#" style="width:250;height:100;border:0px solid #fff;"></iframe>';
            return form;
        };
        FileUploaderClass.prototype.Settings = function () {
            return {
                restTarget: "target"
            };
        };
        FileUploaderClass.prototype.Initialize = function (element, settings) {
            var form = this.getForm(settings);
            $("#" + element).html(form);
            $("#FileUploader_FileSelectionButton").click(function () {
                $("#FileUploader_hiddenFileInput").trigger('click');
            });
            $("#FileUploader_hiddenFileInput").change(function (files) {
                $("#FileUploader_fileInput").val(files.target.files[0].name);
            });
        };
        return FileUploaderClass;
    }());
    FileUploader.FileUploaderClass = FileUploaderClass;
})(FileUploader || (FileUploader = {}));
