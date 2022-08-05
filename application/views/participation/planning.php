<?php
/**
 * Vue
 * Planning : invitations en attente et calendrier
 */
setlocale(LC_ALL, 'fr_FR');
?>
<script>
    /*Mise en place du calendrier lorsque la page est chargée*/
    $(document).ready(function() {
        var defV = window.mobilecheck() ? "basicDay" : "month";
    $('#calendar').fullCalendar({
    header: {
    left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek'
    },
            defaultDate: '<?= date('Y-m-d') ?>', //Date par défaut : aujourd'hui
            navLinks: false, // Changement de vues
            editable: false, // Ajout d'évènements
            eventLimit: true, // Petit lien 'more' si trop d'évènements le même jour 
            locale:'fr', // Langue
            defaultView: defV,
            eventClick: function(calEvent, jsEvent, view) {

                if(calEvent.className=='dispo'){
                    $(".msgBox").remove();
                    $(this).append('<div class="vol p-3 bg-white border-secondary position-absolute border msgBox">\n\
                                        <div class="row">\n\
                                            <div class="col-sm-12"><p class="text-center pr-4">Souhaites-tu te porter volontaire sur ce créneau ?</p></div>\n\
                                        </div>\n\
                                        <div class="row p-2">\n\
                                            <form method="POST" class="hidden" id="formvol">\n\
                                                <input type="hidden" value="'+calEvent.id+'" name="id", id="id"/>\n\
                                                <input type="hidden" value="volontaire" name="type", id="type"/>\n\
                                                <input type="hidden" value="1" name="nb", id="nb"/>\n\
                                            </form>\n\
                                            <div class="col-sm-12 mb-1 p-0"><button onclick="sendSeul();" class="btn btn-success btn-block text-white">Oui</button></div>\n\
                                            <div class="col-sm-12 mb-1 p-0"><button onclick="sendAccomp();" class="btn btn-success btn-block text-white">Oui, accompagné</button></div>\n\
                                            <div class="col-sm-12 mb-1 p-0"><button class="btn btn-danger btn-block text-white" onclick="closeMsg()">Non</button></div>\n\
                                        </div>\n\
                                    </div>')
                    $(this).css('border-color', 'red');
                    $(this).append('<script>function closeMsg(){$(".msgBox").remove()}');
                    $(this).append('<script>function sendSeul(){$("#formvol").submit()}');
                    $(this).append('<script>function sendAccomp(){$("#nb").attr("value","2");$("#formvol").submit()}');
                }
            },
            events: [
<?php
/* Ajout des participations */
foreach ($parts as $part) {
    ?>
                {
                title: 'Routiers',
                        start: new Date(<?= date('Y', $part->date) ?>,<?= date('m', $part->date) - 1 ?>,<?= date('d', $part->date) ?>,<?= $part->heure ?>, 0),
                        allday: false,
                        className: 'part',
                        url:'<?=site_url('creneauProcl').'/'.$part->id?>'
                },
    <?php
}
?>
<?php
/* Ajout des invitations */
foreach ($invits as $invit) {
    ?>
                {
                title: 'Invitation en attente',
                        start: new Date(<?= date('Y', $invit->date) ?>,<?= date('m', $invit->date) - 1 ?>,<?= date('d', $invit->date) ?>,<?= $invit->heure ?>, 0),
                        allday: false,
                        className: 'invit'
                },
    <?php
}

foreach ($creneaux as $cren):
    if(! in_array($cren->id,$complet) && ! in_array($cren->id,$partiel)&& ! in_array($cren->date,$parts) && ! in_array($cren->date,$invits) ):?>
            {
                title:'A pourvoir',
                start: new Date(<?= date('Y', strtotime($cren->date)) ?>,<?= date('m', strtotime($cren->date)) - 1 ?>,<?= date('d', strtotime($cren->date)) ?>,<?= $cren->heure ?>, 0),
                allday:false,
                className:'dispo',
                id:<?=$cren->id?>
            },
    <?php endif;endforeach;
?>
                ]
    });
    });
</script>

<div id='wrap'>
    <div class='row mt-5 mb-5'>
        <h2 class="hidden-desktop text-center d-inline-block">Mon planning</h2>
    <?php foreach ($invits as $invit) { if($invit->status!=-1){?>
    <div class='col-12 alert alert-info mb-3'>
        <div class='row'>
            <div class='col-sm-12 col-md-12 col-lg-5 align-middle d-inline py-1' style='line-height:30px'>
                <?= ucfirst(utf8_encode(strftime('%A %e %B %Y',$invit->date)))?> à <?=$invit->heure?>h (<?=$invit->intitule?>)
            </div>
            <form class='col-sm-6 col-md-4 col-lg-2' method='post'>
                <input type='hidden' id='choix' name='choix' value='1'/>
                <input type='hidden' id='id' name='id' value='<?=$invit->id?>'/>
                <input type='submit' value='Accepter' class='btn btn-success btn-block'/>
            </form>
            <form class='col-sm-6 col-md-4 col-lg-3' method='post'>
                <input type='hidden' id='choix' name='choix' value='2'/>
                <input type='hidden' id='id' name='id' value='<?=$invit->id?>'/>
                <input type='submit' value='Accepter accompagné' class='btn btn-success btn-block'/>
            </form>
            <form class='col-sm-12 col-md-4 col-lg-2 mob-mt-10' method='post'>
                <input type='hidden' id='choix' name='choix' value='-1'/>
                <input type='hidden' id='id' name='id' value='<?=$invit->id?>'/>
                <input type='submit' value='Refuser' class='btn btn-danger btn-block'/>
            </form>
        </div>
    </div>
    <?php }else{ print_r($invit);?>
        <div class='col-12 alert alert-warning mb-3'>
        <div class='row'>
            <div class='col-sm-12 col-md-12 col-lg-8 align-middle d-inline py-1' style='line-height:30px'>
                Désolé, ton invitation du <?= ucfirst(utf8_encode(strftime('%A %e %B %Y',$invit->date)))?> à <?=$invit->heure?>h a été annulée (créneau pourvu)
            </div>
            <form class='col-sm-6 col-md-4 col-lg-4' method='post'>
                <input type='hidden' id='choix' name='choix' value='-2'/>
                <input type='hidden' id='id' name='id' value='<?=$invit->id?>'/>
                <input type='submit' value="C'est noté" class='btn btn-warning btn-block'/>
            </form>
        </div>
    </div>
    <?php }
    } ?>
    </div>
    <!--Obligatoire pour la bibliothèque fullCalendar-->
    <div id='calendar'></div>
    <div style='clear:both'></div>
    
</div>
<script>
    window.mobilecheck = function() {
  var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};
</script>