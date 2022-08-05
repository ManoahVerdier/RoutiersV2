<?php

class Participation extends MY_Controller {

    public function __construct() {
        setlocale(LC_ALL, 'fr_FR');

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('layout');
        $this->load->model('M_utilisateurs');
        $this->load->model('M_participation');
        $this->load->model('M_rapport');
    }

    public function activite() {
        if ($this->require_min_level(1)) {
            //Récupération des dates de toutes les participation
            $dates = $this->M_participation->getPastPartByUser($this->auth_user_id);

            //Formattage
            $data['dates'] = array();
            foreach($dates AS $date)
            {
                $data['dates'][]=$this->changeDateName(strtotime($date));
            }

            $data['langues']=$this->M_participation->getLanguesRencontreesByUser($this->auth_user_id);
            $data['pubs']=$this->M_participation->getPubLaisseesByUser($this->auth_user_id);

            $data['totDates']=$this->M_participation->getTotPastPartByUser($this->auth_user_id);
            $data['totLangs']=$this->M_participation->getTotLangRencontreesByUser($this->auth_user_id);
            $data['totPubs']=$this->M_participation->getTotPubLaisseesByUser($this->auth_user_id);

            /*Infos urgentes*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }

            $this->layout->set_container(1);
            $this->layout->view('participation/activite',$data);
        }
    }

    public function activiteRP($congId=0) {
        if ($this->require_min_level(1)) {
            if($congId==0)
                $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            //Récupération des dates de toutes les participation
            $dates = $this->M_participation->getPastPartCompleteByCong($congId);
            //Formattage
            $data['dates'] = array();
            foreach($dates AS $date)
            {
                $data['dates'][]=$this->changeDateName(strtotime($date->date));
            }

            $data['langues']=$this->M_participation->getLanguesRencontreesByCong($congId);
            $data['pubs']=$this->M_participation->getPubLaisseesByCong($congId);

            $data['totDates']=$this->M_participation->getTotPastPartByCong($congId);
            $data['totLangs']=$this->M_participation->getTotLangRencontreesByCong($congId);
            $data['totPubs']=$this->M_participation->getTotPubLaisseesByCong($congId);
            $data['totRapports']=$this->M_participation->getTotRapportByCong($congId);


            $this->layout->set_RP();
            $this->layout->set_container(1);
            $this->layout->view('participation/activiteRP',$data);
        }
    }

    public function activiteAdmin($idCong=-1) {
        if ($this->require_min_level(9)) {

            if($idCong!=-1){
                $data['langues']=$this->M_participation->getLanguesRencontreesByCong($idCong);
                $data['pubs']=$this->M_participation->getPubLaisseesByCong($idCong);

                $data['totLangs']=$this->M_participation->getTotLangRencontreesByCong($idCong);
                $data['totPubs']=$this->M_participation->getTotPubLaisseesByCong($idCong);
                $data['totRapports']=$this->M_participation->getTotRapportByCong($idCong);
            }else{
                $data['langues']=$this->M_participation->getLanguesRencontrees();
                $data['pubs']=$this->M_participation->getPubLaissees();

                $data['totLangs']=$this->M_participation->getTotLangRencontrees();
                $data['totPubs']=$this->M_participation->getTotPubLaissees();
                $data['totRapports']=$this->M_participation->getTotRapport();
            }
            $data['datesTmp'] = $this->M_participation->getTotPastPartCongs();
            $data['datesNoCren'] = $this->M_participation->getTotPastPartCongsNoCren();
            $data['dates'] = array();
            $tot = 0;
            if($idCong!=-1)
            $totCong = 0;
            foreach($data['datesTmp'] as $date){
                $d = new stdClass;
                $d->nom = $date->nom;
                $d->id = $date->id;
                $d->Nb = $date->Nb;
                $tot +=$date->Nb;
                foreach($data['datesNoCren'] as $dateNoCren){
                    if($date->id==$dateNoCren->id){
                        $d->Nb=$dateNoCren->Nb+$date->Nb;
                        if($date->id==$idCong)
                            $totCong+=$d->Nb;
                        $tot+=$dateNoCren->Nb;
                    }
                }
                $data['dates'][]=$d;
            }
           $data['totDates']=$tot;
           $data['totGraph']=$idCong!=-1?$totCong:$tot;
           $data['idCong']=$idCong;
            $this->layout->set_Admin();
            $this->layout->set_container(1);
            $this->layout->view('participation/activiteAdmin',$data);
        }
    }

    public function planning(){
        if ($this->require_min_level(1)) {
            $this->load->model('M_creneau');
            $this->load->model('M_utilisateurs');
            if($this->input->post()){
                if(isset($this->input->post()['choix'])){
                    if($this->input->post()['choix']==1){
                        $this->M_participation->setStatusInv(1,$this->input->post()['id']);
                        /*Creer participation*/
                        $part = new stdClass;
                        $part->idCren = $this->M_participation->getInvit($this->input->post()['id'])->idCren;
                        $part->idUtil = $this->auth_user_id;
                        $part->status = 1;

                        $this->M_participation->addPart($part);
                    }
                    else if($this->input->post()['choix']==2){
                        $this->M_participation->setStatusInv(1,$this->input->post()['id']);
                        /*Creer participation*/
                        $part = new stdClass;
                        $part->idCren = $this->M_participation->getInvit($this->input->post()['id'])->idCren;
                        $part->idUtil = $this->auth_user_id;
                        $part->status = 1;

                        $this->M_participation->addPart($part);

                        /*Créer accompagnant*/
                        $part = new stdClass;
                        $part->idCren = $this->M_participation->getInvit($this->input->post()['id'])->idCren;
                        $part->idUtil = -1;
                        $part->status = 1;

                        $this->M_participation->addPart($part);
                    } else if($this->input->post()['choix']!=-2){
                        $this->M_participation->setStatusInv(0,$this->input->post()['id']);
                        $idCrenD = $this->M_participation->getInvit($this->input->post()['id'])->idCren;
                        /*Manuel ou auto ?*/
                        $idCren = $this->M_creneau->getWithDate($idCrenD)->idCreneau;
                        $cren = $this->M_creneau->get($idCren);
                        $crenAuto = $cren->auto;
                        /*Si auto, relance d'invitation*/
                        if($crenAuto==1)
                            $this->inviterCren($idCren,1);
                    }

                    //Récupérer nombre participant créneau

                    $idCrenD = $this->M_participation->getInvit($this->input->post()['id'])->idCren;
                    $idCren = $this->M_creneau->getWithDate($idCrenD)->idCreneau;
                    $cren = $this->M_creneau->get($idCren);
                    $crenNb = $cren->nb;

                    $parts = $this->M_participation->getPartByCren($idCrenD);
                    $nbPart = sizeof($parts);

                    if($nbPart>=$crenNb){
                        $this->M_participation->annulerInvit($idCrenD);
                    }

                    if($this->input->post()['choix']==-2){
                        $this->M_participation->deleteInvit($this->input->post()['id']);
                    }
                }else if(isset($this->input->post()['type']) && $this->input->post()['type']=="volontaire"){
                    $invitation = new stdClass;
                    $invitation->date=date('Y-m-d');
                    $invitation->idCren = $this->input->post()['id']  ;
                    $invitation->idUtil = $this->auth_user_id ;
                    $idInvit = $this->M_participation->addInvit($invitation);

                    $this->M_participation->setStatusInv(1,$idInvit);
                    /*Creer participation*/
                    $part = new stdClass;
                    $part->idCren = $this->input->post()['id'];
                    $part->idUtil = $this->auth_user_id;
                    $part->status = 1;

                    $this->M_participation->addPart($part);

                    if($this->input->post()['nb']>1){
                        /*Créer accompagnant*/
                        $part = new stdClass;
                        $part->idCren = $this->input->post()['id'];
                        $part->idUtil = -1;
                        $part->status = 1;

                        $this->M_participation->addPart($part);
                    }
                }
            }
            /*if($this->auth_user_id==44)$this->auth_user_id=2147484860;*/
            $idCong=$this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['parts']=$this->M_participation->getAllPartByUser($this->auth_user_id);
            $data['invits']=$this->M_participation->getAllInvitByUser($this->auth_user_id);

            $data['creneaux'] = $this->M_creneau->getProcCrenLibreByCong($idCong);
            $data['complet']  = $this->M_creneau->getIdComplet($idCong);
            $data['partiel']  = $this->M_creneau->getIdPartiel($idCong);

            /*infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            $this->layout->ajouter_css('fullcalendar-min');
            $this->layout->ajouter_js('moment.min');
            $this->layout->ajouter_js('fullcalendar');
            $this->layout->ajouter_js('locale/fr');
            $this->layout->set_container(1);
            $this->layout->view('participation/planning',$data);
        }
    }

    public function rapports(){
        /*Doit être connecté*/
        if ($this->require_min_level(1)) {
            //Récupération des participations avec rapport manquant
            $data['parts']=$this->M_participation->getMissingReportsByUser($this->auth_user_id);
            $this->layout->view('participation/rapports',$data);
        }
    }

    /**
     *
     * @param string $myDate la date en timestamp
     * @return string la date sous forme de 'Aujourd'hui' etc. jusqu'à une semaine en arrière
     */
    private function changeDateName($myDate){
        setlocale(LC_ALL, 'fr_FR');
        $myWeek=array("Aujourd'hui","Hier","Avant-hier");
        for($i=3;$i<7;$i++){
            array_push($myWeek,strftime('%A', ucfirst(strtotime('-'.$i.' day'))));
        }
	if($myDate> strtotime('-7 day')){
		return $myWeek[(time()-$myDate)/(60*60*24)];
	}
	else{
		return ucfirst(strftime('%A %e %B %Y',$myDate));
	}
    }

    /**
     * Page RP
     * Vue des participations à venir
     */
    public function partAVenir(){
        //RP seulement
        if ($this->require_min_level(6)) {
            setlocale(LC_ALL, 'fr_FR');

            $this->load->model('M_creneau');

            /* ID de la congrégation (pour recherche des créneaux */
            $idCong = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;

            /* Liste des prochains créneaux */
            $data['creneaux'] = $this->M_creneau->getProcCrenByCong($idCong);
            $data['complet']  = $this->M_creneau->getIdComplet($idCong);
            $data['partiel']  = $this->M_creneau->getIdPartiel($idCong);
            $data['invits']   = $this->M_creneau->getIdInvit($idCong);

            $data['idCong'] = $idCong;

            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('participation/aVenir', $data);
        }
    }

    /**
     * Page RP
     * Détail d'un créneau
     */
    public function creneau($id){
        //RP seulement
        if ($this->require_min_level(6)) {
            $this->load->model('M_creneau');
            $data['creneauDate'] = $this->M_creneau->getWithDate($id);
            $data['creneau'    ] = $this->M_creneau->get($data['creneauDate']->idCreneau);
            $data['parts'      ] = $this->M_participation->getPartByCren($id);
            $data['idCren'     ] = $id;
            $data['procls'     ] = $this->M_creneau->getProclsForCren($data['creneauDate']->idCreneau,$id);
            $data['lieu'       ] = $this->M_creneau->getLieuByCrenId($data['creneauDate']->idCreneau)->intitule;

            if ($this->input->post()) {
                $idProcl = $this->input->post()['procl'];
                if($idProcl != 0 && $idProcl != ''){
                    $mail='';
                    $nom='';
                    foreach($data['procls'] as $procl){
                        if($idProcl==$procl->id){
                            $mail = $procl->mail;
                            $nom  = $procl->prenom;
                        }
                        else if($idProcl==-1){
                            $this->inviter($procl->id,$id,$procl->mail,$procl->prenom);
                        }
                    }
                    if($mail!='' && $idProcl!=-1)
                        $this->inviter($idProcl,$id,$mail,$nom);
                }
            }
            $data['procls'     ] = $this->M_creneau->getProclsForCren($data['creneauDate']->idCreneau,$id);
            $data['invits'     ] = $this->M_participation->getInvitationByCren($id);
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('participation/creneau', $data);
        }
    }

    /**
     * Page RP
     * Détail d'un créneau
     */
    public function creneauProcl($id){
        //RP seulement
        if ($this->require_min_level(1)) {
            $this->load->model('M_creneau');
            $data['creneauDate'] = $this->M_creneau->getWithDate($id);
            $data['creneau'    ] = $this->M_creneau->get($data['creneauDate']->idCreneau);
            $data['parts'      ] = $this->M_participation->getPartByCren($id);
            $data['idCren'     ] = $id;
            $data['procls'     ] = $this->M_creneau->getProclsForCren($data['creneauDate']->idCreneau,$id);
            $data['lieu'       ] = $this->M_creneau->getLieuByCrenId($data['creneauDate']->idCreneau)->intitule;
            $data['lieu_det'   ] = $this->M_creneau->getLieuByCrenId($data['creneauDate']->idCreneau);
            if ($this->input->post()) {
                $idProcl = $this->input->post()['procl'];
                if($idProcl != 0 && $idProcl != ''){
                    $mail='';
                    $nom='';
                    foreach($data['procls'] as $procl){
                        if($idProcl==$procl->id){
                            $mail = $procl->mail;
                            $nom  = $procl->prenom;
                        }
                        else if($idProcl==-1){
                            $this->inviter($procl->id,$id,$procl->mail,$procl->prenom);
                        }
                    }
                    if($mail!='' && $idProcl!=-1)
                        $this->inviter($idProcl,$id,$mail,$nom);
                }
            }
            /*infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }

            $data['invits'     ] = $this->M_participation->getInvitationByCren($id);
            $this->layout->set_container(1);
            $this->layout->view('participation/creneauProcl', $data);
        }
    }

    public function annulerRP($id){
        if ($this->require_min_level(6)) {
            setlocale(LC_ALL, 'fr_FR');
            $this->load->model('M_creneau');
            $this->load->library('mailing');
            $parts  = $this->M_participation->getPartByCren($id);
            $invits = $this->M_participation->getInvitationNullByCren($id);
            $dateObj=$this->M_creneau->getDate($id);
            $date = utf8_encode(strftime('%A %d %B %Y', strtotime($dateObj->date))).' à '.$dateObj->heure.'h';

            foreach($parts as $part){
                $this->mailing->sendAnnulationMail($part->mail,$part->prenom,$date);
                $this->M_participation->setStatusPart(0,$part->idPart);
            }

            foreach($invits as $invit){
                $this->mailing->sendAnnulationInvitMail($invit->mail,$invit->prenom,$date);
                $this->M_participation->setStatusInv(0,$invit->idInv);
            }

            $this->M_creneau->annuleCrenDate($id);

            redirect(site_url('creneau').'/'.$id);
        }
    }

    public function annulerProcl($idCren,$idUtil){
        if ($this->require_min_level(1)) {
            setlocale(LC_ALL, 'fr_FR');
            $this->load->model('M_creneau');
            $this->load->library('mailing');
            $part  = $this->M_participation->getPartByCrenUtil($idCren,$idUtil);
            $dateObj=$this->M_creneau->getDate($idCren);
            $date = utf8_encode(strftime('%A %d %B %Y', strtotime($dateObj->date))).' à '.$dateObj->heure.'h';
            $rpMail=$this->M_utilisateurs->get($this->M_utilisateurs->getCongRP($idUtil)->idRP)->mail;

            $this->mailing->sendAnnulationMailRP($rpMail,$part->prenom,$date);
            $this->M_participation->setStatusPart(0,$part->idPart);

            redirect(site_url('planning'));
        }
    }

    private function inviter($idProcl, $idCren, $mail,$nom){
        $invitation = new stdClass;
        $invitation->date=date('Y-m-d');
        $invitation->idCren = $idCren  ;
        $invitation->idUtil = $idProcl ;

        $this->M_participation->addInvit($invitation);

        setlocale(LC_ALL, 'fr_FR');
        $dateObj=$this->M_creneau->getDate($idCren);
        $date = utf8_encode(strftime('%A %d %B %Y', strtotime($dateObj->date))).' à '.$dateObj->heure.'h';

        $this->load->library('mailing');
        $this->mailing->sendInvitationMail($mail, $nom,$date);
    }

    private function inviterCren($idCren,$nb){
        $this->load->model('M_creneau');
        $ids = $this->M_creneau->getCongFromCrenDate($idCren);
        foreach($ids as $id){
            $idCong = $id->idCong;
            $procls = $this-> M_utilisateurs->getProclsFromCong($idCong);
            $date_parts = $this->M_utilisateurs->getUtilByLastPartCong($idCong);
            $date_invits = $this->M_utilisateurs->getUtilByLastInvitCong($idCong);

            $score_max = sizeof($procls);
            $cands=array();


            foreach($procls as $procl){
                //echo '</br>Analyse du candidat '.$procl->id.'</br>';
                $trouveP=false;
                $trouveI=false;
                $cands[$procl->id]=0;
                if(sizeof($date_parts)==1 && $date_parts[0]->date=='')
                    $cands[$procl->id]+=$score_max;
                else{
                    $scoreP = 0;
                    foreach($date_parts as $date){
                        if($date->id==$procl->id && !$trouveP){
                            if($date->date==''){
                                //echo 'Dernière participation non datée, jattribue '.$score_max.'</br>';
                                $cands[$procl->id]+=$score_max;
                            }
                            else{
                                //echo 'Dernière participation en date du '.$date->date.', jattribue '.$scoreP.'</br>';
                                $cands[$procl->id]+=$scoreP;

                            }
                            $trouveP=true;
                        }
                        $scoreP++;
                    }
                }
                if(sizeof($date_invits)==1 && $date_invits[0]->date=='')
                    $cands[$procl->id]+=$score_max;
                else{
                    $scoreI = 0;
                    foreach($date_invits as $date){
                        if($date->id==$procl->id && ! $trouveI){
                            if($date->date==''){
                                //echo 'Dernière invitation non datée, jattribue '.($score_max+1).'</br>';
                                $cands[$procl->id]+=$score_max+1;
                            }
                            else{
                                //echo 'Dernière invitation en date du '.$date->date.', jattribue '.$scoreI.'</br>';
                                $cands[$procl->id]+=$scoreI;

                            }
                            $trouveI=true;
                        }
                        $scoreI++;
                    }
                }
                //echo 'Candidat termine avec '.$cands[$procl->id].' </br></br></br>';
            }
            arsort($cands);
            //print_r($cands);
            foreach($cands as $id =>$cand){
                if($nb>0 && ! $this->M_participation->aRefuseInvit($idCren,$id)){
                    $this->sendInvit($id,$idCren);
                    $invit = new stdClass;
                    $invit->idUtil = $id;
                    $invit->idCren = $idCren;
                    $invit->date = date('Y-m-d');
                    $this->M_participation->addInvit($invit);
                    $nb--;
                }
            }
        }
    }

    private function sendInvit($idUtil,$idCren){
        $this->load->library('mailing');
        $user = $this->M_utilisateurs->get($idUtil);
        $cren = $this->M_creneau->getWithDate($idCren);
        setlocale(LC_ALL, 'fr_FR');
        $date = ucfirst(utf8_encode(strftime('%A %d %B %Y',strtotime($cren->date))));
        if($user->id!=5)
            $this->mailing->sendInvitationMail($user->mail,$user->prenom,$date);
    }
}
