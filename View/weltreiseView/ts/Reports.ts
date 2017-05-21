module Reports {
    export class ReportsManager {
        public static Show() {
            $.get("REST/api/v1/Reports/Show", d => {
                $("#maincontentArea").html(d);
            });
        }
    }
}