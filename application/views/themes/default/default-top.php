<?php
/**
 * Template
 * Header par défaut : Congrégation, nom et prénom util
 */
?>
<header>
<div class="home-header border-bottom box-shadow bg-white">
    <h1 class="text-center display-4">Témoignage aux routiers</h1>
    <hr/>   
    <h3 class="text-center text-muted  "><?= $cong ?></h3>
    <?php if($vueProcl==1 && $auth_level=='6'){ ?>
        <div id="floatRP">
            <!--<a href='<?=site_url('accueilRP')?>' class='btn btn-secondary'>Vue RP</a>-->
            <input type="checkbox" data-toggle="toggle" data-on="Vue RP" data-off="Vue Proclamateur" onchange="loadPageRP(this)">
        </div>
    <?php } else if($vueRP==1){?>
        <div id="floatRP">
            <!--<a href='<?=site_url('')?>' class='btn btn-secondary'>Vue Proclamateur</a>-->
            <input type="checkbox" checked data-toggle="toggle" data-on="Vue RP" data-off="Vue Proclamateur" onchange="loadPageRP(this)">
        </div>
    <?php } else if($vueProcl==1 && $auth_role=='admin'){ ?>
        <div id="floatRP">
            <!--<a href='<?=site_url('accueilAdmin')?>' class='btn btn-secondary'>Vue Admin</a>-->
            <input type="checkbox" data-toggle="toggle" data-on="Vue Admin" data-off="Vue Proclamateur" onchange="loadPageAdm(this)">
        </div>
        
    <?php } else if($vueAdm==1){?>
        <div id="floatRP">
            <!--<a href='<?=site_url('')?>' class='btn btn-secondary'>Vue Proclamateur</a>-->
            <input type="checkbox" checked data-toggle="toggle" data-on="Vue Admin" data-off="Vue Proclamateur" onchange="loadPageAdm(this)">
        </div>
        
    <?php }?>
</div>
<div class="position-absolute bg-white nomUtil border">
    <?= $prenom . ' ' . $nom?>
</div>
    <script>
        function loadPageAdm(src){
            setTimeout(function(){
                if($(src).prop('checked'))
                    window.location = "<?=site_url('accueilAdmin')?>";
                else
                    window.location = "<?=site_url('')?>";
            },500);
        }
        
        function loadPageRP(src){
            setTimeout(function(){
                if($(src).prop('checked'))
                    window.location = "<?=site_url('accueilRP')?>";
                else
                    window.location = "<?=site_url('')?>";
            },500);
        }
    </script>
</header>