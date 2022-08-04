<?php
<<<<<<< HEAD
/**
 * Vue
 * Accueil
 */
?>
<div class="home">
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm-12 col-md-3 col-lg-2 filled activite">
            <div class="card text-center">
                <a href="<?= site_url('activite') ?>">
                    <img class="card-img-top" src="<?= base_url() . 'assets/img/activite.png' ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Activité</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-3 col-md-3 col-lg-3 text-center"><img src="<?=base_url()."assets/img/blueArk"?>" class='img-fluid blueArk ark mx-auto d-block hidden-phone'/></div>
        <div class="col-sm-12 col-md-3 col-lg-2 text-center filled planning">
            <div class="card">
                <a href="<?= site_url('planning') ?>">
                    <img class="card-img-top" src="<?= base_url() . 'assets/img/planning.png' ?>" alt="Card image cap">
                        <?php if($nbInvit>0){?>
                            <span class="badge badge-danger ml-1 absBadge"><?=$nbInvit?></span>
                        <?php }?>
                    <div class="card-body">
                        <h5 class="card-title">Planning</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm-4 col-md-4"><img src="<?=base_url()."assets/img/greenArk"?>" class='img-fluid greenArk ark float-right hidden-phone'/></div>
        <div class="col-sm-12 col-md-3 col-lg-2 text-center filled rapport">
            <div class="card">
                <a href="<?= site_url('rapport') ?>">
                    <img class="card-img-top" src="<?= base_url() . 'assets/img/rapport.png' ?>" alt="Card image cap">
                        <?php if($nbRap>0){?>
                            <span class="badge badge-danger ml-1 absBadge"><?=$nbRap?></span>
                        <?php }?>
                    <div class="card-body">
                        <h5 class="card-title">Rapport</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-4 col-md-4"><img src="<?=base_url()."assets/img/redArk"?>" class='img-fluid redArk ark float-left hidden-phone'/></div>
        <div class="col-sm"></div>
    </div>
=======

/**

 * Vue

 * Accueil

 */

?>

<div class="home">

    <div class="row">

        <div class="col-sm"></div>

        <div class="col-sm-12 col-md-3 col-lg-2 filled activite">

            <div class="card text-center">

                <a href="<?= site_url('activite') ?>">

                    <img class="card-img-top" src="<?= base_url() . 'assets/img/activite.png' ?>" alt="Card image cap">

                    <div class="card-body">

                        <h5 class="card-title">Activité</h5>

                    </div>

                </a>

            </div>

        </div>

        <div class="col-sm-3 col-md-3 col-lg-3 text-center"><img src="<?=base_url()."assets/img/blueArk.png"?>" class='img-fluid blueArk ark mx-auto d-block hidden-phone'/></div>

        <div class="col-sm-12 col-md-3 col-lg-2 text-center filled planning">

            <div class="card">

                <a href="<?= site_url('planning') ?>">

                    <img class="card-img-top" src="<?= base_url() . 'assets/img/planning.png' ?>" alt="Card image cap">

                        <?php if($nbInvit>0){?>

                            <span class="badge badge-danger ml-1 absBadge"><?=$nbInvit?></span>

                        <?php }?>

                    <div class="card-body">

                        <h5 class="card-title">Planning</h5>

                    </div>

                </a>

            </div>

        </div>

        <div class="col-sm"></div>

    </div>

    <div class="row">

        <div class="col-sm"></div>

        <div class="col-sm-4 col-md-4"><img src="<?=base_url()."assets/img/greenArk.png"?>" class='img-fluid greenArk ark float-right hidden-phone'/></div>

        <div class="col-sm-12 col-md-3 col-lg-2 text-center filled rapport">

            <div class="card">

                <a href="<?= site_url('rapport') ?>">

                    <img class="card-img-top" src="<?= base_url() . 'assets/img/rapport.png' ?>" alt="Card image cap">

                        <?php if($nbRap>0){?>

                            <span class="badge badge-secondary  ml-1 absBadge"><?=$nbRap?></span>

                        <?php }?>

                    <div class="card-body">

                        <h5 class="card-title">Rapport</h5>

                    </div>

                </a>

            </div>

        </div>

        <div class="col-sm-4 col-md-4"><img src="<?=base_url()."assets/img/redArk.png"?>" class='img-fluid redArk ark float-left hidden-phone'/></div>

        <div class="col-sm"></div>

    </div>

>>>>>>> 7ff721c (initial commit)
</div>