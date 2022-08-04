<<<<<<< HEAD
<?php
/**
 * Vue
 * Informations (locales + générales)
 */
?>
<h3>Informations</h3>

<div class='row'>
<?php foreach($infos AS $info){
    if($info->contenu!='' && $info->actif!='off'){?>
        <?php $class="";?>
        <?php if($info->urgent==1){ 
            $class="alert alert-danger"; 
            if($info->idCong==-1){
                $title = ucfirst('à tous les proclamateurs');
                $class.= ' mb-3';
            }
            else{
                $title = "Aux proclamateurs de l'assemblée $congName";
            }
        }
        else{
            if($info->idCong==-1){
                $title = ucfirst('Informations générales');
                $class.= " mb-5";
            }
            else{
                $title = "Informations concernant l'assemblée $congName";
                $class.= " mb-3";
            }
        }
        ?>
        <div class='col-sm-12 mt-3 <?=$class?>'>
            <h5 class='text-center mb-3'><?php if($info->urgent){?><b>URGENT : </b><?php } ?><?=$title?></h5>    
            <?=$info->contenu?>
        </div>
    <?php }
    
    } ?>
=======
<?php
/**
 * Vue
 * Informations (locales + générales)
 */
?>
<h3>Informations</h3>

<div class='row'>
<?php foreach($infos AS $info){
    if($info->contenu!='' && $info->actif!='off'){?>
        <?php $class="";?>
        <?php if($info->urgent==1){ 
            $class="alert alert-danger"; 
            if($info->idCong==-1){
                $title = ucfirst('à tous les proclamateurs');
                $class.= ' mb-3';
            }
            else{
                $title = "Aux proclamateurs de l'assemblée $congName";
            }
        }
        else{
            if($info->idCong==-1){
                $title = ucfirst('Informations générales');
                $class.= " mb-5";
            }
            else{
                $title = "Informations concernant l'assemblée $congName";
                $class.= " mb-3";
            }
        }
        ?>
        <div class='col-sm-12 mt-3 <?=$class?>'>
            <h5 class='text-center mb-3'><?php if($info->urgent){?><b>URGENT : </b><?php } ?><?=$title?></h5>    
            <?=$info->contenu?>
        </div>
    <?php }
    
    } ?>
>>>>>>> 7ff721c (initial commit)
</div>