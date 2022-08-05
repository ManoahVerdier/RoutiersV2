<?php
/* Vue
 * Gestion des indisponibilités
 */

/* Réglage du temps sur la France */
setlocale(LC_ALL, 'fr_FR');
$fmt = datefmt_create(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'America/Los_Angeles',
    IntlDateFormatter::GREGORIAN
);
$fmt->setPattern('MMM');
?> 

<div id="wrapCreneaux">
    <div class="row">
        <div class="col-sm-12 col-lg-8 col-md-12 col-xs-10">
    <h3>Créneaux à venir</h3>
    <?php
    $currentMonth = 0;
    /* Parcours des créneaux */
    if(empty($creneaux) || sizeof($creneaux)==0 ):?>
    <p>
        Il n'y a pas de créneaux à venir. Pour définir un créneau pour l'assemblée, tu peux te rendre sur <a href="<?=site_url('creneauxAssemblee')?>">créneaux de l'assemblée</a>.
    </p>    
        <?php
    endif;
    foreach ($creneaux as $creneau) {
        $min = $creneau->nb;
        /* Par défaut, disponible, donc classe de bouton vert, pas de préfixe et state à 1 */
        $class = "danger";
        $title = "A pourvoir";
        $part  = "0/$min";
        $invit = "";
        
        if(in_array($creneau->id,$complet)){
            $title="Complet";
            $class="success";
            $part="$min/$min";
        }
        if(in_array($creneau->id,$partiel)){
            $title="Incomplet";
            $class="warning incomplet";
            $part="1/$min";
        }
        
        
        $totInvit=0;
        foreach($invits as $i){
            if($i->id==$creneau->id){
                $s = $i->nb>1?'s':'';
                if($i->ok==""){
                    $invit.="$i->nb en attente ";
                    $totInvit+=$i->nb;
                    if($i->nb==$min && $title == "A pourvoir"){
                        $class='warning incomplet';
                        $title='A pourvoir - en attente';
                        
                    }
                }
                else if($i->ok==0){
                    $invit.="$i->nb refusée$s ";
                    $totInvit+=$i->nb;
                }
            }
        }
        
        if(($title=="Incomplet" || $title=="A pourvoir") && $invit==""){
            $invit = "0 en attente";
        }
        
        if($creneau->annule){
            $class='danger';
            $title='Annulé';
            $invit="$totInvit annulée".($totInvit>1?'s':'');
        }
        
        if($creneau->idCong != $idCong){
            $class = 'secondary';
            $title = 'Administré par '.$creneau->nom;
        }
            
        if(utf8_encode(datefmt_format($fmt,  strtotime($creneau->date))) !== $currentMonth){
            $currentMonth=utf8_encode(datefmt_format($fmt, strtotime($creneau->date)));
        ?>
    <div class="row mt-4 mb-2">
        <h5><?=ucFirst(utf8_encode($currentMonth))?> <?= utf8_encode(datefmt_format($fmt, strtotime($creneau->date))) ?></h5>
    </div>
    <div class="row hidden-phone">
        <div class="col-sm-12 col-md-2 font-weight-bold text-center">
            Date
        </div>
        <div class="col-sm-12 col-md-2 font-weight-bold text-center">
            Heure
        </div>
        <div class="col-sm-12 col-md-4 font-weight-bold text-center">
            Status
        </div>
        <div class="col-sm-12 col-md-4 font-weight-bold text-center">
            Invitation(s)
        </div>
    </div>
        <?php
        }
        ?>
        <div class="row stripped hover creneau mob-mb-10">

            <div class="col-sm-12 col-lg-2 col-xs-2 col-md-2 my-auto text-center">
                <?= ucfirst(utf8_encode(strftime('%A %e', strtotime($creneau->date)))) ?>
            </div>
            <!--<div class="col-lg-1 col-xs-2 col-md-2 col-sm-2 my-auto text-center">
                <?= utf8_encode(strftime('%e', strtotime($creneau->date))) ?>
            </div>
            -->
            <div class="col-sm-12 col-lg-2 col-xs-2 col-md-2 my-auto text-center">
                <?= $creneau->heure ?>h
            </div>
            <!--<div class="col-lg-1 col-xs-2 col-md-2 col-sm-2 my-auto text-center">
                <?= $part ?>
            </div>-->
            <div class="col-sm-12 col-lg-4 col-xs-4 col-md-4 text-center">
                <a href="<?=$creneau->idCong==$idCong?site_url('creneau').'/'.$creneau->id:'#'?>" class="btn btn-<?= $class ?>"><?=$title?></a>
            </div>
            <div class="col-sm-12 col-lg-4 col-xs-4 col-md-4 my-auto text-center pl-1 pr-1">
                <?= $invit ?>
            </div>

        </div>
        
    <?php } ?>
        </div>
    </div>
</div>

<script>
    $('.creneau').click(function(event){
        var link;
        if($(event.target).attr('class').split(' ')[0]=='row')
            link = $(event.target).find('a');
        else
            link = $(event.target.parentElement).find('a');
        console.log(link);
        $('.creneau').unbind('click');
        link.trigger('click');
        link[0].click();
    });
</script>