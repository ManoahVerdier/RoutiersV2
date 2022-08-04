<?php
/**
 * Vue - RP
 * Liste des proclamateurs
 */
?>
<div id='wrapGestLieux' class='list'>
    <div class='row mb-4'>
        <div class='col-12'>
            <h5>Liste des lieux</h5>
        </div>
    </div>
    <?php if(isset($messageListeRP) && $messageListeRP!=""){?><div class="alert alert-success" role="alert"><?=$messageListeRP?></div><?php }?>
    <div class='row mb-3'>
        <div class='col-12'>
            <a onclick="openAdd()" class="btn btn-success text-white" id="btnAddLieu"><i class="fas fa-map-marked-alt"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter</a>
            <form id="addlieu" class="hidden row">
                <input type='hidden' value='new' name='type' id='type'/>
                <input class="col-lg-2 form-control" type="text" placeholder="Intitule" name="intitule" id="intitule"/>
                <input class="col-lg-6 form-control ml-2" type="text" placeholder="Adresse" name="adresse" id="adresse"/>                                
                <button class="form-control btn btn-success col-lg-3 ml-3" onclick="$('addlieu').submit()"><span><i class="fas fa-map-marked-alt"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter </span></button>
            </form>
        </div>
    </div>
    <?php foreach ($lieux as $l) { ?>
        <div class='row mb-3 hover align-items-center pb-1 pt-1 breakWord'>
            
            <div class='col-lg-2 col-sm-12 col-xs-6 align-top breakWord px-1' id="intituleLieu<?=$l->id?>">
                <a onclick="openEditLieu(<?=$l->id?>)" class='btn white breakWord p-0'><?= $l->intitule ?></a>
            </div>
            <div class='col-lg-6 col-sm-12 col-xs-6 align-top' id="adresseLieu<?=$l->id?>">
                <a onclick="openEditLieu(<?=$l->id?>)" class='btn white'><?= $l->adresse ?></a>
            </div>
            <div class='col-lg-10 col-sm-12 col-xs-6 align-top hidden' id="editLieu<?=$l->id?>">
                <form id="majLieu" class='row'>
                    <input type="hidden" id="type" name="type" value="maj"/>
                    <input type="hidden" id="d" name="id" value="<?=$l->id?>"/>
                    <input class="form-control col-lg-2 m-1 mr-3" id="intitule<?=$l->id?>" name="intitule" placeholder="<?= $l->intitule ?>" value="<?= $l->intitule ?>" type="text"/>
                    <input class="form-control col-lg-6 m-1 mr-3" id="adresse<?=$l->id?>" name="adresse" placeholder="<?= $l->adresse ?>" value="<?= $l->adresse ?>" type="text"/>
                    <input class="btn btn-success col-lg-3 m-1 ml-3" type="submit" value="Enregistrer"/>
                </form>
            </div>
            <div class='col-lg-2 col-sm-6 col-xs-3' id='openEditLieu<?=$l->id?>'>
                <a onclick="openEditLieu(<?=$l->id?>)" class='btn btn-primary text-white'><i class="fas fa-map-marked-alt"></i><i class="fas fa-pen mr-2 overlay-lower-right"></i>Modifier</a>
            </div>
            <div class='col-lg-2 col-sm-6 col-xs-3' id='deleteLieu<?=$l->id?>'>
                <form id='supprLieu<?=$l->id?>' class='hidden'>
                    <input type='hidden' value='<?=$l->id?>' name='id' id='id'/>
                    <input type='hidden' value='suppr' name='type' id='type'/>
                </form>
                <a href='/dev/routiers/gestLieux?id=<?=$l->id?>&type=suppr' class='btn btn-danger text-white' data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"><i class="fas fa-map-marked-alt"></i><i class="fas fa-times mr-2 overlay-lower-right"></i>Supprimer</a>
            </div>
            
        </div>
        <?php 
        
        } ?>
</div>

<script>
    $(document).ready(function() {
    $('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  copyAttributes : 'href'
});});
function openAdd(){
    $('#addlieu').removeClass('hidden');
    $('#btnAddLieu').addClass('hidden');
}

function openEditLieu($id){
    $('#editLieu'+$id).removeClass('hidden');
    $('#openEditLieu'+$id).addClass('hidden');
    $('#intituleLieu'+$id).addClass('hidden');
    $('#deleteLieu'+$id).addClass('col-sm-12');
    $('#deleteLieu'+$id).removeClass('col-sm-6');
    $('#adresseLieu'+$id).addClass('hidden');
}
</script>