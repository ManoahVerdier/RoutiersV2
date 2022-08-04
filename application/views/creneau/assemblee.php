<<<<<<< HEAD
<?php
$nbLieux=0;
$jours = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
?>
<div id="crenAsWrap">
    <h3 class='mb-5 mt-5'>Créneaux de l'assemblée</h3>    
    <div class='row'>
        <?php foreach($sortie as $lieu){ 
            $autreExist=false;
            $dejaPris = array();
            foreach($autresCong as $autre){
                if($autre->idCren == $lieu->idCren){
                    $autreExist=true;
                    $dejaPris[]=$autre->idCong;
                }
            }
        ?>
        <div class='col-sm-12 col-md-4 mob-mb-10 mob-mt-10'>
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 text-center wrapCol border rounded shadow d-flex flex-column pt-2'>
                    <div class="deleteCren float-right text-danger">
                        <?php if($lieu->idAdmin==$idCong){?>
                        <form method="post" id="deleteForm<?=$nbLieux?>">
                            <input type="hidden" id="type" name="type" value="delete"/>
                            <input type="hidden" id="id" name="id" value="<?=$lieu->idCren?>"/>
                            <button class='btn btn-link p-0 text-danger' type="submit" data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non" data-content="Tous les créneaux concernés à venir seront impactés. Un mail sera envoyé aux proclamateurs participants ou invités.">
                                <i class="fas fa-times-circle submit" ></i>
                            </button>
                        </form>
                        <?php } ?>
                    </div>
                    <div class='row full-height contentCreneau<?=$nbLieux?>'>
                        <div class='col-sm-12'>
                            <h4>Le <?=$jours[$lieu->jour-1]?></h4>
                        </div>
                        <div class='col-sm-12 text-center'>
                            <h5><?=$lieu->heure?>h</h5>
                        </div>
                        <div class='col-sm-12 text-center'>
                            <b><?=$lieu->intitule?></b>
                        </div>
                        <div class='col-sm-12 text-center'>
                            <?=$lieu->adresse?>
                        </div>
                        <div class='col-sm-12 text-center mt-2'>
                            <?=$lieu->nb?> proclamateurs requis
                        </div>
                        <?php if($autreExist){?>
                        <div class='col-sm-12 text-center mt-3'>
                            <b>Ce créneau concerne également : </b>
                            <?php   foreach($autresCong as $autre){
                                        if($autre->idCren == $lieu->idCren){
                                            ?><div class='autres'><?=$autre->nom?>
                                                <form method="post" id="deleteCongForm<?=$nbLieux?>">
                                                    <input type="hidden" id="type" name="type" value="delConc"/>
                                                    <input type="hidden" id="id" name="idCren" value="<?=$lieu->idCren?>"/>
                                                    <input type="hidden" id="id" name="idCong" value="<?=$autre->idCong?>"/>
                                                    <?php if($lieu->idAdmin==$idCong):?>
                                                    <button class='btn btn-link p-0 text-danger' type="submit" data-toggle="confirmation" data-title="Êtes-vous sûr" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non" data-content="Mis à part les créneau déjà programmés, cette assemblée ne sera plus invitée à ce créneau.">
                                                        <i class="fas fa-times-circle submit" ></i>
                                                    </button>
                                                    <?php endif?>
                                                </form>
                                            </div>
                                            <?php   
                                        }
                                    }
                            ?>
                        </div>
                        <?php }?>
                    </div>
                    <div class='row mt-auto mb-3 pt-3'>
                        <div class='col-sm-12 text-center mt-2'>
                            <form action='' method='post' id='modCrenForm<?=$nbLieux?>' class='row pt-2 px-3 pb-0 '>
                                <select id='jour' name='jour' class='col-sm-6 form-control mob-mb-10'>
                                    <?php for($i=0;$i<7;$i++){?>
                                    <option <?=$i+1==$lieu->jour?'selected':''?> value='<?=$i+1?>'><?=$jours[$i]?></option>
                                    <?php } ?>
                                </select>
                                <input type='number' id='heure' name='heure' class='col-sm-5 form-control mob-ml-10 ml-4 mob-mb-10 mob-pr-0' placeholder='Heure' min="0" max ="23" value="<?=$lieu->heure?>"/>
                                <select id='lieu' name='lieu' class='col-md-12 col-sm-12 mt-3 mob-mt-10 form-control'>
                                    <?php foreach($lieux as $l){?>
                                    <option <?=$l->id==$lieu->idLieu?'selected':''?>  value='<?=$l->id?>'><?=$l->intitule?></option>
                                    <?php } ?>
                                </select>
                                <div class="form-check mt-3 col-md-12 col-sm-11 text-left">
                                    <input class='form-check-input' type='checkbox' name='auto' id='auto' style='font-size:18pt' <?=$lieu->auto==1?'checked':''?>/>
                                    <label class='ml-2 form-check-label' for='auto' >Invitations automatiques</label>
                                </div>
                                <div class="form-check mt-3 col-md-12 col-sm-11 text-left">
                                    <input class='form-check-input' type='checkbox' name='libre' id='libre' style='font-size:18pt' <?=$lieu->libre==1?'checked':''?>/>
                                    <label class='ml-2 form-check-label' for='auto' >Inscription libre</label>
                                </div>
                                
                                <input type='number' id='nb' name='nb' class='col-sm-2 form-control mt-3' placeholder='Nb requis' min="0" max ="23" value="<?=$lieu->nb?>"/><span id="withNb" class="col-sm-10 mt-4 text-left"> proclamateurs requis</span>
                                <input type="hidden" id="type" name="type" value="modif"/>
                                <input type="hidden" id="id" name="id" value="<?=$lieu->idCren?>"/>
                                <input type='submit' class='btn btn-primary btn-block mt-4' value="Modifier" data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non" data-content="Tous les créneaux concernés à venir seront impactés. Un mail sera envoyé aux proclamateurs participants ou invités."/>
                            </form>
                            <?php if($lieu->idAdmin==$idCong){?><input type='button' onclick='modif(<?=$nbLieux?>)' class='btn btn-primary btn-block' value="Modifier" id="butMod<?=$nbLieux?>"/><?php }
                            else {?><div class='btn btn-secondary btn-block'>Administré par <?=$lieu->nom?></div><?php } ?>
                            <input type='button' onclick='extend(<?=$nbLieux?>)' class='btn btn-info btn-block' value="Étendre à d'autres assemblées" id="butExt<?=$nbLieux?>"/>
                            <form action='' method='post' id='extCrenForm<?=$nbLieux?>' class='row pt-0 px-3 pb-0 '>
                                <label for="partage1" class="mt-2">Ce créneau concernera aussi : </label>
                                <select id='partage' name='partage' class='col-sm-12 mob-mt-10 form-control'>
                                    <?php foreach($congs as $c){
                                        if($c->id != $idCong && !in_array($c->id, $dejaPris)){?>
                                    <option value='<?=$c->id?>'><?=$c->nom?></option>
                                    <?php }
                                    }?>
                                </select>
                                <input type="hidden" id="type" name="type" value="extend"/>
                                <input type="hidden" id="id" name="id" value="<?=$lieu->idCren?>"/>
                                <input type='submit' class='btn btn-info btn-block mt-3' value="Étendre"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $nbLieux++;
        }
        while($nbLieux <3){?>
            <div class='mob-mb-10 mob-mt-10 col-sm-12 col-md-4 ajoutCrenWrap ajoutCrenWrap<?=$nbLieux?>' onclick="showAdd(<?=$nbLieux?>)" id="addWrap<?=$nbLieux?>">
                <div class='row px-4 full-height rowCol d-flex flex-column'>
                    <div class='col-sm-12 text-center wrapCol border-primary rounded pt-2 ajoutCren d-flex flex-column pt-3'>
                        <form action='' method='post' id='addCrenForm<?=$nbLieux?>' class='row p-3'>
                            <select id='jour' name='jour' class='col-sm-6 form-control mob-mb-10'>
                                <?php for($i=0;$i<7;$i++){?>
                                <option value='<?=$i+1?>'><?=$jours[$i]?></option>
                                <?php } ?>
                            </select>
                            <input type='number' id='heure' name='heure' class='col-sm-5 form-control ml-4 mob-ml-10 mob-mb-10' placeholder='Heure' min="0" max ="23" required/>
                            <select id='lieu' name='lieu' class='col-sm-12 mt-3 form-control mob-mt-10' required >
                                <?php foreach($lieux as $l){?>
                                <option value='<?=$l->id?>'><?=$l->intitule?></option>
                                <?php } ?>
                            </select>
                            <div class="form-check mt-3">
                                <input class='form-check-input' type='checkbox' name='auto' id='auto' style='font-size:18pt'/>
                                <label class='ml-2 form-check-label' for='auto' >Invitations automatiques</label>
                            </div>
                            <div class="form-check mt-3">
                                <input class='form-check-input' type='checkbox' name='libre' id='libre' style='font-size:18pt'/>
                                <label class='ml-2 form-check-label' for='auto' >Inscription libre</label>
                            </div>
                            <input type='number' id='nb' name='nb' class='col-sm-12 form-control mt-3' placeholder='Nb proclamateurs requis' min="0" max ="23" value="" required />
                            <input type="hidden" id="type" name="type" value="add"/>
                            <input type="submit" id='submit<?=$nbLieux?>' class="hidden" value="add"/>
                        </form>
                        <i class="fas fa-plus-circle bigPlus mb-5 bigPlus<?=$nbLieux?> text-primary mt-4"></i>
                        <input type='button' onclick='ajout(<?=$nbLieux?>)' class='btn btn-primary btn-block mt-auto mb-3 showAdd' value="Ajouter"/>
                    </div>
                </div>
            </div>
        <?php
            $nbLieux++;
        }
        ?>
    </div>
</div>

<script>
    <?php 
    $a=0;
    foreach($sortie as $lieu){
        ?>
            $("#modCrenForm<?=$a?>").hide();
            $("#extCrenForm<?=$a?>").hide();
        <?php
        $a++;
    }
    while($a<3){?>
            $("#addCrenForm<?=$a?>").hide();
            <?php
            $a++;
    }
    
    ?>
   
    function showAdd(nb){
        $("#addCrenForm"+nb).show();
        $(".bigPlus"+nb).hide();
        $(".ajoutCrenWrap"+nb).css('cursor','auto');
    }
    
    function ajout(nb){
        if($('#addCrenForm'+nb).is(":visible"))
            $('#submit'+nb).click();
        else
             $(".ajoutCrenWrap"+nb).trigger('click');
    }
    
    function modif(nb){
        $("#modCrenForm"+nb).show();
        $(".contentCreneau"+nb).hide();
        $("#butMod"+nb).hide();
        $("#butExt"+nb).hide();
    }
    
    function extend(nb){
        $("#extCrenForm"+nb).show();
        $("#butExt"+nb).hide();
        $("#butMod"+nb).hide();
    }
    
    function deleteForm(nb){
        $("#deleteForm"+nb).submit();
    }
    
    $(document).ready(function() {
    $('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  // other options
});});
=======
<?php
$nbLieux=0;
$jours = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
?>
<div id="crenAsWrap">
    <h3 class='mb-5 mt-5'>Créneaux de l'assemblée</h3>    
    <div class='row'>
        <?php foreach($sortie as $lieu){ 
            $autreExist=false;
            $dejaPris = array();
            foreach($autresCong as $autre){
                if($autre->idCren == $lieu->idCren){
                    $autreExist=true;
                    $dejaPris[]=$autre->idCong;
                }
            }
        ?>
        <div class='col-sm-12 col-md-4 mob-mb-10 mob-mt-10'>
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 text-center wrapCol border rounded shadow d-flex flex-column pt-2'>
                    <div class="deleteCren float-right text-danger">
                        <?php if($lieu->idAdmin==$idCong){?>
                        <form method="post" id="deleteForm<?=$nbLieux?>">
                            <input type="hidden" id="type" name="type" value="delete"/>
                            <input type="hidden" id="id" name="id" value="<?=$lieu->idCren?>"/>
                            <button class='btn btn-link p-0 text-danger' type="submit" data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non" data-content="Tous les créneaux concernés à venir seront impactés. Un mail sera envoyé aux proclamateurs participants ou invités.">
                                <i class="fas fa-times-circle submit" ></i>
                            </button>
                        </form>
                        <?php } ?>
                    </div>
                    <div class='row full-height contentCreneau<?=$nbLieux?>'>
                        <div class='col-sm-12'>
                            <h4>Le <?=$jours[$lieu->jour-1]?></h4>
                        </div>
                        <div class='col-sm-12 text-center'>
                            <h5><?=$lieu->heure?>h</h5>
                        </div>
                        <div class='col-sm-12 text-center'>
                            <b><?=$lieu->intitule?></b>
                        </div>
                        <div class='col-sm-12 text-center'>
                            <?=$lieu->adresse?>
                        </div>
                        <div class='col-sm-12 text-center mt-2'>
                            <?=$lieu->nb?> proclamateurs requis
                        </div>
                        <?php if($autreExist){?>
                        <div class='col-sm-12 text-center mt-3'>
                            <b>Ce créneau concerne également : </b>
                            <?php   foreach($autresCong as $autre){
                                        if($autre->idCren == $lieu->idCren){
                                            ?><div class='autres'><?=$autre->nom?>
                                                <form method="post" id="deleteCongForm<?=$nbLieux?>">
                                                    <input type="hidden" id="type" name="type" value="delConc"/>
                                                    <input type="hidden" id="id" name="idCren" value="<?=$lieu->idCren?>"/>
                                                    <input type="hidden" id="id" name="idCong" value="<?=$autre->idCong?>"/>
                                                    <?php if($lieu->idAdmin==$idCong):?>
                                                    <button class='btn btn-link p-0 text-danger' type="submit" data-toggle="confirmation" data-title="Êtes-vous sûr" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non" data-content="Mis à part les créneau déjà programmés, cette assemblée ne sera plus invitée à ce créneau.">
                                                        <i class="fas fa-times-circle submit" ></i>
                                                    </button>
                                                    <?php endif?>
                                                </form>
                                            </div>
                                            <?php   
                                        }
                                    }
                            ?>
                        </div>
                        <?php }?>
                    </div>
                    <div class='row mt-auto mb-3 pt-3'>
                        <div class='col-sm-12 text-center mt-2'>
                            <form action='' method='post' id='modCrenForm<?=$nbLieux?>' class='row pt-2 px-3 pb-0 '>
                                <select id='jour' name='jour' class='col-sm-6 form-control mob-mb-10'>
                                    <?php for($i=0;$i<7;$i++){?>
                                    <option <?=$i+1==$lieu->jour?'selected':''?> value='<?=$i+1?>'><?=$jours[$i]?></option>
                                    <?php } ?>
                                </select>
                                <input type='number' id='heure' name='heure' class='col-sm-5 form-control mob-ml-10 ml-4 mob-mb-10 mob-pr-0' placeholder='Heure' min="0" max ="23" value="<?=$lieu->heure?>"/>
                                <select id='lieu' name='lieu' class='col-md-12 col-sm-12 mt-3 mob-mt-10 form-control'>
                                    <?php foreach($lieux as $l){?>
                                    <option <?=$l->id==$lieu->idLieu?'selected':''?>  value='<?=$l->id?>'><?=$l->intitule?></option>
                                    <?php } ?>
                                </select>
                                <div class="form-check mt-3 col-md-12 col-sm-11 text-left">
                                    <input class='form-check-input' type='checkbox' name='auto' id='auto' style='font-size:18pt' <?=$lieu->auto==1?'checked':''?>/>
                                    <label class='ml-2 form-check-label' for='auto' >Invitations automatiques</label>
                                </div>
                                <div class="form-check mt-3 col-md-12 col-sm-11 text-left">
                                    <input class='form-check-input' type='checkbox' name='libre' id='libre' style='font-size:18pt' <?=$lieu->libre==1?'checked':''?>/>
                                    <label class='ml-2 form-check-label' for='auto' >Inscription libre</label>
                                </div>
                                
                                <input type='number' id='nb' name='nb' class='col-sm-2 form-control mt-3' placeholder='Nb requis' min="0" max ="23" value="<?=$lieu->nb?>"/><span id="withNb" class="col-sm-10 mt-4 text-left"> proclamateurs requis</span>
                                <input type="hidden" id="type" name="type" value="modif"/>
                                <input type="hidden" id="id" name="id" value="<?=$lieu->idCren?>"/>
                                <input type='submit' class='btn btn-primary btn-block mt-4' value="Modifier" data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non" data-content="Tous les créneaux concernés à venir seront impactés. Un mail sera envoyé aux proclamateurs participants ou invités."/>
                            </form>
                            <?php if($lieu->idAdmin==$idCong){?><input type='button' onclick='modif(<?=$nbLieux?>)' class='btn btn-primary btn-block' value="Modifier" id="butMod<?=$nbLieux?>"/><?php }
                            else {?><div class='btn btn-secondary btn-block'>Administré par <?=$lieu->nom?></div><?php } ?>
                            <input type='button' onclick='extend(<?=$nbLieux?>)' class='btn btn-info btn-block' value="Étendre à d'autres assemblées" id="butExt<?=$nbLieux?>"/>
                            <form action='' method='post' id='extCrenForm<?=$nbLieux?>' class='row pt-0 px-3 pb-0 '>
                                <label for="partage1" class="mt-2">Ce créneau concernera aussi : </label>
                                <select id='partage' name='partage' class='col-sm-12 mob-mt-10 form-control'>
                                    <?php foreach($congs as $c){
                                        if($c->id != $idCong && !in_array($c->id, $dejaPris)){?>
                                    <option value='<?=$c->id?>'><?=$c->nom?></option>
                                    <?php }
                                    }?>
                                </select>
                                <input type="hidden" id="type" name="type" value="extend"/>
                                <input type="hidden" id="id" name="id" value="<?=$lieu->idCren?>"/>
                                <input type='submit' class='btn btn-info btn-block mt-3' value="Étendre"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $nbLieux++;
        }
        while($nbLieux <3){?>
            <div class='mob-mb-10 mob-mt-10 col-sm-12 col-md-4 ajoutCrenWrap ajoutCrenWrap<?=$nbLieux?>' onclick="showAdd(<?=$nbLieux?>)" id="addWrap<?=$nbLieux?>">
                <div class='row px-4 full-height rowCol d-flex flex-column'>
                    <div class='col-sm-12 text-center wrapCol border-primary rounded pt-2 ajoutCren d-flex flex-column pt-3'>
                        <form action='' method='post' id='addCrenForm<?=$nbLieux?>' class='row p-3'>
                            <select id='jour' name='jour' class='col-sm-6 form-control mob-mb-10'>
                                <?php for($i=0;$i<7;$i++){?>
                                <option value='<?=$i+1?>'><?=$jours[$i]?></option>
                                <?php } ?>
                            </select>
                            <input type='number' id='heure' name='heure' class='col-sm-5 form-control ml-4 mob-ml-10 mob-mb-10' placeholder='Heure' min="0" max ="23" required/>
                            <select id='lieu' name='lieu' class='col-sm-12 mt-3 form-control mob-mt-10' required >
                                <?php foreach($lieux as $l){?>
                                <option value='<?=$l->id?>'><?=$l->intitule?></option>
                                <?php } ?>
                            </select>
                            <div class="form-check mt-3">
                                <input class='form-check-input' type='checkbox' name='auto' id='auto' style='font-size:18pt'/>
                                <label class='ml-2 form-check-label' for='auto' >Invitations automatiques</label>
                            </div>
                            <div class="form-check mt-3">
                                <input class='form-check-input' type='checkbox' name='libre' id='libre' style='font-size:18pt'/>
                                <label class='ml-2 form-check-label' for='auto' >Inscription libre</label>
                            </div>
                            <input type='number' id='nb' name='nb' class='col-sm-12 form-control mt-3' placeholder='Nb proclamateurs requis' min="0" max ="23" value="" required />
                            <input type="hidden" id="type" name="type" value="add"/>
                            <input type="submit" id='submit<?=$nbLieux?>' class="hidden" value="add"/>
                        </form>
                        <i class="fas fa-plus-circle bigPlus mb-5 bigPlus<?=$nbLieux?> text-primary mt-4"></i>
                        <input type='button' onclick='ajout(<?=$nbLieux?>)' class='btn btn-primary btn-block mt-auto mb-3 showAdd' value="Ajouter"/>
                    </div>
                </div>
            </div>
        <?php
            $nbLieux++;
        }
        ?>
    </div>
</div>

<script>
    <?php 
    $a=0;
    foreach($sortie as $lieu){
        ?>
            $("#modCrenForm<?=$a?>").hide();
            $("#extCrenForm<?=$a?>").hide();
        <?php
        $a++;
    }
    while($a<3){?>
            $("#addCrenForm<?=$a?>").hide();
            <?php
            $a++;
    }
    
    ?>
   
    function showAdd(nb){
        $("#addCrenForm"+nb).show();
        $(".bigPlus"+nb).hide();
        $(".ajoutCrenWrap"+nb).css('cursor','auto');
    }
    
    function ajout(nb){
        if($('#addCrenForm'+nb).is(":visible"))
            $('#submit'+nb).click();
        else
             $(".ajoutCrenWrap"+nb).trigger('click');
    }
    
    function modif(nb){
        $("#modCrenForm"+nb).show();
        $(".contentCreneau"+nb).hide();
        $("#butMod"+nb).hide();
        $("#butExt"+nb).hide();
    }
    
    function extend(nb){
        $("#extCrenForm"+nb).show();
        $("#butExt"+nb).hide();
        $("#butMod"+nb).hide();
    }
    
    function deleteForm(nb){
        $("#deleteForm"+nb).submit();
    }
    
    $(document).ready(function() {
    $('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  // other options
});});
>>>>>>> 7ff721c (initial commit)
</script>