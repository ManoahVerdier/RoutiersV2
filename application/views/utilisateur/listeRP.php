<?php
/**
 * Vue - RP
 * Liste des proclamateurs
 */
?>
<div id='wrapListeRP' class='list'>
    <div class='row mb-4'>
        <div class='col-12'>
            <h5>Liste des proclamateurs</h5>
        </div>
    </div>
    <?php if(isset($messageListeRP) && $messageListeRP!=""){?><div class="alert alert-success" role="alert"><?=$messageListeRP?></div><?php }?>
    <div class='row mb-3'>
        <div class='col-12'>
            <a href="<?=site_url('utilisateur/ajoutProcl')?>" class="btn btn-success"><i class="fas fa-user-plus mr-2"></i>Ajouter</a>
        </div>
    </div>
    <?php foreach ($procls as $procl) { 
        if($procl->id !=$auth_user_id){ ?>
        <div class='row mb-3 hover align-items-center pb-1 pt-1'>
            
            <div class='col-lg-8 col-sm-4 col-xs-6 align-top'>
                <a href='<?=site_url('utilisateur/modifProcl')?>/<?=$procl->id?>' class='btn white'><?= $procl->prenom ?> <?= $procl->nom ?></a>
            </div>
            <div class='col-lg-2 col-sm-4 col-xs-3'>
                <a href='<?=site_url('utilisateur/modifProcl')?>/<?=$procl->id?>' class='btn btn-primary'><i class="fas fa-user-edit mr-2"></i>Modifier</a>
            </div>
            <div class='col-lg-2 col-sm-4 col-xs-3'>
                <a href='<?=site_url('utilisateur/supprProcl')?>/<?=$procl->id?>' class='btn btn-danger' data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"><i class="fas fa-user-times mr-2"></i>Supprimer</a>
            </div>
            
        </div>
        <?php }
        
        } ?>
</div>

<script>
    $(document).ready(function() {
    $('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  // other options
});});
</script>