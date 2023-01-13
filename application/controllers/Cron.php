<?php

class Cron extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('layout');
        $this->load->model('M_creneau');
        $this->load->model('M_utilisateurs');
        $this->load->model('M_participation');
    }
    
    public function lancerInvitation(){
        $crens=$this->M_creneau->getAllCrenDateAuto();
        
        foreach($crens as $cren){
            if($cren->date>date('Y-m-d') && $cren->date<=date('Y-m-d',strtotime(date('Y-m-d').' +1 month'))){
                $parts = $this->M_participation->getPartByCren($cren->idDate);
                $nbPart = sizeof($parts);
        
                if($nbPart<$cren->nb){
                    $invits = $this->M_participation->getInvitationValByCren($cren->idDate);
                    $nbInvit=sizeof($invits);

                    $nbTot = $nbPart+$nbInvit;
                }
                else{
                    $nbTot = $nbPart;
                }
                if($nbTot<$cren->nb){
                    $this->inviterCren($cren->idDate,$cren->nb-$nbTot);
                }
            }
        }
    }
    
    public function inviterCren($idCren,$nb){
        $ids = $this->M_creneau->getCongFromCrenDate($idCren);
        $procls = array();
        $date_parts = array();
        $date_invits = array();
        
        
        foreach($ids AS $id){
            $idCong = $id->idCong;
        
            $procls = array_merge($this-> M_utilisateurs->getProclsFromCong($idCong),$procls);
            $date_parts = array_merge($this->M_utilisateurs->getUtilByLastPartCong($idCong),$date_parts);
            $date_invits = array_merge($this->M_utilisateurs->getUtilByLastInvitCong($idCong),$date_invits);
        }
        
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
            
            arsort($cands);
            //print_r($cands);
            foreach($cands as $id =>$cand){
                if($nb>0 && ! $this->M_participation->aRefuseInvit($idCren,$id)){
                    echo $id.'-'.$idCren;
                    print_r($cand);
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
    
    public function addCren(){
        $crens=$this->M_creneau->getAllCren();
        foreach($crens as $cren){
            $this->M_creneau->addOneCren($cren,$cren->id);
        }

    }
    
    public function sendReport(){
        $this->load->library('mailing');
        $parts=$this->M_participation->getPartsByMonth(date('m'));
        //$parts=$this->M_participation->getPartsByMonth(03);
        $strTable = "<table class='table'><tr><th>Assemblées</th><th>Participations</th><th>Rapports</th><th>Contacts</th><th>Publications</th></tr>";
        foreach($parts as $part){
            $strTable .="<tr><td>$part->nom</td><td>$part->nb</td><td>$part->nbRap</td><td>$part->nbRenc</td><td>$part->nbPub</td></tr>";
        }
        $strTable.="</table>";
        $mail = $this->M_utilisateurs->getAdminMail();
        
        $this->mailing->sendMonthReportMail($mail,$strTable);
    }
    
}