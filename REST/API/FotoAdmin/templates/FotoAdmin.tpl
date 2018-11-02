{* 
    Template für Foto Admin 
    Version 1.0.0.2 - 
    02.05.2017/na - neu erstellt
*}
<h1>Foto Admin</h1>
<div class="w3-bar w3-border-bottom">
    <button id="btnUpload" data-targetsite="upload" class="tablink w3-bar-item w3-btn buttoncolor button-hover">Hochladen</button>
    
    <button id="btnAdmin" data-targetsite="admin" class="tablink w3-bar-item w3-btn buttoncolor button-hover">Verwalten</button>    
</div>
<div id="fotoadmin_content">
    {include file='uploadFotos.tpl'}
</div>