{* 
Template für Foto Upload 
Version 1.0.0.2 - 
02.05.2017/na - neu erstellt
*}

<h3>Fotos hochladen</h3>

<form id="FotoUploadForm" class="w3-container w3-half" action="#">
    <p><input id="checkboxExistingGallery" class="w3-check" type="checkbox" name="checkboxExistingGallery">
        <label>In bestehende Gallerie einfügen</label></p>
    <p>&nbsp;</p>
    <p><label>Gallerie</label>
        <input id="inputNewGallery" class="w3-input w3-border" type="text" name="eventname">
    <p>&nbsp;</p>
    <p><label>TourPart</label>
        <input id="inputTourPart" class="w3-input w3-border" type="text" name="tourPart">
    <p>&nbsp;</p>
    <p><select id="cmbCountry" class="w3-select" name="countryID">
            <option value="" disabled selected>Land auswählen...</option>
            {foreach from=$countries item=country}        
                <option value="{$country['ID']}">{$country[1]}</option>
            {/foreach}
        </select></p>
    <p><label>Datum (YYYY-MM-DD)</label>
        <input id="inputNewDate" class="w3-input w3-border" type="text" name="eventdate"></p>
    <p>&nbsp;</p>
    <p><select id="cmbExistingGallery" class="w3-select" name="eventID" disabled>
            <option value="" disabled selected>Gallerie auswählen...</option>
            {foreach from=$events item=event}        
                <option value="{$event['ID']}">{$event['GalleryName']}</option>
            {/foreach}


        </select></p>
    <p>&nbsp;</p>
    <p>
        <button id="uploadStartButton" class="w3-btn buttoncolor button-hover" type="button">Hüüh Geiss!!</button>
    </p>
</form>

{include file='picturePreview.tpl'}

<div id="LogWindow"></div>