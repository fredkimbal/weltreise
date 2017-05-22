module Navigation {
    export class NavigationManager {

        public static InitializeNavigation() {

            $("#lnkReportsVelo").click(() => {
                Reports.ReportsManager.Show();
            });
        }

    }
}