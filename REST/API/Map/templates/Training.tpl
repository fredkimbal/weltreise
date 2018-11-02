<h1>Training</h1>
<div id="mapid"></div>
<h3>Trainingsrouten</h3>
<div id="w3-container">
    <table class="w3-table w3-striped">
        <tr class='buttoncolor'>
            <td class="w3-small">Datum</td>
            <td class="w3-small">Distanz</td>
            <td class="w3-small">Steigung</td>
            <td class="w3-small">Höhenprofil</td>
        </tr>

        {foreach from=$tracks item=track}
            <tr class='trackLink' data-trackID = '{$track['ID']}' style="cursor:pointer">
                <td class="w3-small">{$track['TrackDate']}</td>
                <td class="w3-small">{$track['Distance']}</td>
                <td class="w3-small">{$track['Ascent']}</td>
                <td><button data-trackID="{$track['ID']}" class="w3-button w3-border w3-border-gray w3-padding-small elevationChartButton">
                        <i class="fa fa-line-chart w3-small" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        {/foreach}
    </table>

</div>

<div id="elevationForm" class="w3-modal" style="z-index: 2000;">
    <div class="w3-modal-content">
        <div class="w3-container">
            <span id="closeButton" class="w3-button w3-display-topright">&times;</span>
            <h3>H&ouml;henprofil</h3>
            <p>
                <img width="100%" id="elevationImage" src=""/>
            </p>            
        </div>
    </div>
</div>