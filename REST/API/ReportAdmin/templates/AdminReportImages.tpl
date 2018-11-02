<div class='w3-container'>
    <div class="w3-row w3-margin-top">
        <span>Bericht auswählen: </span>
        <div class="w3-dropdown-click">

            <button class="w3-btn buttoncolor button-hover" id="reportDropdownBtn">Bericht Auswahl</button>
            <div id='reportDropdownContent' class="w3-dropdown-content w3-bar-block w3-border">
                {foreach from=$reports item=report}
                    <a class="reportComboChild w3-bar-item w3-button w3-hover-light-grey" data-ReportID='{$report['id']}' href="#">{$report['title']}</a>
                {/foreach}

            </div>
        </div> 
    </div>
    <div id='reportDetails' class="w3-row">

    </div>
    <div class="w3-row  w3-margin-top">
        <div id="imagedetails" class="w3-half">Detail Anzeige und Legende</div>
        <div class="w3-half">
            <div class="w3-row">
                <span>Gallerie auswählen: </span>
                <div class="w3-dropdown-click">                    
                    <button class="w3-btn buttoncolor button-hover" id="galleryDropdownBtn">Gallerie Auswahl</button>
                    <div id='galleryDropdownContent' class="w3-dropdown-content w3-bar-block w3-border">
                        {foreach from=$galleries item=gallery}
                            <a class="galleryComboChild w3-bar-item w3-button w3-hover-light-grey" 
                               data-galleryid='{$gallery['id']}' href="#">{$gallery['title']}</a>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div id='fotoSelection'>
                Foto Auswahl
            </div>
        </div>
    </div>
</div>