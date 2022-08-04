<?php
/**
 * Template
 * Footer par défaut
 */
if ($vueProcl) {
    $home = site_url('');
    ?>
    <div class="home-bottom border-top box-shadow bg-white">
        <div class="row text-center">
            <div class='col-sm-12'>
                <a href="<?= $home ?>" id='floatHome'>
                    <i class="fas fa-home"></i>
                </a>
            </div>
        </div>
        <div class='row text-center menu'>
            <div class='col-sm hidden-tablet '></div>
            <div class='col-sm-12 col-lg-2 col-md-3 text-center'>
                <a href='<?php echo site_url('disponibilites'); ?>' class='btn btn-outline-secondary btn-bottom'>
                    Mes disponibilités
                </a>
            </div>
            <div class='col-sm-12 col-lg-2 col-md-3 text-center'>
                <a href='<?php echo site_url('contacts'); ?>' class='btn btn-outline-secondary btn-bottom'>
                    Contacts           
                </a>
            </div>
            <div class='col-sm-1 hidden-tablet col-md text-center'></div>
            <div class='col-sm-12 col-lg-2 col-md-3 text-center'>
                <a href='<?php echo site_url('informations'); ?>' class='btn btn-outline-secondary btn-bottom'>  
                    Informations       
                    <?php if($urgent==1){?>
                        <span class="badge badge-danger ml-2 absBadge">!</span>
                    <?php }?>
                </a>
            </div>
            <div class='col-sm-12 col-lg-2 col-md-3 text-center'>
                <a href='<?php echo site_url('options'); ?>' class='btn btn-outline-secondary btn-bottom'>       
                    Options            
                </a>
            </div>
            <div class='col-sm hidden-tablet'></div>
        </div>
        <div class="row text-center">
            <div class='col-sm-12'>
                <a href='<?php echo site_url('deconnexion'); ?>' id='floatDeco' class='btn btn-danger'>
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
    <?php
} else if ($vueRP) {
    $home = site_url('accueilRP');
    ?>
    <div class="home-bottom border-top box-shadow bg-white">
        <div class="row text-center">
            <div class='col-sm-12'>
                <a href="<?= $home ?>" id='floatHome'>
                    <i class="fas fa-home"></i>
                </a>
            </div>
        </div>
        <div class='row text-center menu'>
            <div class='col-sm hidden-tablet'></div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('creneauxAssemblee'); ?>' class='btn btn-outline-secondary btn-bottom'>
                    Créneaux de l'assemblée 
                </a>
            </div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('rapportRP'); ?>' class='btn btn-outline-secondary btn-bottom'>
                    Saisir un rapport           
                </a>
            </div>
            <div class='col-sm-1 text-center hidden-tablet'></div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('informationsAssemblee'); ?>' class='btn btn-outline-secondary btn-bottom'>  
                    Informations pour l'assemblée      
                </a>
            </div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('bilan'); ?>' class='btn btn-outline-secondary btn-bottom'>       
                    Activité de l'assemblée            
                </a>
            </div>
            <div class='col-sm hidden-tablet'></div>
        </div>
        <div class="row text-center">
            <div class='col-sm-12'>
                <a href='<?php echo site_url('deconnexion'); ?>' id='floatDeco' class='btn btn-danger'>
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
    <?php
}else if ($vueAdmin) {
    $home = site_url('accueilAdmin');
    ?>
    <div class="home-bottom border-top box-shadow bg-white">
        <div class="row text-center">
            <div class='col-sm-12'>
                <a href="<?= $home ?>" id='floatHome'>
                    <i class="fas fa-home"></i>
                </a>
            </div>
        </div>
        <div class='row text-center menu'>
            <div class='col-sm hidden-tablet'></div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('gestAssemblee'); ?>' class='btn btn-outline-secondary btn-bottom'>
                    Gestion des assemblées 
                </a>
            </div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('gestLieux'); ?>' class='btn btn-outline-secondary btn-bottom'>
                    Gestion des lieux           
                </a>
            </div>
            <div class='col-sm-1 text-center hidden-tablet'></div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('informationsAdmin'); ?>' class='btn btn-outline-secondary btn-bottom'>  
                    Informations      
                </a>
            </div>
            <div class='col-sm-12 col-lg-2 col-md-12 text-center'>
                <a href='<?php echo site_url('gestPubs'); ?>' class='btn btn-outline-secondary btn-bottom'>       
                    Gestion des publications          
                </a>
            </div>
            <div class='col-sm hidden-tablet'></div>
        </div>
        <div class="row text-center">
            <div class='col-sm-12'>
                <a href='<?php echo site_url('deconnexion'); ?>' id='floatDeco' class='btn btn-danger'>
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
    <?php
}
?>

