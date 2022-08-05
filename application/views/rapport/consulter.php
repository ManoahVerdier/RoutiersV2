<?php 
/**
 * Vue
 * Consultation d'un rapport
 */
//Réglage du temps sur France
setlocale(LC_ALL, 'fr_FR');
$fmt = datefmt_create(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL
);
$fmt->setPattern('eeee d MMMM yyyy');
?> 

<div id='consRap'>
    <h3>Participation du <?= utf8_encode(datefmt_format($fmt, strtotime($part!=NULL?$part->date:$cren->date)))?> - <?=$part!=NULL?$part->heure:$cren->heure?> heures</h3>
<?php
/*Regroupement par langue rencontrées*/
foreach($rencs as $renc){
    ?>
    <h4 class='text-center border-top border-bottom'>
         <?=$renc->intitule?>
        <a class="float-right btn btn-white py-0 px-1" href="<?=site_url('supprLng')."/$idRap/$renc->idLang"?>"><i class="fas fa-times text-danger"></i></a>
        <a class="float-right btn btn-white py-0 px-1" href="<?=site_url('modifier')."/$idRap/$renc->idLang"?>"><i class="fas fa-edit"></i></a>
        
    </h4>
    <?php $s = ($renc->nb>1)?'s':'';?>
    <p><?=$renc->nb?> personne<?=$s?> rencontrée<?=$s?></p>
    <?php foreach($placs as $plac){
        if($renc->idLang==$plac->idLang){
            ?><p><?=$plac->qte?> x <?=$plac->titre?></p>
    <?php    } 
    }
    if(isset($faits)&&$faits!=''){?>
        <p><b>Fait notable : </b></p>
    <?php foreach($faits as $fait){
        if($renc->idLang==$fait->idLang){
            ?><p><?=$fait->fait?></p>
    <?php    }
     }
    }
}
?>
            <a href="<?=site_url('ajoutLangue')."/$idRap"?>" class="btn btn-lng btn-block btn-info mt-5">J'ai oublié une langue</a>
</div>