var CommonFunctions;
(function (CommonFunctions) {
    var Functions = (function () {
        function Functions() {
        }
        Functions.ToggleAccordion = function (id) {
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
        Functions.ComboboxClick = function (combobox) {
            if ($(combobox).hasClass("w3-show")) {
                $(combobox).removeClass("w3-show");
            }
            else {
                $(combobox).addClass("w3-show");
            }
        };
        Functions.DoAjaxPost = function (uri, data, action) {
            $.post(uri, data, function (response) { action(response); });
        };
        Functions.DoAjaxGet = function (uri, action) {
            $.ajax({
                type: 'GET',
                url: uri,
                success: function (data) {
                    action(data);
                }
            });
        };
        Functions.DoAjaxUpdatePath = function (uri, action) {
            $.ajax({
                type: 'UPDATE',
                url: uri,
                success: function (data) {
                    action(data);
                }
            });
        };
        Functions.DoAjaxDeletePath = function (uri, action) {
            $.ajax({
                type: 'DELETE',
                url: uri,
                success: function (data) {
                    action(data);
                }
            });
        };
        Functions.showYesNoMsgBox = function (title, msg, yesFunction) {
            $("#yesNoMsgBoxTitle").html(title);
            $("#yesNoMsgBoxMsg").html(msg);
            $("#yesNoMsgBoxYesBtn").unbind("click");
            $("#yesNoMsgBoxYesBtn").click(function () {
                yesFunction();
                document.getElementById('yesNoMsgBox').style.display = 'none';
            });
            $("#yesNoMsgBoxNoBtn").click(function () {
                document.getElementById('yesNoMsgBox').style.display = 'none';
            });
            document.getElementById('yesNoMsgBox').style.display = 'block';
        };
        return Functions;
    }());
    CommonFunctions.Functions = Functions;
})(CommonFunctions || (CommonFunctions = {}));
