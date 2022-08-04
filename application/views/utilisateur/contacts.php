<?php
/**
 *  Vue 
 *  Contacts - proclamateurs de la même congrégation 
 */
?>
<div class='wrapContacts'>
    <h3>Contacts</h3>
    <div class='table-responsive'>
        <table class='table table-striped table-bordered'>
            <thead>
                <tr>
                    <th class='text-center'>Responsable Prédication</th>
                    <th class='text-center' data-breakpoints="xs sm">Téléphone</th>
                    <th class='text-center' data-breakpoints="xs sm">E-mail</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($procls AS $procl) {
                    if ($procl->id == $rpp) {
                        ?>
                        <tr>
                            <td><?= $procl->prenom ?> <?= $procl->nom ?></td><td class='text-center' data-breakpoints="xs sm">
                                <a href='tel:<?= "+33" . ltrim($procl->telephone, "0") ?>' class='btn btn-dark ctcPhone' >
                                    <?= $procl->telephone ?>
                                    <i class="fas fa-mobile-alt"></i>
                                </a>
                            </td>
                            <td class='text-center' data-breakpoints="xs sm">
                                <a class='btn btn-dark ctcMail' href='mailto:<?= $procl->mail ?>'>
                                    <?= $procl->mail ?>
                                    <i class="fas fa-envelope"></i> 
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class='table-responsive'>
        <table class='table table-striped table-bordered' mobile-show-header="false">
            <thead>
                <tr>
                    <th class='text-center'>Nom</th>
                    <th class='text-center' data-breakpoints="xs sm">Téléphone</th>
                    <th class='text-center' data-breakpoints="xs sm">E-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($procls AS $procl) {
                    if ($procl->id != $rpp) {
                        ?>
                        <tr>
                            <td>
                                <?= $procl->prenom ?> <?= $procl->nom ?>
                            </td>
                            <td class='text-center'>
                                <a href='tel:<?= "+33" . ltrim($procl->telephone, "0") ?>' class='btn btn-dark ctcPhone' >
                                    <?= $procl->telephone ?>
                                    <i class="fas fa-mobile-alt"></i>
                                </a>
                            </td>
                            <td class='text-center'>
                                <a class='btn btn-dark ctcMail' href='mailto:<?= $procl->mail ?>'>
                                    <?= $procl->mail ?>
                                    <i class="fas fa-envelope"></i> 
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Appel a la bibliothèque footable qui permet un affichage déroulant sur mobile-->
<script>
    jQuery(function ($) {
        $('.table').footable();
    });
</script>