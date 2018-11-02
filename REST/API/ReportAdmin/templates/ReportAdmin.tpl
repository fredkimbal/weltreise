{* 
    Template für Foto Admin 
    Version 1.0.0.2 - 
    02.05.2017/na - neu erstellt
*}
<h1>Report Admin</h1>
<div class="w3-bar w3-border-bottom">
    <!--<button id="btnText" data-targetsite="text" class="tablink w3-bar-item w3-btn buttoncolor button-hover">Text</button>-->
    
    <button id="btnPics" data-targetsite="fotos" class="tablink w3-bar-item w3-btn buttoncolor button-hover">Fotos</button>    
</div>
<div>
    {include file='AdminReportImages.tpl'}
</div>  
<div id='currentReportID' class='w3-hide'></div>