<<<<<<< HEAD
<?php
setlocale(LC_TIME, "fr_FR");
?>
 <?php if (isset($message) && $message != '') { ?>
    <div class='alert alert-success'> <?= $message ?></div>
<?php } ?>
<div class='rapport'>
    <h3>Remplir manuellement un rapport</h3>
    <form class='row' method='post'>
        <div class='col-sm-12 mb-4'>
            <p>Cette page permet de remplir un rapport pour un créneau passé ou pour une participation non liée à un créneau en saisissant simplement la date</p>
        </div>
        <div class='col-sm-12 text-center' id='ORcenter'><span>OU</span></div>
        <div class='col-sm-6 border-right pt-3 pb-3'>
            <h5 class='text-center mb-4'>Choisir un créneau existant</h5>
            <select id='idCren' name='idCren' class='form-control mb-5'>
                <option selected disabled value='-1'>Choisir un créneau</option>
                <?php foreach($parts as $part){?>
                <option value='<?=$part->idCren?>'><?=ucfirst(strftime("%A %d %B %Y",strtotime($part->date)));?></option>
                <?php } ?>
            </select>
            <input type='submit' value='Choisir' class='btn btn-block btn-primary choose mt-4'/>
        </div>
        <div class='col-sm-6 pt-3'>
            <div class='row'>
                <h5 class='text-center col-sm-12 mb-4'>Entrer une date et une heure</h5>
                <div class='col-sm'></div>
                <div class='col-sm-6'>
                    <input id='date' name='date' type='date' placeholder='Date de la participation' value="<?=date('Y-m-d')?>" class='form-control mr-0'/>
                </div> 
                <div class='col-sm-4'>
                    <input id='heure' name='heure' type='number' placeholder='Heure' class='form-control  ml-0'/>
                </div>
                <div class='col-sm'></div>
                <div class='col-sm-12 pb-3'>
                    <input type='submit' value='Choisir' class='btn btn-block btn-primary choose mt-4'/>
                </div>
            </div>
        </div>
    </form>
</div>
=======
<?php
setlocale(LC_TIME, "fr_FR");
?>
 <?php if (isset($message) && $message != '') { ?>
    <div class='alert alert-success'> <?= $message ?></div>
<?php } ?>
<div class='rapport'>
    <h3>Remplir manuellement un rapport</h3>
    <form class='row' method='post'>
        <div class='col-sm-12 mb-4'>
            <p>Cette page permet de remplir un rapport pour un créneau passé ou pour une participation non liée à un créneau en saisissant simplement la date</p>
        </div>
        <div class='col-sm-12 text-center' id='ORcenter'><span>OU</span></div>
        <div class='col-sm-6 border-right pt-3 pb-3'>
            <h5 class='text-center mb-4'>Choisir un créneau existant</h5>
            <select id='idCren' name='idCren' class='form-control mb-5'>
                <option selected disabled value='-1'>Choisir un créneau</option>
                <?php foreach($parts as $part){?>
                <option value='<?=$part->idCren?>'><?=ucfirst(strftime("%A %d %B %Y",strtotime($part->date)));?></option>
                <?php } ?>
            </select>
            <input type='submit' value='Choisir' class='btn btn-block btn-primary choose mt-4'/>
        </div>
        <div class='col-sm-6 pt-3'>
            <div class='row'>
                <h5 class='text-center col-sm-12 mb-4'>Entrer une date et une heure</h5>
                <div class='col-sm'></div>
                <div class='col-sm-6'>
                    <input id='date' name='date' type='date' placeholder='Date de la participation' value="<?=date('Y-m-d')?>" class='form-control mr-0'/>
                </div> 
                <div class='col-sm-4'>
                    <input id='heure' name='heure' type='number' placeholder='Heure' class='form-control  ml-0'/>
                </div>
                <div class='col-sm'></div>
                <div class='col-sm-12 pb-3'>
                    <input type='submit' value='Choisir' class='btn btn-block btn-primary choose mt-4'/>
                </div>
            </div>
        </div>
    </form>
</div>
>>>>>>> 7ff721c (initial commit)
        