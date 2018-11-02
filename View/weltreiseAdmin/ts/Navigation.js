var Navigation;
(function (Navigation) {
    var NavigationManager = (function () {
        function NavigationManager() {
        }
        NavigationManager.InitializeNavigation = function () {
            $("#lnkGpxImport").click(function () {
                GPX.GpxManager.show();
                NavigationManager.w3_close();
                NavigationManager.highlightLink("lnkGpxImport");
            });
            $("#lnkSendNotification").click(function () {
                onTour_Notification.NotificationManager.Show();
                NavigationManager.w3_close();
                NavigationManager.highlightLink("lnkSendNotification");
            });
            $("#lnkAdminFotos").click(function () {
                FotoAdmin.FotoAdminManager.showPage();
                NavigationManager.w3_close();
                NavigationManager.highlightLink("lnkAdminFotos");
            });
            $("#lnkAdminReports").click(function () {
                ReportAdmin.ReportAdminManager.showPage();
                NavigationManager.w3_close();
                NavigationManager.highlightLink("lnkAdminReports");
            });
            $("#closeLink").click(function () {
                NavigationManager.w3_close();
            });
            $("#myOverlay").click(function () {
                NavigationManager.w3_close();
            });
            $("#hamburgerButton").click(function () {
                NavigationManager.w3_open();
            });
            window.onscroll = function () {
                if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
                    document.getElementById("myTop").classList.add("w3-card-4");
                    document.getElementById("myIntro").classList.add("w3-show-inline-block");
                    document.getElementById("myTop").classList.add("w3-orange");
                    document.getElementById("myTop").classList.remove("w3-transparent");
                }
                else {
                    document.getElementById("myIntro").classList.remove("w3-show-inline-block");
                    document.getElementById("myTop").classList.remove("w3-card-4");
                    document.getElementById("myTop").classList.remove("w3-orange");
                    document.getElementById("myTop").classList.add("w3-transparent");
                }
            };
        };
        NavigationManager.w3_open = function () {
            document.getElementById("mySidenav").style.display = "block";
            document.getElementById("myOverlay").style.display = "block";
        };
        NavigationManager.w3_close = function () {
            document.getElementById("mySidenav").style.display = "none";
            document.getElementById("myOverlay").style.display = "none";
        };
        NavigationManager.highlightLink = function (id) {
            $(".navigationLink").removeClass("w3-light-grey");
            $("#" + id).addClass("w3-light-grey");
        };
        return NavigationManager;
    }());
    Navigation.NavigationManager = NavigationManager;
})(Navigation || (Navigation = {}));
