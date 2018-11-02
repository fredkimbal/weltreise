var Reports;
(function (Reports) {
    var ReportsManager = (function () {
        function ReportsManager() {
        }
        ReportsManager.Show = function (tourpart) {
            ReportsManager.tourpart = tourpart;
            $.get("REST/api/v1/Reports/Show/" + tourpart, function (d) {
                $("#maincontentArea").html(d);
                ReportsManager.addEventListener();
                $.get("REST/api/v1/Reports/GetLastReportID/" + ReportsManager.tourpart, function (id) {
                    ReportsManager.loadReports(id);
                });
            });
        };
        ReportsManager.ShowReport = function (tourpart, reportID) {
            ReportsManager.tourpart = tourpart;
            $.get("REST/api/v1/Reports/Show/" + tourpart + "/" + reportID, function (d) {
                $("#maincontentArea").html(d);
                ReportsManager.addEventListener();
                ReportsManager.loadReports(reportID);
            });
        };
        ReportsManager.addEventListener = function () {
            $("#gotofirstreport").click(function () {
                $.get("REST/api/v1/Reports/ShowFirstReport/" + ReportsManager.tourpart, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
                $.get("REST/api/v1/Reports/GetFirstReportID/" + ReportsManager.tourpart, function (id) {
                    ReportsManager.loadReports(id);
                });
            });
            $("#gotonewestreport").click(function () {
                $.get("REST/api/v1/Reports/ShowLastReport/" + ReportsManager.tourpart, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
                $.get("REST/api/v1/Reports/GetLastReportID/" + ReportsManager.tourpart, function (id) {
                    ReportsManager.loadReports(id);
                });
            });
            $("#gotopreviewreport").click(function (e) {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/" + id + "/" + ReportsManager.tourpart, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
                ReportsManager.loadReports(id);
            });
            $("#gotonextreport").click(function (e) {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/" + id + "/" + ReportsManager.tourpart, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
                ReportsManager.loadReports(id);
            });
            $("#commentAccordionBtn").click(function () {
                Common.Functions.toggleAccordion("commentAccordion");
            });
            $("#createCommentCloseBtn").click(function () {
                Common.Functions.toggleAccordion("createCommentAccordion");
            });
            $("#createCommentAccordionBtn").click(function () {
                Common.Functions.toggleAccordion("createCommentAccordion");
                $("#commentIDInput").val("");
            });
            $("#submitMessageButton").click(function () {
                $.post("REST/api/v1/Comment/ByReport", $("#createCommentForm").serialize(), function (data) {
                    alert(data);
                    if (!data.startsWith("Fehler")) {
                        $("#nameInput").val("");
                        $("#mailInput").val("");
                        $("#messageInput").val("");
                        ReportsManager.loadReports($("#reportID").val());
                    }
                });
            });
        };
        ReportsManager.loadReports = function (id) {
            $.get("REST/api/v1/Comment/ByReport/" + id + "/count", function (c) {
                $("#commentCount").html(c);
                if (c > 0) {
                    $.get("REST/api/v1/Comment/ByReport/" + id, function (d) {
                        $("#commentAccordion").html(d);
                        $(".answersAccordionBtn").click(function (e) {
                            var id = e.target.getAttribute("data-commentid");
                            Common.Functions.toggleAccordion("answersAccordion_" + id);
                        });
                        $(".answerButton").click(function (e) {
                            $("#commentIDInput").val(e.target.getAttribute("data-CommentID"));
                            Common.Functions.toggleAccordion("createCommentAccordion");
                        });
                    });
                }
                GalleryFunctions.GalleryManager.InitPhotoSwipeFromDOM('.my-gallery');
                $.ajax({
                    type: 'GET',
                    url: 'REST/api/v1/Fotos/GetPhotoSwipeForm',
                    success: function (data) {
                        $("#photoswipeContent").html(data);
                    }
                });
            });
        };
        return ReportsManager;
    }());
    Reports.ReportsManager = ReportsManager;
})(Reports || (Reports = {}));
