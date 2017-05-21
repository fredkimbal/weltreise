var Reports;
(function (Reports) {
    var ReportsManager = (function () {
        function ReportsManager() {
        }
        ReportsManager.Show = function () {
            $.get("REST/api/v1/Reports/Show", function (d) {
                $("#maincontentArea").html(d);
            });
        };
        return ReportsManager;
    })();
    Reports.ReportsManager = ReportsManager;
})(Reports || (Reports = {}));
//# sourceMappingURL=Reports.js.map