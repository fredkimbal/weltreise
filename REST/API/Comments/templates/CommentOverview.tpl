{* Template für Gallerie *}
<div>
    {foreach from=$comments item=comment}
        <div class="w3-container w3-margin-bottom">
            <div class='w3-row w3-small w3-border-bottom'>{$comment['date']} - {$comment['user']}</div>            
            <div>
                {$comment['CommentText']}
            </div>
            <div class='w3-row w3-small w3-border-bottom'><a href="#" class="answerButton" data-CommentID = "{$comment['CommentID']}">Antwort erstellen</a></div>            
            {if $comment['hasAnswers']}
                <div class="w3-button w3-small w3-block w3-left-align answersAccordionBtn" 
                     data-CommentID = "{$comment['CommentID']}">
                    Antworten anzeigen <i class="fa fa-caret-down"></i>
                </div>


                <div id="answersAccordion_{$comment['CommentID']}" class="w3-container w3-hide">
                    {foreach from=$comment['subcomment'] item=subcomment}
                        <div class="w3-container w3-margin-bottom">
                            <div class='w3-row w3-small w3-border-bottom'>{$subcomment['date']} - {$subcomment['user']}</div>            
                            <div>
                                {$subcomment['CommentText']}
                            </div>                        
                        </div>
                    {/foreach}
                </div>
            {/if}
        </div>
    {/foreach}
</div>