<h1>{$title}</h1>
<div id="mapid"></div>
<div class="w3-row w3-small">
    <div class="w3-quarter">Legende</div>
</div> 
<div class="w3-row w3-small">
    <div class="l2 w3-col m2 s6"><span class="w3-blue">&nbsp;&nbsp;&nbsp;</span> Undefiniert</div>
    <div class="l2 w3-col m2 s6"><span style="background-color: darkslateblue">&nbsp;&nbsp;&nbsp;</span> Camper</div>
    <div class="l2 w3-col m2 s6"><span style="background-color: red">&nbsp;&nbsp;&nbsp;</span> Flugzeug</div>
    <div class="l2 w3-col m2 s6"><span style="background-color: green">&nbsp;&nbsp;&nbsp;</span> zu Fuss</div>
    <div class="l2 w3-col m2 s6"><span style="background-color: goldenrod">&nbsp;&nbsp;&nbsp;</span> Fahrrad</div>
</div>
<h3>Orte</h3>
<div id="w3-container">
    <table class="w3-table w3-striped">
        <tr class='buttoncolor'>
            <td class="w3-small">Ankunft</td>
            <td class="w3-small">Ort</td>            
        </tr>

        {foreach from=$tracks item=track}
            <tr class='trackLink' data-trackID = '{$track['ID']}' style="cursor:pointer"data-lat="{$track['lat']}" data-long="{$track['long']}">
                <td class="w3-small">{$track['TrackDate']}</td>
                <td class="w3-small">{$track['Location']}</td>                
            </tr>
        {/foreach}
    </table>
</div>