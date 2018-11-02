module Portrait {
    export class PortraitManager {

        public static ShowPortrait(name: string) {
            var uri: string;            
            if (name === "luana") {
                uri = "REST/api/v1/Portrait/Luana";
            }
            else {
                uri = "REST/api/v1/Portrait/Andy";
            }
            
            $.ajax({
                type: 'GET',
                url: uri,
                success: function (data) {
                    $("#maincontentArea").html(data);
                }
            });
        }

    }
}
