module Reports {
    export class ReportsManager {
        public static Show() {
            $.get("REST/api/v1/Reports/Show", d => {
                $("#maincontentArea").html(d);
                ReportsManager.addEventListener();

            });
        }

        private static addEventListener() {
            $("#gotofirstreport").click(() => {
                $.get("REST/api/v1/Reports/ShowFirstReport", function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener()
                });;
            });

            $("#gotonewestreport").click(() => {
                $.get("REST/api/v1/Reports/ShowLastReport", function (d) {
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
            });

            $("#gotopreviewreport").click((e) => {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/"+id, function (d) {
                    
                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
            });

            $("#gotonextreport").click((e) => {
                var id = $(e.target).attr("data-ID");
                $.get("REST/api/v1/Reports/ShowReportByID/" + id, function (d) {

                    $("#maincontentArea").html(d);
                    ReportsManager.addEventListener();
                });
            });
        }
    }
}