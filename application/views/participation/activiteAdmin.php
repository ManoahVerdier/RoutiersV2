<?php /**
 * Vue admin
 * Tableaux de résultats et statistiques
 */ ?>

<div class='activite'>
    <h2>Bilan de l'activité</h2>
    <div class='row'>
        <div class='col-md-4 col-sm-12 text-center'>
            <h5>Sorties par assemblées</h5>
            <table class='table table-hover table-bordered'>
                <?php
                foreach ($dates AS $date) {
                    ?>
                <tr>
                    <td <?=$idCong==$date->id?'class="bg-dark"':''?>><a href="./<?=$date->id?>" class="<?=$idCong==$date->id?'text-white':'text-dark'?>"><?= $date->nom ?></a></td>
                    <td <?=$idCong==$date->id?'class="bg-dark text-white"':''?>><?= $date->Nb ?></td>
                </tr>
                <?php } ?>
                <tr><td class="<?=$idCong==-1?'table-dark':''?>"><a href="./" class="<?=$idCong==-1?'text-white':'text-dark'?>">Total</a></td><td class="<?=$idCong==-1?'table-dark':''?>"><?= $totDates ?></td></tr>
            </table>
        </div>
        <div class='col-md-4 col-sm-12 text-center'>
            <h5>Langues rencontrées</h5>
            <table class='table table-hover table-bordered'>
                <?php
                foreach ($langues AS $langue) {
                    ?>
                    <tr><td><?= $langue->intitule ?></td><td><?= $langue->Nb ?></td></tr>
                <?php } ?>
                <tr><td class="table-dark">Total</td><td class="table-dark"><?= $totLangs->Nb ?></td></tr>
            </table>
        </div>
        <div class='col-md-4 col-sm-12 text-center'>
            <h5>Publications</h5>
            <table class='table table-hover table-bordered'>
                <?php
                foreach ($pubs AS $pub) {
                    ?>
                    <tr><td><?= $pub->titre ?></td><td><?= $pub->Nb ?></td></tr>
                <?php } ?>
                <tr><td class="table-dark"  >Total</td><td class="table-dark"><?= $totPubs->Nb ?></td></tr>
            </table>
        </div>
    </div>
    <?php if($totRapports->Nb>0):?>
    <div class='row'>
        <div class='col-md-4 col-sm-12 text-center p-5'>
            <canvas id="myChartRap" width="400" height="400"></canvas>
        </div>
        <div class='col-md-4 col-sm-12 text-center p-4'>
            <canvas id="myChartLg" width="400" height="400"></canvas>
        </div>
        <div class='col-md-4 col-sm-12 text-center p-3'>
            <canvas id="myChartPub" width="400" height="400"></canvas>
        </div>
    </div>
    <?php endif?>
</div>
<?php
$nbLg = sizeof($langues);
$nbPub = sizeof($pubs);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>

<script>
    var ctxLg = document.getElementById("myChartLg");
    var myChartLg = new Chart(ctxLg, {
        type: 'pie',
        data: {
            labels: [
                <?php $i = 0;
                    foreach($langues as $langue) {
                        echo "'".$langue->intitule."'";
                        $i++;        
                        if($i<$nbLg)echo ',';
                    }
                ?>
                    ],
            datasets: [{
                label: '%',
                data: [
                    <?php $i = 0;
                    foreach($langues as $langue) {
                        echo $langue->Nb;
                        $i++;        
                        if($i<$nbLg)echo ',';
                    }
                ?>
                ],
                backgroundColor: [
                    'rgba(255, 99 , 132, 0.2)',
                    'rgba(54 , 162, 235, 0.2)',
                    'rgba(255, 206, 86 , 0.2)',
                    'rgba(75 , 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64 , 0.2)',
                    'rgba(255,  50, 234, 0.2)',
                    'rgba(175,  76, 0  , 0.2)',
                    'rgba(196, 196, 196, 0.2)',
                    'rgba(255, 99 , 132, 0.2)',
                    'rgba(54 , 162, 235, 0.2)',
                    'rgba(255, 206, 86 , 0.2)',
                    'rgba(75 , 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64 , 0.2)',
                    'rgba(255,  50, 234, 0.2)',
                    'rgba(175,  76, 0  , 0.2)',
                    'rgba(196, 196, 196, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99 ,132 , 1)',
                    'rgba(54 , 162, 235, 1)',
                    'rgba(255, 206, 86 , 1)',
                    'rgba(75 , 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64 , 1)',
                    'rgba(255,  50, 234, 1)',
                    'rgba(175,  76, 0  , 1)',
                    'rgba(196, 196, 196, 1)',
                    'rgba(255, 99 ,132 , 1)',
                    'rgba(54 , 162, 235, 1)',
                    'rgba(255, 206, 86 , 1)',
                    'rgba(75 , 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64 , 1)',
                    'rgba(255,  50, 234, 1)',
                    'rgba(175,  76, 0  , 1)',
                    'rgba(196, 196, 196, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        display:false
                    },
                    display:false
                }],
                display:false
            },
            legend : {
                position:'bottom'
            }
        }
    });
    var ctxPub = document.getElementById("myChartPub");
    var myChartPub = new Chart(ctxPub, {
        type: 'pie',
        data: {
            labels: [
                <?php $i = 0;
                    foreach($pubs as $pub) {
                        echo '"'.$pub->titre.'"';
                        $i++;        
                        if($i<$nbPub)echo ',';
                    }
                ?>
                    ],
            datasets: [{
                label: '%',
                data: [
                    <?php $i = 0;
                    foreach($pubs as $pub) {
                        echo $pub->Nb;
                        $i++;        
                        if($i<$nbPub)echo ',';
                    }
                ?>
                ],
                backgroundColor: [
                    'rgba(255, 99 , 132, 0.2)',
                    'rgba(54 , 162, 235, 0.2)',
                    'rgba(255, 206, 86 , 0.2)',
                    'rgba(75 , 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64 , 0.2)',
                    'rgba(255,  50, 234, 0.2)',
                    'rgba(175,  76, 0  , 0.2)',
                    'rgba(196, 196, 196, 0.2)',
                    'rgba(255, 99 , 132, 0.2)',
                    'rgba(54 , 162, 235, 0.2)',
                    'rgba(255, 206, 86 , 0.2)',
                    'rgba(75 , 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64 , 0.2)',
                    'rgba(255,  50, 234, 0.2)',
                    'rgba(175,  76, 0  , 0.2)',
                    'rgba(196, 196, 196, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99 ,132 , 1)',
                    'rgba(54 , 162, 235, 1)',
                    'rgba(255, 206, 86 , 1)',
                    'rgba(75 , 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64 , 1)',
                    'rgba(255,  50, 234, 1)',
                    'rgba(175,  76, 0  , 1)',
                    'rgba(196, 196, 196, 1)',
                    'rgba(255, 99 ,132 , 1)',
                    'rgba(54 , 162, 235, 1)',
                    'rgba(255, 206, 86 , 1)',
                    'rgba(75 , 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64 , 1)',
                    'rgba(255,  50, 234, 1)',
                    'rgba(175,  76, 0  , 1)',
                    'rgba(196, 196, 196, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        display:false
                    },
                    display:false
                }],
                display:false
            },
            legend : {
                position:'bottom'
            }
        }
    });
    var ctxRap = document.getElementById("myChartRap");
    var myChartRap = new Chart(ctxRap, {
        type: 'pie',
        data: {
            labels: ['Rapport effectué','Rapport non effectué'],
            datasets: [{
                label: '%',
                data: [<?=$totRapports->Nb?>,<?=$totGraph-$totRapports->Nb?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        display:false
                    },
                    display:false
                }],
                display:false
            },
            legend : {
                position:'bottom'
            }
        }
    });
</script>