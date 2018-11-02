<div id='w3-container'>
    <div id='w3-row'>
        {foreach from=$images item=image}
            <div class='w3-half w3-margin-top'>
                <img src="{$path}/large/{$image[0]}" style ="max-width:200px; max-height:200px;" data-img-id="{$image['ID']}" class="imagepreviewpic"/>
            </div>
        {/foreach}
    </div>
</div>