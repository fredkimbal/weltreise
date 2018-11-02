<div>
    <p>{$date}-{$title}</p>
    <div class="w3-row">
        <p>
            {foreach from=$fotos item=foto}
                {$foto['PicPath']} <i class="fa fa-trash" aria-hidden="true"></i>
<br/>
            {/foreach}
        </p>
    </div>
</div>