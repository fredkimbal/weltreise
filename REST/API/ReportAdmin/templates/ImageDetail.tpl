<div class="w3-margin">
    <form id="imageDetailForm">
        <div class="w3-row">
            <img src="{$path}/large/{$image['PicPath']}" style ="width:100%;max-width:500px; max-height:500px;"/>
        </div>
        <div class="w3-row">
            <input class="w3-input w3-border" name="caption"/>
        </div>
        <input type="hidden" name="imageID" value="{$image['ID']}"/>
        <input type="hidden" name="reportID" value="{$reportID}"/>
        <button  type="button" class="w3-button" id="SaveImageInReportBtn">Speichern</button>
    </form>
</div>