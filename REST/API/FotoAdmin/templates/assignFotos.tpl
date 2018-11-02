{* 
Template für Foto Zuteilung 
Version 1.0.0.2 - 
02.05.2017/na - neu erstellt
*}

<h3>Fotos zuteilen</h3>

<div class="w3-row">
    <form action="#" id="AssignFotoForm">
        <input type="hidden" name="picID" id="picID" value ="{$id}"/>
        <div class="w3-third ">
            <img id="assigningPic" class="w3-margin-bottom" src="{$pic}" alt="zuzuteilendes Foto" style="width:95%;"/>
            <p>
                <button id="assignFoto" class="w3-btn buttoncolor button-hover" type="button">Zuteilen</button>
            </p>
        </div>
        <div class="w3-twothird">

            <div class="w3-row">
                {foreach name='mitgLoop' from=$mitglieder item=mitg}
                    <div class="w3-half w3-margin-bottom">

                        <input name="mitg[]" value="{$mitg['MITG_KEY']}" type="checkbox">
                        <img width="75" src='Content/images/mitg/{$mitg['MITG_PIC_G']}' alt="bildli"/>
                        <span>{$mitg['MITG_NAM']} {$mitg['MITG_VNAM']}</span>
                    </div>
                {/foreach}
            </div>
        </div>
    </form>
</div>

<div id="LogWindow"></div>