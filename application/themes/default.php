<<<<<<< HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" > 
    <head>
        <title><?php echo $titre; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name='robots' content='noindex,follow' />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous"/>
        <link href="<?= base_url() . 'assets/css/bootstrap.min.css'; ?>" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url() . 'assets/css/bootstrap-responsive.min.css'; ?>" rel="stylesheet" type="text/css"/>
        <?php foreach ($css as $url): ?>
            <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
        <?php endforeach; ?>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/jquery-2.1.1.min.js'; ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/popper.js'; ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/bootstrap.min.js'; ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/bootstrap-confirmation.js'; ?>"></script>
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    </head>

    <body>
        <div class='wrapper'>
        <?php
        //Affichage de l'en-tête
        if ($top) {
            echo $topContent;
        }
        ?>
        <div class="<?= $top ? 'after-top' : '' ?> wrap">
            <div class="wrap">
                <?php if ($container) { ?>
                    <div class="container">
                        <?php echo $output; ?>
                    </div>
                <?php } else { ?>
                    <?php echo $output; ?>
                <?php } ?>
            </div>
            
        </div>
        <?php
            //Affichage du pied de page
            if ($bottom) {
                echo $bottomContent;
            }
        ?>
        <?php foreach ($js as $url): ?>
            <script type="text/javascript" src="<?php echo $url; ?>"></script> 
        <?php endforeach; ?>
    </div>
    </body>

=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" > 
    <head>
        <title><?php echo $titre; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name='robots' content='noindex,follow' />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous"/>
        <link href="<?= base_url() . 'assets/css/bootstrap.min.css'; ?>" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url() . 'assets/css/bootstrap-responsive.min.css'; ?>" rel="stylesheet" type="text/css"/>
        <?php foreach ($css as $url): ?>
            <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
        <?php endforeach; ?>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/jquery-2.1.1.min.js'; ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/popper.js'; ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/bootstrap.min.js'; ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/javascript/bootstrap-confirmation.js'; ?>"></script>
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    </head>

    <body>
        <div class='wrapper'>
        <?php
        //Affichage de l'en-tête
        if ($top) {
            echo $topContent;
        }
        ?>
        <div class="<?= $top ? 'after-top' : '' ?> wrap">
            <div class="wrap">
                <?php if ($container) { ?>
                    <div class="container">
                        <?php echo $output; ?>
                    </div>
                <?php } else { ?>
                    <?php echo $output; ?>
                <?php } ?>
            </div>
            
        </div>
        <?php
            //Affichage du pied de page
            if ($bottom) {
                echo $bottomContent;
            }
        ?>
        <?php foreach ($js as $url): ?>
            <script type="text/javascript" src="<?php echo $url; ?>"></script> 
        <?php endforeach; ?>
    </div>
    </body>

>>>>>>> 7ff721c (initial commit)
</html>