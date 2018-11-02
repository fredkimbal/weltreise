module Navigation {
    export class NavigationManager {

        public static InitializeNavigation() {

            $(".navigationLink").click(function (e) {
                Navigation.NavigationManager.showPage(e);                
                NavigationManager.w3_close();
                NavigationManager.highlightLink(e.target.id);
            });

            $(".accordionLink").click((e) => {
                Common.Functions.toggleAccordion(e.target.getAttribute("data-accordion-name"));
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
        }

        private static w3_open() {
            document.getElementById("mySidenav").style.display = "block";
            document.getElementById("myOverlay").style.display = "block";
        }
        private static w3_close() {
            document.getElementById("mySidenav").style.display = "none";
            document.getElementById("myOverlay").style.display = "none";
        }

        private static highlightLink(id) {
            $(".navigationLink").removeClass("w3-light-grey");
            $("#" + id).addClass("w3-light-grey");
        }

        private static showPage(clickedElement: JQueryEventObject) {
            var sitedata = clickedElement.target.getAttribute("data-site").split("_");
            if (sitedata[0] === "start") {
                Startseite.StartseiteManager.Show();
            }
            if (sitedata[0] === "portrait") {
                Portrait.PortraitManager.ShowPortrait(sitedata[1]);
            }
            if (sitedata[0] === "report") {
                Reports.ReportsManager.Show(parseInt(sitedata[1]));
            }
            if (sitedata[0] === "pics") {
                Fotos.FotosManager.ShowGallery(sitedata[1], null);
            }
            if (sitedata[0] === "map") {
                Map.MapManager.ShowMap(sitedata[1]);
            }
        }

    }
}