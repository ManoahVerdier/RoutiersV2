<?php /**
 * Vue
 * Tableaux de résultats et statistiques
 */ ?>

<div class='activite'>
    <h2>Activité de l'assemblée</h2>
    <div class='row'>
        <div class='col-md-4 col-sm-12 text-center'>
            <h5>Sorties de l'assemblée</h5>
            <table class='table table-hover table-bordered'>
                <?php
                foreach ($dates AS $date) {
                    ?>
                    <tr><td><?= utf8_encode($date) ?></td></tr>
                <?php } ?>
                <tr><td class="table-dark">Total : <?= $totDates ?></td></tr>
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
        <div class='col-md-4 col-sm-12 text-center'>
            <canvas id="myChartRap" width="400" height="400"></canvas>
        </div>
        <div class='col-md-4 col-sm-12 text-center'>
            <canvas id="myChartLg" width="400" height="400"></canvas>
        </div>
        <div class='col-md-4 col-sm-12 text-center'>
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
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
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
                        echo "'".$pub->titre."'";
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
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
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
                data: [<?=$totRapports->Nb?>,<?=$totDates-$totRapports->Nb?>],
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