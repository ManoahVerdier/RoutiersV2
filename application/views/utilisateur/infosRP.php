<div class='wrapInfosRP mt-3'>
    <?php if($maj){?>
    <div class='col-12 alert alert-success'>
        Informations mises à jour !
    </div>
    <?php } ?>
    <h3>Informations à destination de la congrégation</h3>
    <div class='row'>
         <div class='col-12'>
             <form class='row' method='post'>
                 <div class="form-group col-12 mt-4">
                     <label for="info"><b>Informations générales</b></label>
                    <textarea id='info' name='info' class="form-control " rows="12"><?=$info?></textarea>
                 </div>
                 <div class="form-group col-12 mt-3">
                     <label for="infoUrg"><b>Informations urgentes</b> <button type="button" class="btn btn-sm btn-toggle <?=isset($actifUrg)&&$actifUrg!='off'?'active':''?>" data-toggle="button" aria-pressed="<?=$actifUrg!='off'?'true':'false'?>" autocomplete="off" id="btnActUrg" onclick="setTimeout(toggle(),10)">
                                                                        <div class="handle"></div>
                                                                       </button>
                     </label>
                    <textarea id='infoUrg' name='infoUrg' class="form-control " rows="12"><?=$infoUrg?></textarea>
                    <input type="hidden" id="actifUrg" name="actifUrg" value="<?=isset($actifUrg)&&$actifUrg!='off'?'on':'off'?>"/>
                 </div>
                 <div class="col-3"></div>
                 <input type='submit' class='form-control btn btn-primary col-6 mt-4' value ="Envoyer"/>
             </form>
         </div>
    </div>
</div>

<script>
    document.getElementById('infoUrg').style.visibility=(document.getElementById("btnActUrg").getAttribute('aria-pressed')=='true'?'visible':'hidden');
function toggle(){
    document.getElementById('actifUrg').value=(document.getElementById("btnActUrg").getAttribute('aria-pressed')=='true'?'off':'on');
    document.getElementById('infoUrg').style.visibility=(document.getElementById("btnActUrg").getAttribute('aria-pressed')=='true'?'hidden':'visible');
}
</script>