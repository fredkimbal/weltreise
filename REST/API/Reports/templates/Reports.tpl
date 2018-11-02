{* Template für Gallerie *}
<div>
    <h1>Berichte</h1>
    
    <div class='w3-row w3-small w3-border-bottom'>{$date} - {$data['CreationUser']}</div>
    <h4>{$data['ReportTitle']}</h4>
    <div id="reportArea">
        {$data['ReportText']}
    </div>
    {if isset($pics)}
        <div class="w3-row my-gallery">
            {foreach from=$pics item=pic}        
                <figure class="galleryEvnt" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                    <a href="{$pic['path']}" itemprop="contentUrl" data-size="{$pic['width']}x{$pic['height']}">
                        <img src="{$pic['path']}" itemprop="thumbnail" alt="Image description" />
                    </a>
                    <figcaption itemprop="caption description">{$pic['caption']}</figcaption>
                </figure>
            {/foreach}
        </div>
    {/if}
    <p>&nbsp;</p>
    <button id="commentAccordionBtn" class="w3-btn w3-block w3-left-align w3-margin-top secondaryColor">Kommentare (<span id="commentCount">0</span>) <i class="fa fa-caret-down"></i></button>
    <div id="commentAccordion" class="w3-container w3-hide">
        <p>Bisher noch keine Kommentare</p>
    </div>

    <button id="createCommentAccordionBtn" class="w3-button w3-left-align w3-margin-top secondaryColor">Kommentar erstellen</button>


    <div id="createCommentAccordion" class="w3-modal">
        <div class="w3-modal-content">
            <div class="w3-container">
                <div id="createCommentCloseBtn" class="w3-button w3-display-topright">&times;</div>

                <form id="createCommentForm" class="w3-container w3-margin-top">
                    <p>
                        <label class="w3-text-grey">Name</label>
                        <input id="nameInput" class="w3-input w3-border" name="name" required="" type="text"/>
                    </p>
                    <p>
                        <label class="w3-text-grey">E-Mail</label>
                        <input id="mailInput" class="w3-input w3-border" required="" name="mail"  type="text"/>
                    </p>
                    <p>
                        <label class="w3-text-grey">Nachricht</label>
                        <textarea id="messageInput" class="w3-input w3-border" style="resize:none" name="message"  ></textarea>
                    </p>
                    <p>
                        <img id="captcha" src="REST/Libs/securimage/securimage_show.php?{$time}" alt="CAPTCHA Image"/>                
                    </p>
                    <p>
                        <input type="text" name="captcha_code" size="10" maxlength="6" />
                        <a href="#" onclick="document.getElementById('captcha').src = 'REST/Libs/securimage/securimage_show.php?' + Math.random();
                                return false">Anderes Bild</a></p>
                    <p>
                        <input id="reportID" name="reportID" value="{$data['ID']}" type="hidden"/>
                        <input id="commentIDInput" name="commentID" value="{$data['isAnswer']}" type="hidden"/>
                        <button id="submitMessageButton" type="button" class="w3-btn buttoncolor">Senden</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <p>&nbsp;</p>

    
    <div class="w3-bottom reportNavigation w3-white">
        <div class="w3-bar w3-white  w3-padding-small">

            {if isset($preview)}
                <div class="w3-left reportNavigationLeftIntent">
                    <button id="gotofirstreport" class="w3-btn buttoncolor">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <button id="gotopreviewreport" class="w3-btn buttoncolor" data-ID="{$preview['ID']}">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                    </button>
                </div>
            {/if}
            {if isset($next)}
                <div class="w3-right">
                    <button id="gotonextreport" class="w3-btn buttoncolor" data-ID="{$next['ID']}">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </button>

                    <button id="gotonewestreport" class="w3-btn buttoncolor">
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                    </button>
                </div>
            {/if}



        </div> 
    </div>
</div>