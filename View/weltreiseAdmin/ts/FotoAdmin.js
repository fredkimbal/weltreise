var FotoAdmin;
(function (FotoAdmin) {
    var FotoAdminManager = (function () {
        function FotoAdminManager() {
        }
        FotoAdminManager.showPage = function () {
            CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/Show', function (data) {
                $("#maincontentArea").html(data);
                FotoAdminManager.addEventListener("upload");
                $(".tablink").click(function (e) {
                    var site = e.target.getAttribute("data-targetsite");
                    CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/GetContent/' + site, function (data) {
                        $("#fotoadmin_content").html(data);
                        FotoAdminManager.addEventListener(site);
                    });
                    $.each(e.target.parentElement.children, function (i, c) {
                        if (c !== e.target) {
                            c.classList.remove("shaddow");
                            c.classList.add("buttoncolor");
                        }
                    });
                    e.target.classList.remove("buttoncolor");
                    e.target.classList.add("shaddow");
                });
            });
        };
        FotoAdminManager.addEventListener = function (siteID) {
            if (siteID === "upload") {
                FotoAdmin.FotoAdminManager.addUploadEventListeners();
            }
            if (siteID === "assign") {
                $("#assignFoto").click(function () {
                    FotoAdminManager.assignFoto();
                });
            }
            if (siteID === "admin") {
                FotoAdminManager.addAdminEventListeners();
            }
        };
        FotoAdminManager.assignFoto = function () {
            var data = $('#AssignFotoForm').serialize();
            CommonFunctions.Functions.DoAjaxPost('../REST/api/v1/I_FotoAdmin/AssignFoto', data, function (response) {
                $("#LogWindow").html(response);
                $('input:checkbox').removeAttr('checked');
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/GetNextUnaloccatedPicture', function (response) {
                    $("#assigningPic").attr("src", response.path);
                    $("#picID").attr("value", response.id);
                });
            });
        };
        FotoAdminManager.addAdminEventListeners = function () {
            $("#yearDropDownBtn").click(function () {
                CommonFunctions.Functions.ComboboxClick("#yearDropDownContent");
            });
            $("#eventDropDownBtn").click(function () {
                CommonFunctions.Functions.ComboboxClick("#eventDropDownContent");
            });
            $(".yearComboChilds").click(function (e) {
                var year = e.target.textContent;
                CommonFunctions.Functions.ComboboxClick("#yearDropDownContent");
                $("#yearDropDownBtn").html("<i class='fa fa-caret-down'/> " + year);
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/GetEventComboItemsByYear/' + year, function (response) {
                    $("#eventDropDownContent").html(response);
                    FotoAdminManager.addEventComboChildsEvent();
                });
            });
            FotoAdminManager.addEventComboChildsEvent();
        };
        FotoAdminManager.addEventComboChildsEvent = function () {
            $(".eventComboChilds").click(function (e) {
                var year = e.target.textContent;
                CommonFunctions.Functions.ComboboxClick("#eventDropDownContent");
                var eventID = $(e.target).attr("data-event-ID");
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/GetEventAdminView/' + eventID, function (response) {
                    $("#galleryView").html(response);
                    FotoAdminManager.addAdminViewEvents();
                });
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/Event/' + eventID + '/showpictures', function (response) {
                    $("#galleryPictures").html(response);
                    FotoAdminManager.addEventPicturesEventListener();
                });
            });
        };
        FotoAdminManager.addEventPicturesEventListener = function () {
            $(".rotateleftbutton").click(function (e) {
                var id = $(e.target).attr("data-imageid");
                CommonFunctions.Functions.DoAjaxUpdatePath('../REST/api/v1/I_FotoAdmin/Pic/' + id + '/rotateleft', function () {
                    var d = new Date();
                    var id = e.target.getAttribute("data-imageID");
                    var path = $("#" + id).attr("src") + "?" + d.getTime();
                    $("#" + id).attr("src", path);
                });
            });
            $(".rotaterightbutton").click(function (e) {
                var id = $(e.target).attr("data-imageid");
                CommonFunctions.Functions.DoAjaxUpdatePath('../REST/api/v1/I_FotoAdmin/Pic/' + id + '/rotateright', function () {
                    var d = new Date();
                    var id = e.target.getAttribute("data-imageID");
                    var path = $("#" + id).attr("src") + "?" + d.getTime();
                    $("#" + id).attr("src", path);
                });
            });
            $(".deleteButton").click(function (e) {
                var id = $(e.target).attr("data-imageid");
                CommonFunctions.Functions.DoAjaxDeletePath('../REST/api/v1/I_FotoAdmin/Pic/' + id, function () {
                    $("#ImgField" + id).remove();
                });
            });
        };
        FotoAdminManager.addUploadEventListeners = function () {
            $("#checkboxExistingGallery").click(function () {
                if ($("#cmbExistingGallery").is(':enabled')) {
                    $("#cmbExistingGallery").prop("disabled", true);
                    $("#inputNewDate").prop("disabled", false);
                    $("#inputNewGallery").prop("disabled", false);
                    $("#cmbCountry").prop("disabled", false);
                    $("#inputTourPart").prop("disabled", false);
                }
                else {
                    $("#cmbExistingGallery").prop("disabled", false);
                    $("#inputNewDate").prop("disabled", true);
                    $("#inputNewGallery").prop("disabled", true);
                    $("#cmbCountry").prop("disabled", true);
                    $("#inputTourPart").prop("disabled", true);
                }
            });
            $(".rotateleftbutton").click(function (e) {
                var file = e.target.getAttribute("data-file");
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/RotateLeftUploadFoto/' + file, function (data) {
                    var d = new Date();
                    var id = e.target.getAttribute("data-imageID");
                    var path = $("#" + id).attr("src") + "?" + d.getTime();
                    $("#" + id).attr("src", path);
                });
            });
            $(".deleteButton").click(function (e) {
                var id = e.target.getAttribute("data-imageID");
                var file = e.target.getAttribute("data-file");
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/DeleteUploadFoto/' + file, function (data) {
                    $("#ImgField" + id).remove();
                });
            });
            $(".rotaterightbutton").click(function (e) {
                var file = e.target.getAttribute("data-file");
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/RotateRightUploadFoto/' + file, function (data) {
                    var d = new Date();
                    var id = e.target.getAttribute("data-imageID");
                    var path = $("#" + id).attr("src") + "?" + d.getTime();
                    $("#" + id).attr("src", path);
                });
            });
            $("#uploadStartButton").click(function () {
                var data = $('#FotoUploadForm').serialize();
                CommonFunctions.Functions.DoAjaxPost('../REST/api/v1/I_FotoAdmin/AddFotos', data, function (response) {
                    $("#LogWindow").html(response);
                    alert("Upload beendet");
                    $("#btnUpload").click();
                });
            });
        };
        FotoAdminManager.addAdminViewEvents = function () {
            $("#editEventTitleBtn").click(function () {
                $("#title").addClass("w3-hide");
                $("#editTitleForm").removeClass("w3-hide");
            });
            $("#deleteEventBtn").click(function () {
                CommonFunctions.Functions.showYesNoMsgBox("Event löschen?", "Wollen Sie den ausgewählten Event wirklich löschen?", function () {
                    CommonFunctions.Functions.DoAjaxDeletePath('../REST/api/v1/I_FotoAdmin/Event/' + $("#eventID").html(), function () {
                        $("#btnAdmin").click();
                    });
                });
            });
            $("#renameTitleSaveButton").click(function () {
                $("#title").removeClass("w3-hide");
                $("#editTitleForm").addClass("w3-hide");
                CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_FotoAdmin/UpdateEventTitle/' + $("#eventID").html() + '/' + $("#newTitleInputForm").val(), function (response) {
                    $("#title").html(response.newtitle);
                    $("#newTitleInputForm").val(response.newtitle);
                    $(".eventComboChilds[data-event-ID=" + $("#eventID").html() + "]").html(response.newtitle);
                });
            });
            $("#renameTitleCancelButton").click(function () {
                $("#title").removeClass("w3-hide");
                $("#editTitleForm").addClass("w3-hide");
            });
        };
        return FotoAdminManager;
    }());
    FotoAdmin.FotoAdminManager = FotoAdminManager;
})(FotoAdmin || (FotoAdmin = {}));
