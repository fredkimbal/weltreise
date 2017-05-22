var Navigation;
(function (Navigation) {
    var NavigationManager = (function () {
        function NavigationManager() {
        }
        NavigationManager.InitializeNavigation = function () {
            $("#lnkReportsVelo").click(function () {
                Reports.ReportsManager.Show();
            });
        };
        return NavigationManager;
    }());
    Navigation.NavigationManager = NavigationManager;
})(Navigation || (Navigation = {}));
