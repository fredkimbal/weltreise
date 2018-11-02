{* 
Template für das Anzeigen der einzelnen Fotos
Version 1.0.0.2 - 
31.05.2017/na - neu erstellt
*}

<div class="w3-row" id="previewRow">
    <h4>Vorschau</h4>
    <div>
        {foreach  from=$pictures item=pic}  
            <div class="w3-quarter" id="ImgField{$pic[0]}">
                <div class="w3-center" style="height:150px">
                    <img id='{$pic[0]}' class="w3-center w3-large w3-margin" src="{$galleryPath}/{$pic[1]}" style="max-height : 150px">  
                </div>            
                <div class="w3-center">
                    <button class="rotateleftbutton w3-btn buttoncolor button-hover" data-file="{$pic[1]}" data-imageID = "{$pic[0]}">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                    </button>
                    <button class="deleteButton w3-btn buttoncolor button-hover" data-file="{$pic[1]}" data-imageID = "{$pic[0]}">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                    <button class="rotaterightbutton w3-btn buttoncolor button-hover" data-file="{$pic[1]}" data-imageID = "{$pic[0]}">
                        <i class="fa fa-repeat" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        {/foreach}
    </div>
</div>