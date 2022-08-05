<?php
/**
 * Vue
 * Accueil
 */
?>
<div class="home homeRP">
    <div class="row <?=$part?'hidden':''?>">
        <div class="col-sm-12">
            <div class="alert alert-danger m-0" role="alert">
                <b>Attention</b> : Aucune activité n'a été enregistrée au cours de la semaine passée.
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm-12 col-md-3 col-lg-2 filled proclamateurs">
            <div class="card text-center">
                <a href="<?= site_url('proclamateurs') ?>">
                    <img class="card-img-top" src="<?= base_url() . 'assets/img/predicateur.png' ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Proclamateurs</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-3 text-center"><img src="<?=base_url()."assets/img/blueArk.png"?>" class='img-fluid blueArk ark mx-auto d-block hidden-phone'/></div>
        <div class="col-sm-12 col-md-3 col-lg-2 text-center filled planning">
            <div class="card">
                <a href="<?= site_url('creneaux') ?>">
                    <img class="card-img-top" src="<?= base_url() . 'assets/img/creneaux.png' ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Créneaux à venir</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm col-lg"></div>
        <div class="col-sm-4 col-md-4 col-lg-4"><img src="<?=base_url()."assets/img/greenArk.png"?>" class='img-fluid greenArk ark float-right hidden-phone'/></div>
        <div class="col-sm-12 col-md-3 col-lg-2 text-center filled rapport">
            <div class="card">
                <a href="<?= site_url('rapportsAssemblee') ?>">
                    <img class="card-img-top" src="<?= base_url() . 'assets/img/rapports.png' ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Rapports de l'assemblée</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4"><img src="<?=base_url()."assets/img/redArk.png"?>" class='img-fluid redArk ark float-left hidden-phone'/></div>
        <div class="col-sm col-lg"></div>
    </div>
</div>