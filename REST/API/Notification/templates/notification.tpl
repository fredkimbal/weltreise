{* Template für Gallerie *}
<h1>E-Mail Benachrichtigung</h1>
<form id="notificationForm" class="w3-container">

    <label class="w3-text-black"><b>Betreff</b></label>
    <input name="subject" class="w3-input w3-border" type="text" value="Neues auf luanaundandy.ch">

    <label class="w3-text-black"><b>Nachricht</b></label>
    <textarea name="message" class="w3-input w3-border" rows="10" style="resize:none">
        <p>Hallo Freunde</p>
        <p>Es gibt neues auf <a href="http://www.luanaundandy.ch" target="_blank">luanaundandy.ch</a></p>
        <p>
            Viel Spass beim Lesen<br/>
            Luana und Andy
        </p>
    </textarea>    

    <button id="sendNotification" class="w3-btn w3-orange" type="button">Senden</button>

</form> 

