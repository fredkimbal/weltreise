var Reports;
(function (Reports) {
    var ReportsManager = (function () {
        function ReportsManager() {
        }
        ReportsManager.Show = function () {
            $.get("REST/api/v1/Reports/Show", function (d) {
                $("#maincontentArea").html(d);
                ReportsManager.addEventListener();
            });
        };
        ReportsManager.addEventListener = function () {
            $("#gotofirstreport").click(function () {
                $.get("REST/api/v1/Reports/ShowFirstReport", function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
                ;
            });
            $("#gotonewestreport").click(function () {
                $.get("REST/api/v1/Reports/ShowLastReport", function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
            });
            $("#gotopreviewreport").click(function (e) {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/" + id, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
            });
            $("#gotonextreport").click(function (e) {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/" + id, function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
            });
        };
        return ReportsManager;
    }());
    Reports.ReportsManager = ReportsManager;
})(Reports || (Reports = {}));
