module ReportAdmin {
    export class ReportAdminManager {
        public static showPage() {

            CommonFunctions.Functions.DoAjaxGet('../REST/api/v1/I_ReportAdmin/Show',
                function (data) {
                    $("#maincontentArea").html(data);
                    $("#reportDropdownBtn").click(function () {
                        CommonFunctions.Functions.ComboboxClick("#reportDropdownContent");
                    });
                    $("#galleryDropdownBtn").click(function () {
                        CommonFunctions.Functions.ComboboxClick("#galleryDropdownContent");
                    });
                    $(".reportComboChild").click(function (e) {
                        var id = e.target.getAttribute("data-ReportID");
                        $("#currentReportID").html(id);
                        CommonFunctions.Functions.
                            DoAjaxGet('../REST/api/v1/I_ReportAdmin/GetReportDetail/'
                            + id,
                            function (data) {
                                $("#reportDetails").html(data);
                            });
                        CommonFunctions.Functions.ComboboxClick("#reportDropdownContent");
                    });
                    // Event Handler für die Gallerie Auswahl
                    $(".galleryComboChild").click(function (e) {
                        CommonFunctions.Functions.
                            DoAjaxGet('../REST/api/v1/I_ReportAdmin/GetGalleryPreview/'
                            + e.target.getAttribute("data-galleryid"),
                            function (data) {
                                $("#fotoSelection").html(data);
                                $(".imagepreviewpic").click(function (e) {
                                    var id = e.target.getAttribute("data-img-id");
                                    CommonFunctions.Functions.DoAjaxGet("../REST/api/v1/I_ReportAdmin/Image/detail/" + id + "/" + $("#currentReportID").html(), function (data) {
                                        $("#imagedetails").html(data);
                                        $("#SaveImageInReportBtn").click(function () {
                                            CommonFunctions.Functions.DoAjaxPost("../REST/api/v1/I_ReportAdmin/Image/save",
                                                $("#imageDetailForm").serialize(),
                                                function () {
                                                    alert("Bild gespeichert");
                                                }
                                            );
                                        });
                                    });
                                });
                            });
                    });
                    CommonFunctions.Functions.ComboboxClick("#galleryDropdownContent");
                });
        
    }
}
}