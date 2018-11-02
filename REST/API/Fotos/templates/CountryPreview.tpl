<div class="w3-row">
    {foreach from=$galleries item=gallery}
        <div class="w3-quarter w3-card-4 w3-padding-16 w3-center w3-margin-right w3-margin-bottom secondaryColor w3-border-0 gallerylink" data-ID="{$gallery[0]}" style="cursor:pointer" ><i  class="fa fa-camera" aria-hidden="true"></i>
            {$gallery[1]}
        </div>
    {/foreach}
</div>