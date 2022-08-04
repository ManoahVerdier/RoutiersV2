<?php
/**
 * Vue - RP
 * Liste des proclamateurs
 */
?>
<div id='wrapGestPubs' class='list'>
    <div id='accordion'>
        <div class='card border-bottom-0' style="border-bottom-left-radius:0;border-bottom-right-radius:0;">
            <div class='card-header bg-secondary p-0'  id="headingOne"><button class="btn btn-secondary btn-block p-3" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Publications</button></div>
            <div id="collapseOne" class="collapse show p-3" aria-labelledby="headingOne" data-parent="#accordion">
                <div class='row mb-4'>
                    <div class='col-12'>
                        <h5>Liste des publications</h5>
                    </div>
                </div>
                <?php if(isset($messageListeRP) && $messageListeRP!=""){?><div class="alert alert-success" role="alert"><?=$messageListeRP?></div><?php }?>
                <div class='row mb-3'>
                    <div class='col-12'>
                        <a onclick="openAdd()" class="btn btn-success text-white" id="btnAddPub"><i class="fas fa-book-open"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter</a>
                        <form id="addpub" class="hidden row px-4">
                            <input type='hidden' value='new' name='type' id='type'/>
                            <input type="hidden" id="objet" name="objet" value="pub"/>
                            <input class="col-lg-5 form-control mr-2" type="text" placeholder="Titre" name="titre" id="titre"/>
                            <input class="col-lg-2 form-control" type="text" placeholder="Type" name="typePub" id="typePub"/>
                            <button class="form-control btn btn-success col-lg-3 ml-3" onclick="$('addpub').submit()"><span><i class="fas fa-book-open"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter </span></button>
                        </form>
                    </div>
                </div>
                <?php foreach ($pubs as $p): ?>
                    <div class='row mb-3 hover align-items-center pb-1 pt-1'>

                        <div class='col-lg-8 col-sm-12 col-xs-6 align-top' id="titrePub<?=$p->id?>">
                            <a onclick="openEditPub(<?=$p->id?>)" class='btn white'><?= $p->titre ?></a>
                        </div>
                        <div class='col-lg-10 col-sm-12 col-xs-6 align-top hidden' id="editPub<?=$p->id?>">
                            <form id="majPub" class='row'>
                                <input type="hidden" id="type" name="type" value="maj"/>
                                <input type="hidden" id="id" name="id" value="<?=$p->id?>"/>
                                <input type="hidden" id="objet" name="objet" value="pub"/>
                                <input class="form-control col-lg-6 m-1 mr-3" id="titre<?=$p->id?>" name="titre" placeholder="<?= $p->titre ?>" value="<?= $p->titre ?>" type="text"/>
                                <input class="form-control col-lg-2 m-1 mr-3" id="type<?=$p->id?>" name="typePub" placeholder="<?= $p->type ?>" value="<?= $p->type ?>" type="text"/>
                                <input class="btn btn-success col-lg-3 m-1 ml-3" type="submit" value="Enregistrer"/>
                            </form>
                        </div>
                        <div class='col-lg-2 col-sm-6 col-xs-3' id='openEditPub<?=$p->id?>'>
                            <a onclick="openEditPub(<?=$p->id?>)" class='btn btn-primary text-white'><i class="fas fa-book-open"></i><i class="fas fa-pen mr-2 overlay-lower-right"></i>Modifier</a>
                        </div>
                        <div class='col-lg-2 col-sm-6 col-xs-3' id='deletePub<?=$p->id?>'>
                            <form id='supprPub<?=$p->id?>' class='hidden'>
                                <input type='hidden' value='<?=$p->id?>' name='id' id='id'/>
                                <input type='hidden' value='suppr' name='type' id='type'/>
                            </form>
                            <a href='/dev/routiers/gestPubs?id=<?=$p->id?>&type=suppr&objet=pub' class='btn btn-danger text-white' data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"><i class="fas fa-book-open"></i><i class="fas fa-times mr-2 overlay-lower-right"></i>Supprimer</a>
                        </div>

                    </div>
                    <?php 
                    endforeach ?>
            </div>

        </div>
        <div class='card' style="border-top-left-radius:0;border-top-right-radius:0;">
            <div class='card-header  bg-secondary p-0'><button style="border-top-left-radius:0;border-top-right-radius:0;" class="btn collapsed btn-secondary btn-block p-3" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Langues</div>
            <div id="collapseTwo" class="collapse p-3" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class='row mb-4'>
                    <div class='col-12'>
                        <h5>Liste des langues</h5>
                    </div>
                </div>
                <div class='row mb-3'>
                    <div class='col-12'>
                        <a onclick="openAddLang()" class="btn btn-success text-white" id="btnAddLang"><i class="fas fa-globe-americas"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter</a>
                        <form id="addlang" class="hidden row px-4">
                            <input type='hidden' value='new' name='type' id='type'/>
                            <input type="hidden" id="objet" name="objet" value="langue"/>
                            <input class="col-lg-8 form-control" type="text" placeholder="Intitulé" name="intitule" id="intitule"/>
                            <button class="form-control btn btn-success col-lg-3 ml-3" onclick="$('addlang').submit()"><span><i class="fas fa-globe-americas"></i><i class="fas fa-plus mr-2 overlay-lower-right"></i>Ajouter </span></button>
                        </form>
                    </div>
                </div>
                <?php foreach ($langs as $l): ?>
                    <div class='row mb-3 hover align-items-center pb-1 pt-1'>

                        <div class='col-lg-8 col-sm-12 col-xs-6 align-top' id="titreLang<?=$l->id?>">
                            <a onclick="openEditLang(<?=$p->id?>)" class='btn white'><?= $l->intitule ?></a>
                        </div>
                        <div class='col-lg-10 col-sm-12 col-xs-6 align-top hidden' id="editLang<?=$l->id?>">
                            <form id="majLang" class='row'>
                                <input type="hidden" id="type" name="type" value="maj"/>
                                <input type="hidden" id="id" name="id" value="<?=$l->id?>"/>
                                <input type="hidden" id="objet" name="objet" value="langue"/>
                                <input class="form-control col-lg-8 m-1 mr-3" id="intitule<?=$l->id?>" name="intitule" placeholder="<?= $l->intitule ?>" value="<?= $l->intitule ?>" type="text"/>
                                <input class="btn btn-success col-lg-3 m-1 ml-3" type="submit" value="Enregistrer"/>
                            </form>
                        </div>
                        <div class='col-lg-2 col-sm-6 col-xs-3' id='openEditLang<?=$l->id?>'>
                            <a onclick="openEditLang(<?=$l->id?>)" class='btn btn-primary text-white'><i class="fas fa-globe-americas"></i><i class="fas fa-pen mr-2 overlay-lower-right"></i>Modifier</a>
                        </div>
                        <div class='col-lg-2 col-sm-6 col-xs-3' id='deleteLang<?=$l->id?>'>
                            <form id='supprLang<?=$l->id?>' class='hidden'>
                                <input type='hidden' value='<?=$l->id?>' name='id' id='id'/>
                                <input type='hidden' value='suppr' name='type' id='type'/>
                                <input type="hidden" id="objet" name="objet" value="langue"/>
                            </form>
                            <a href='/dev/routiers/gestPubs?id=<?=$l->id?>&type=suppr&objet=langue' class='btn btn-danger text-white' data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"><i class="fas fa-globe-americas"></i><i class="fas fa-times mr-2 overlay-lower-right"></i>Supprimer</a>
                        </div>

                    </div>
                    <?php 
                    endforeach ?>
            </div>
        </div>    
    </div>
</div>

<script>
    $(document).ready(function() {
    $('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  copyAttributes : 'href'
});});
function openAdd(){
    $('#addpub').removeClass('hidden');
    $('#btnAddPub').addClass('hidden');
}

function openAddLang(){
    $('#addlang').removeClass('hidden');
    $('#btnAddLang').addClass('hidden');
}

function openEditPub($id){
    $('#editPub'+$id).removeClass('hidden');
    $('#openEditPub'+$id).addClass('hidden');
    $('#titrePub'+$id).addClass('hidden');
    $('#deletePub'+$id).addClass('col-sm-12');
    $('#deletePub'+$id).removeClass('col-sm-6');
    $('#adressePub'+$id).addClass('hidden');
}

function openEditLang($id){
    $('#editLang'+$id).removeClass('hidden');
    $('#openEditLang'+$id).addClass('hidden');
    $('#titreLang'+$id).addClass('hidden');
    $('#deleteLang'+$id).addClass('col-sm-12');
    $('#deleteLang'+$id).removeClass('col-sm-6');
    $('#adresseLang'+$id).addClass('hidden');
}
</script>