{* 
Template für das Administrieren eines Events
Version 1.0.0.2 - 
31.05.2017/na - neu erstellt
*}

<div class="w3-row w3-margin-top">
    <button id="deleteEventBtn" class="w3-btn buttoncolor button-hover">
        <i class="fa fa-trash-o"></i>
    </button>
    <button id="editEventTitleBtn" class="w3-btn buttoncolor button-hover">
        <i class="fa fa-pencil"></i>
    </button>
    <span id="title" class="w3-margin w3-large">{$title}</span>
    <span id="editTitleForm" class="w3-hide">
        <input id="newTitleInputForm" value="{$title}" />
        <button id="renameTitleSaveButton" class="w3-btn buttoncolor button-hover">Speichern</button>
        <button id="renameTitleCancelButton" class="w3-btn buttoncolor button-hover">Abbrechen</button>
    </span>
</div>
<div id="eventID" class="w3-hide">{$eventID}</div>
