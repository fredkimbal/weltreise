{*Template für die Vorschau des Events*}

<h3>{$eventTitle}</h3><a class="eventPreviewBack" href="#" id="{$country}">zurück zur Übersicht</a>

<div class="my-gallery" class="w3-row" itemscope itemtype="http://schema.org/ImageGallery">
    {foreach from=$pics item=pic}        
        <figure class="galleryEvent" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
            <a href="{$pic['path']}" itemprop="contentUrl" data-size="{$pic['width']}x{$pic['height']}">
                <img src="{$pic['path']}" itemprop="thumbnail" alt="Image description" />
            </a>
            <figcaption itemprop="caption description"></figcaption>
        </figure>
    {/foreach}
</div>

