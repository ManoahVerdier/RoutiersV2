<?php
/**
 * Vue - RP
 * Liste des proclamateurs
 */
?>
<div id='wrapListeRP' class='list'>
    <div class='row mb-4'>
        <div class='col-12'>
            <h5>Envoi d'un message</h5>
        </div>
    </div>
    <?php if(isset($messageRP) && $messageRP!=""){?>
    <div class="alert alert-success" role="alert"><?=$messageRP?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php }?>
    <?php if(isset($messageRPError) && $messageRPError!=""){?>
    <div class="alert alert-danger" role="alert"><?=$messageRPError?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php }?>
    <div class='row mt-3'>
        <div class='col-12 p-3'>
            <form class='row p-3' method='post'>
                <input type='text' name='objet' id='objet' placeholder="Objet" class='col-lg-6 form-control mb-4'/>
                <textarea rows='15' name='corps' id='corps' class='col-lg-12 form-control mb-4' placeholder='Message'></textarea>
                <a href='<?=site_url('utilisateur/listeAdmin')?>' class='btn btn-secondary col-lg-4'>Retourner à la liste</a>
                <div class='col-lg-1'></div>
                <input type='submit' value='Envoyer' class='btn btn-success col-lg-7'/>
            </form>
        </div>
    </div>
</div>