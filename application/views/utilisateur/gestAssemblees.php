<?php
/**
 * Vue - RP
 * Liste des proclamateurs
 */
?>
<div id='wrapGestCong' class='list'>
    <div class='row mb-4'>
        <div class='col-12'>
            <h5>Liste des assemblées</h5>
        </div>
    </div>
    <?php if(isset($messageListeRP) && $messageListeRP!=""){?><div class="alert alert-success" role="alert"><?=$messageListeRP?></div><?php }?>
    <div class='row mb-3'>
        <div class='col-12'>
            <a onclick="openAdd()" class="btn btn-success text-white" id="btnAddCong"><i class="fas fa-users"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter</a>
            <form id="addcong" class="hidden row">
                <input type='hidden' value='new' name='type' id='type'/>
                <input class="col-lg-8 form-control" type="text" placeholder="Nom" name="nom" id="nom"/>                
                <button class="form-control btn btn-success col-lg-3 ml-3" onclick="$('addcong').submit()"><span><i class="fas fa-users"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter </span></button>
            </form>
        </div>
    </div>
    <?php foreach ($assemblees as $a) { ?>
        <div class='row mb-3 hover align-items-center pb-1 pt-1'>
            
            <div class='col-lg-8 col-sm-12 col-xs-6 align-top' id="nameCong<?=$a->id?>">
                <a onclick="openEditCong(<?=$a->id?>)" class='btn white'><?= $a->nom ?></a>
            </div>
            <div class='col-lg-10 col-sm-12 col-xs-6 align-top hidden' id="editCong<?=$a->id?>">
                <form id="majCong" class='row'>
                    <input type="hidden" id="type" name="type" value="maj"/>
                    <input type="hidden" id="d" name="id" value="<?=$a->id?>"/>
                    <input class="form-control col-lg-8 m-1 mr-3" id="nom" name="nom" placeholder="<?= $a->nom ?>" value="<?= $a->nom ?>" type="text"/>
                    <input class="btn btn-success col-lg-3 m-1 ml-3" type="submit" value="Enregistrer"/>
                </form>
            </div>
            <div class='col-lg-2 col-sm-6 col-xs-3' id='openEditCong<?=$a->id?>'>
                <a onclick="openEditCong(<?=$a->id?>)" class='btn btn-primary text-white'><i class="fas fa-users"></i><i class="fas fa-pen mr-2 overlay-lower-right"></i>Modifier</a>
            </div>
            <div class='col-lg-2 col-sm-6 col-xs-3' id="deleteCong<?=$a->id?>">
                <form id='supprCong<?=$a->id?>' class='hidden'>
                    <input type='hidden' value='<?=$a->id?>' name='id' id='id'/>
                    <input type='hidden' value='suppr' name='type' id='type'/>
                </form>
                <a href='/dev/routiers/gestAssemblee?id=<?=$a->id?>&type=suppr' class='btn btn-danger text-white' data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"><i class="fas fa-users"></i><i class="fas fa-times mr-2 overlay-lower-right"></i>Supprimer</a>
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
    $('#addcong').removeClass('hidden');
    $('#btnAddCong').addClass('hidden');
}

function openEditCong($id){
    $('#editCong'+$id).removeClass('hidden');
    $('#openEditCong'+$id).addClass('hidden');
    $('#deleteCong'+$id).addClass('col-sm-12');
    $('#deleteCong'+$id).removeClass('col-sm-6');
    $('#nameCong'+$id).addClass('hidden');
}
</script>