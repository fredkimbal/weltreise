
module CommonFunctions {
    export class Functions {

        public static ToggleAccordion(id) {
            var x = document.getElementById(id);
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
                x.previousElementSibling.className += " w3-theme";
            } else {
                x.className = x.className.replace("w3-show", "");
                x.previousElementSibling.className =
                    x.previousElementSibling.className.replace(" w3-theme", "");
            }
        }

        public static ComboboxClick(combobox) {
            if ($(combobox).hasClass("w3-show")) {
                $(combobox).removeClass("w3-show");
            }
            else {
                $(combobox).addClass("w3-show");
            }

        }

        public static DoAjaxPost(uri, data, action) {
            $.post(uri, data, function (response) { action(response); });
        }

        public static DoAjaxGet(uri, action) {
            $.ajax({
                type: 'GET',
                url: uri,
                success: function (data) {
                    action(data);
                }
            });
        }

        public static DoAjaxUpdatePath(uri, action) {
            $.ajax({
                type: 'UPDATE',
                url: uri,
                success: function (data) {
                    action(data);
                }
            });
        }

        public static DoAjaxDeletePath(uri, action) {
            $.ajax({
                type: 'DELETE',
                url: uri,
                success: function (data) {
                    action(data);
                }
            });
        }

        public static showYesNoMsgBox(title, msg, yesFunction) {
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
        }

    }
}