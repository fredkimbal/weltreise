{* Template für Gallerie *}
<h1>Fotos</h1>

<div class="w3-dropdown-click">
    <button ID="countryComboButton" class="w3-btn  buttoncolor button-hover">Land: <span id="cmbCountryCaption">{$currentCountry}</span></button>
    <div id="CountryCombo" class="w3-dropdown-content w3-animate-left w3-card" style="position:absolute; z-index: 3">
        {foreach from=$countries item=country}
            <a class="countryComboChilds" href="#" data-ID="{$country[0]}">{$country[1]}</a>
        {/foreach}
    </div>    
</div>
    <div id="CountryPreview" class="w3-margin-top">
    {include file='CountryPreview.tpl'}
</div>