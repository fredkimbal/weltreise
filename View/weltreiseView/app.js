window.onload = function () {
    Navigation.NavigationManager.InitializeNavigation();
    if (window.location.search.substring(1).length > 0) {
        var params = window.location.search.substring(1).split("&");
        if (params[0] === "verify") {
            Verify.VerifyManager.Verify(params[1]);
        }
        if (params[0] === "unsubscribe") {
            Verify.VerifyManager.Unsubscribe(params[1]);
        }
        if (params[0] === "report") {
            Reports.ReportsManager.ShowReport(Number(params[1]), Number(params[2]));
        }
    }
    else {
        Startseite.StartseiteManager.Show();
    }
};
