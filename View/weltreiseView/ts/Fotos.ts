module Fotos {
    export class FotosManager {
        public static ShowVelo(id) {
            var uri;
            if (id == null) {
                uri = "REST/api/v1/Fotos/ShowVelo";
            }
            else {
                uri = "REST/api/v1/Fotos/ShowVelo/"+id;
            }

            $.get(uri, d => {
                $("#maincontentArea").html(d);
                $(".gallerylink").click(function (e) {
                    FotosManager.loadEventPreview(e.target.getAttribute("data-ID"), 1);
                });

                $("#countryComboButton").click(function () {
                    Common.Functions.toggleAccordion("CountryCombo");
                });

                $(".countryComboChilds").click(function (e) {
                    var countryID = e.target.getAttribute("data-ID");
                    $.get("REST/api/v1/Fotos/Country/preview/" + countryID+"/1", d => {
                        $.get("REST/api/v1/Fotos/Country/name/" + countryID, d => {
                            $("#cmbCountryCaption").html(d);
                            $(".gallerylink").click(function (e) {
                                FotosManager.loadEventPreview(e.target.getAttribute("data-ID"),1);
                            });
                        });
                        $("#CountryPreview").html(d);
                        Common.Functions.toggleAccordion("CountryCombo");

                    });
                });

            });
        }

        public static ShowBenelux(id) {
            var uri;
            if (id == null) {
                uri = "REST/api/v1/Fotos/ShowBenelux";
            }
            else {
                uri = "REST/api/v1/Fotos/ShowBenelux/" + id;
            }

            $.get(uri, d => {
                $("#maincontentArea").html(d);
                $(".gallerylink").click(function (e) {
                    FotosManager.loadEventPreview(e.target.getAttribute("data-ID"), 2);
                });

                $("#countryComboButton").click(function () {
                    Common.Functions.toggleAccordion("CountryCombo");
                });

                $(".countryComboChilds").click(function (e) {
                    var countryID = e.target.getAttribute("data-ID");
                    $.get("REST/api/v1/Fotos/Country/preview/" + countryID+"/2", d => {
                        $.get("REST/api/v1/Fotos/Country/name/" + countryID, d => {
                            $("#cmbCountryCaption").html(d);
                            $(".gallerylink").click(function (e) {
                                FotosManager.loadEventPreview(e.target.getAttribute("data-ID"),2);
                            });
                        });
                        $("#CountryPreview").html(d);
                        Common.Functions.toggleAccordion("CountryCombo");

                    });
                });

            });
        }

        public static ShowGallery(tourpart, id) {
            if (tourpart === "1") {
                Fotos.FotosManager.ShowVelo(id);
            }
            if (tourpart === "2") {
                Fotos.FotosManager.ShowBenelux(id);
            }
            if (tourpart === "3") {
                FotosManager.Show(tourpart, id);
            }
        }

        public static Show(tourpart, id) {
            var uri;
            if (id == null) {
                uri = "REST/api/v1/Fotos/Show/"+tourpart;
            }
            else {
                uri = "REST/api/v1/Fotos/Show/" + tourpart+"/" + id;
            }

            $.get(uri, d => {
                $("#maincontentArea").html(d);
                $(".gallerylink").click(function (e) {
                    FotosManager.loadEventPreview(e.target.getAttribute("data-ID"), 2);
                });

                $("#countryComboButton").click(function () {
                    Common.Functions.toggleAccordion("CountryCombo");
                });

                $(".countryComboChilds").click(function (e) {
                    var countryID = e.target.getAttribute("data-ID");
                    $.get("REST/api/v1/Fotos/Country/preview/" + countryID + "/2", d => {
                        $.get("REST/api/v1/Fotos/Country/name/" + countryID, d => {
                            $("#cmbCountryCaption").html(d);
                            $(".gallerylink").click(function (e) {
                                FotosManager.loadEventPreview(e.target.getAttribute("data-ID"), 2);
                            });
                        });
                        $("#CountryPreview").html(d);
                        Common.Functions.toggleAccordion("CountryCombo");

                    });
                });

            });
        }


        private static loadEventPreview(id, tourpart) {
            
            $.ajax({
                type: 'GET',
                url: 'REST/api/v1/Fotos/Pics/bygallery/' + id,
                success: function (data) {
                    $("#maincontentArea").html(data);

                    $(".eventPreviewBack").click(function (eventData) {
                        var countryID = eventData.target.id;
                        if (tourpart == 1) {
                            FotosManager.ShowVelo(countryID);
                        }
                        else {
                            FotosManager.ShowBenelux(countryID);
                        }
                    });
                    // execute above function
                    GalleryFunctions.GalleryManager.InitPhotoSwipeFromDOM('.my-gallery');

                   
                }
            });
            $.ajax({
                type: 'GET',
                url: 'REST/api/v1/Fotos/GetPhotoSwipeForm',
                success: function (data) {
                    $("#photoswipeContent").html(data);
                }
            });



        }
    }
}