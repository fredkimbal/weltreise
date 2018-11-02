module Reports {
    export class ReportsManager {

        private static tourpart: number;

        public static Show(tourpart: number) {
            ReportsManager.tourpart = tourpart;
            $.get("REST/api/v1/Reports/Show/" + tourpart, d => {
                $("#maincontentArea").html(d);
                ReportsManager.addEventListener();
                $.get("REST/api/v1/Reports/GetLastReportID/" + ReportsManager.tourpart, function (id) {
                    ReportsManager.loadReports(id);
                });
            });
        }

        public static ShowReport(tourpart: number, reportID: number) {
            ReportsManager.tourpart = tourpart;
            $.get("REST/api/v1/Reports/Show/" + tourpart + "/" + reportID, d => {
                $("#maincontentArea").html(d);
                ReportsManager.addEventListener();
                ReportsManager.loadReports(reportID);
            });            
        }

        private static addEventListener() {
            $("#gotofirstreport").click(() => {
                $.get("REST/api/v1/Reports/ShowFirstReport/" + ReportsManager.tourpart, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener()
                });
                $.get("REST/api/v1/Reports/GetFirstReportID/" + ReportsManager.tourpart, function (id) {
                    ReportsManager.loadReports(id);
                });
            });

            $("#gotonewestreport").click(() => {
                $.get("REST/api/v1/Reports/ShowLastReport/" + ReportsManager.tourpart, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });

                $.get("REST/api/v1/Reports/GetLastReportID/" + ReportsManager.tourpart, function (id) {
                    ReportsManager.loadReports(id);
                });
            });

            $("#gotopreviewreport").click((e) => {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/" + id + "/" + ReportsManager.tourpart, function (d) {

                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
                ReportsManager.loadReports(id);
            });

            $("#gotonextreport").click((e) => {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/" + id + "/" + ReportsManager.tourpart, function (d) {

                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });

                ReportsManager.loadReports(id);
            });

            $("#commentAccordionBtn").click(() => {
                Common.Functions.toggleAccordion("commentAccordion");
            });
            $("#createCommentCloseBtn").click(() => {
                Common.Functions.toggleAccordion("createCommentAccordion");
            });
            $("#createCommentAccordionBtn").click(() => {
                Common.Functions.toggleAccordion("createCommentAccordion");
                $("#commentIDInput").val("");
            });

            $("#submitMessageButton").click(function () {
                $.post("REST/api/v1/Comment/ByReport", $("#createCommentForm").serialize(), function(data){
                    alert(data);
                    if (!data.startsWith("Fehler")) {                        
                        $("#nameInput").val("");
                        $("#mailInput").val("");
                        $("#messageInput").val("");

                        ReportsManager.loadReports($("#reportID").val());
                    }
                });                
            });

        }

        private static loadReports(id) {
            $.get("REST/api/v1/Comment/ByReport/" + id + "/count", c => {
                $("#commentCount").html(c);

                if (c > 0) {
                    $.get("REST/api/v1/Comment/ByReport/" + id, d => {
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
        }
    }
}