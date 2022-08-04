<?php
/**
 * Vue - RP
 * Liste des proclamateurs
 */
?>
<div id='wrapListeRP' class='list'>
    <div class='row mb-4'>
        <div class='col-12'>
            <h5>Liste des Responsables Prédication</h5>
        </div>
    </div>
    <?php if(isset($messageListeRP) && $messageListeRP!=""){?><div class="alert alert-success" role="alert"><?=$messageListeRP?></div><?php }?>
    <div class='row mb-3'>
        <div class='col-12'>
            <a href="<?=site_url('utilisateur/ajoutRP')?>" class="btn btn-success"><i class="fas fa-user-plus mr-2"></i>Inviter à s'inscrire</a>
        </div>
    </div>
    <?php foreach ($procls as $procl) { ?>
        <div class='row mb-3 hover align-items-center pb-1 pt-1'>
            <div class='col-xl-2 col-lg-12 col-xs-12 col-sm-12 col-md-12 align-top'>
                <a href='<?=site_url('utilisateur/modifAdmin')?>/<?=$procl->id?>' class='btn white text-center'><?= $procl->nomCong ?></a>
            </div>
            <div class='col-xl-2 col-lg-12 col-xs-12 col-sm-12 col-md-12 align-top'>
                <a href='<?=site_url('utilisateur/modifAdmin')?>/<?=$procl->id?>' class='btn white text-center'><?= $procl->prenom ?> <?= $procl->nom ?></a>
            </div>
            <div class='col-xl-2 col-lg-3 col-xs-3 col-sm-12 col-md-12'>
                <a href='<?=site_url('utilisateur/changeRP')?>/<?=$procl->idCong?>/<?=$procl->id?>' class='btn btn-primary btn-block'><i class="fas fa-exchange-alt mr-2"></i><span class="lessText">Changer le RP</span><span class="moreText">Changer RP</span></a>
            </div>
            <div class='col-xl-2 col-lg-3 col-xs-3 col-sm-12 col-md-12'>
                <a href='<?=site_url('utilisateur/modifAdmin')?>/<?=$procl->id?>' class='btn btn-secondary btn-block'><i class="fas fa-user-edit mr-2"></i><span class="lessText">Modifier</span><span class="moreText">Modifier</span></a>
            </div>
            <div class='col-xl-2 col-lg-3 col-xs-3 col-sm-12 col-md-12'>
                <a href='<?=site_url('utilisateur/supprProcl')?>/<?=$procl->id?>' class='btn btn-danger btn-block' data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"><i class="fas fa-user-times mr-2"></i>Supprimer</a>
            </div>
            <div class='col-xl-2 col-lg-3 col-xs-3 col-sm-12 col-md-12'>
                <a href='<?=site_url('utilisateur/ctcRP')?>/<?=$procl->id?>' class='btn btn-info btn-block'><i class="fas fa-envelope mr-2"></i>Contacter</a>
            </div>
            
        </div>
    <?php } ?>
    <div class='row mb-2 mt-4'>
        <div class="col-lg-2"></div>
        <div class='col-lg-8'>
            <a href="<?=site_url('utilisateur/ctcRP')?>/-1" class="btn btn-info btn-block"><i class="fas fa-envelope mr-2"></i>Contacter tous</a>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    $('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  
});});
</script>