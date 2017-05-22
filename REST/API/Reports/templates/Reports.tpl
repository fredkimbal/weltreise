{* Template für Gallerie *}
<h1>Berichte</h1>

<div class='w3-row w3-small w3-border-bottom'>{$date} - {$data['CreationUser']}</div>
<div id="reportArea">
    {$data['ReportText']}
</div>
<div class="w3-center">
    <div class="w3-bar">
        {if isset($preview)}
        <button id="gotofirstreport" class="w3-btn buttoncolor">
            <i class="fa fa-angle-double-left" aria-hidden="true"></i>
        </button>
        <button id="gotopreviewreport" class="w3-btn buttoncolor" data-ID="{$preview['ID']}">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
        </button>
        {/if}
        {if isset($next)}
        <button id="gotonextreport" class="w3-btn buttoncolor" data-ID="{$next['ID']}">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </button>
        
        <button id="gotonewestreport" class="w3-btn buttoncolor">
            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
        </button>
        {/if}
    </div>
</div> 