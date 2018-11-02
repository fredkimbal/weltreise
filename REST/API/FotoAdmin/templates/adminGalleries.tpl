{* 
Template für Gallerie Admin 
Version 1.0.0.2 - 
02.05.2017/na - neu erstellt
*}

<h3>Gallerien verwalten</h3>

<div class="w3-row">
    <div class="w3-dropdown-click">
        <button id="yearDropDownBtn" class="w3-btn buttoncolor button-hover"><i class="fa fa-caret-down"/> {$years[0][0]}</button>
        <div id="yearDropDownContent" class="w3-dropdown-content w3-bar-block w3-border">
            {foreach from=$years item=year}
                <a class="yearComboChilds w3-bar-item w3-button w3-hover-light-grey" href="#">{$year[0]}</a>
            {/foreach}
        </div>
    </div> 
    <div class="w3-dropdown-click">
        <button id="eventDropDownBtn" class="w3-btn buttoncolor button-hover"><i class="fa fa-caret-down"/> Event</button>
        <div id="eventDropDownContent" class="w3-dropdown-content w3-bar-block w3-border">
            {foreach from=$events item=event}
                <a class="eventComboChilds w3-bar-item w3-button w3-hover-light-grey" data-event-ID="{$event['EVENT_KEY']}" href="#">
                    {$event['EVENT_BEZ']}
                </a>
            {/foreach}
        </div>
    </div> 
</div>
<div id="galleryView" class="w3-row"></div>
<div id="galleryPictures" class="w3-row"></div>
