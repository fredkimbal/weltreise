{* Template für Gallerie *}
<h1>Fotos</h1>

<div class="w3-dropdown-click">
    <button ID="yearComboButton" class="w3-btn  buttoncolor button-hover">Jahr: {$currentYear}</button>
    <div id="YearCombo" class="w3-dropdown-content w3-animate-left w3-card" style="position:absolute; z-index: 3">
        {foreach from=$years item=year}
            <a class="yearComboChilds" href="#">{$year[0]}</a>
        {/foreach}
    </div>    
</div>
<div id="YearPreview">
    {include file='YearPreview.tpl'}
</div>