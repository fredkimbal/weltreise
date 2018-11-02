var Common;
(function (Common) {
    var Functions = (function () {
        function Functions() {
        }
        Functions.toggleAccordion = function (id) {
            var x = document.getElementById(id);
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
                x.previousElementSibling.className += " w3-theme";
            }
            else {
                x.className = x.className.replace("w3-show", "");
                x.previousElementSibling.className =
                    x.previousElementSibling.className.replace(" w3-theme", "");
            }
        };
        return Functions;
    }());
    Common.Functions = Functions;
})(Common || (Common = {}));
