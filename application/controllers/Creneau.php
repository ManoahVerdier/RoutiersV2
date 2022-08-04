<?php

class Creneau extends MY_Controller {
    setlocale(LC_ALL, 'fr_FR');

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('layout');
        $this->load->model('M_creneau');
        $this->load->model('M_utilisateurs');
    }


    public function creneauxAssemblee(){
        if ($this->require_min_level(6)) {
            $this->layout->set_container(1);
            $this->layout->set_RP();
<<<<<<< HEAD
            if(isset($_GET['idCong'])){
				$idCong = $_GET['idCong'];
			} else {
				$idCong = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
			}  
            
=======
	if(isset($_GET['idCong'])){
		$idCong = $_GET['idCong'];
	} else {
        	$idCong = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
	}    
>>>>>>> 7ff721c (initial commit)
            
            if($this->input->post()){
                $tabPost=$this->input->post();
                if($tabPost['type']=='delete'){
                    $this->avertirDelete($tabPost['id']);
                    $this->M_creneau->deleteCren($tabPost['id']);
                }
                else if($tabPost['type']=='extend'){
                    $this->M_creneau->addConc($tabPost['id'],$tabPost['partage']);
                }
                else if($tabPost['type']=='delConc'){
                    $this->M_creneau->delConc($tabPost['idCren'],$tabPost['idCong']);
                }
                else{
                    $cren = new stdClass;
                    $cren->jour=$tabPost['jour'];
                    $cren->heure=$tabPost['heure'];
                    $cren->idLieu=$tabPost['lieu'];
                    $cren->nb=$tabPost['nb'];
                    $cren->auto = isset($tabPost['auto']) && $tabPost['auto']=='on'?1:0;
                    $cren->libre = isset($tabPost['libre']) && $tabPost['libre']=='on'?1:0;
                    if($tabPost['type']=='modif'){
                        $oldCren = $this->M_creneau->getLieuxCrenCongById($tabPost['id']);
                        $cren->id=$tabPost['id'];
                        if($oldCren->jour!=$cren->jour ||$oldCren->heure!=$cren->heure ||$oldCren->idLieu!=$cren->idLieu )
                            $this->avertirChangement($tabPost['id'],$cren);
                        $this->M_creneau->majCren($cren);
                    }
                    else{
                        $cren->idCong=$idCong;
                        $idNew=$this->M_creneau->addCren($cren);
                        $this->M_creneau->generateCren($cren,$idNew);
                    }
                }
            }
            $this->load->model('M_congregations');
            $data['congs'] = $this->M_congregations->getAllCong();
            $data['autresCong'] = $this->M_creneau->getAutreCongCren($idCong);
            $data['lieux'] = $this->M_creneau->getLieuxCong($idCong);
            $data['sortie'] = $this->M_creneau->getLieuxCrenCong($idCong);
            $data['idCong'] = $idCong;
            $this->layout->view('creneau/assemblee', $data);
        }
    }
    
    private function avertirDelete($id){
        $this->load->model('M_participation');
        $this->load->library('mailing');
        $old = $this->M_creneau->get($id);
        $creneaux = $this->M_creneau->getDateId($id);
        
        foreach($creneaux as $cren){
            if(! $cren->annule){
                $invits= $this->M_participation->getInvitationByCren($cren->id);
                foreach($invits as $invit){
                    $user=$this->M_utilisateurs->get($invit->idUtil);      
                    $this->mailing->sendSupprMail($user->mail, $cren,'invit');
                }
                $parts = $this->M_participation->getPartByCren($cren->id);
                foreach($parts as $part){
                    $user=$this->M_utilisateurs->get($part->idUtil);
                    $this->mailing->sendSupprMail($user->mail, $cren,'part');
                }
            }
        }
    }
    
    private function avertirChangement($id,$new){
        $this->load->model('M_participation');
        $this->load->library('mailing');
        $creneaux = $this->M_creneau->getDateId($id);
        $old = $this->M_creneau->get($id);
        $oldLieu = $this->M_creneau->getLieu($old->idLieu)->intitule;
        $newLieu = $this->M_creneau->getLieu($new->idLieu)->intitule;
        foreach($creneaux as $cren){
            if(! $cren->annule){
                $weekDayOld = date('N', strtotime($cren->date));
                $weekDayNew = $new->jour;
                $diff=($weekDayNew-$weekDayOld)>=0?'+'.$weekDayNew-$weekDayOld:$weekDayNew-$weekDayOld;
                $newDate=date('Y-m-d',strtotime($cren->date.' '.$diff.' days'));
                $newCren=new stdClass;
                $newCren->date = $newDate;
                $newCren->heure= $new->heure;
                $invits= $this->M_participation->getInvitationByCren($cren->id);
                foreach($invits as $invit){
                    $user=$this->M_utilisateurs->get($invit->idUtil);      
                    $this->mailing->sendAvertMailInv($user->mail, $cren, $newCren,$oldLieu,$newLieu);
                }
                $parts = $this->M_participation->getPartByCren($cren->id);
                foreach($parts as $part){
                    $user=$this->M_utilisateurs->get($part->idUtil);
                    $this->mailing->sendAvertMailPart($user->mail, $cren, $newCren,$oldLieu,$newLieu);
                }
                $this->M_creneau->majCrenJour($cren->id,$newCren);
            }
        }
    }
    
    public function gestionLieux(){
        /* Connexion requise + Admin */
        if ($this->require_min_level(9)) {
            if($this->input->get())
            {
                $type=$this->input->get()['type'];
                if($type=='suppr'){
                    $id  =$this->input->get()['id'];
                    $this->M_creneau->deleteLieu($id);
                }
                else if($type=='maj'){
                    $id  =$this->input->get()['id'];
                    $lieu = new stdClass;
                    $lieu->intitule = $this->input->get()['intitule'];
                    $lieu->adresse = $this->input->get()['adresse'];
                    $lieu->id = $id;
                    $this->M_creneau->majLieu($lieu);
                }
                else if($type=='new'){
                    $lieu = new stdClass;
                    $lieu->intitule = $this->input->get()['intitule'];
                    $lieu->adresse = $this->input->get()['adresse'];
                    $this->M_creneau->addLieu($lieu);
                }
            }
            $data['lieux'] = $this->M_creneau->getAllLieux();
            $this->layout->set_container(1);
            $this->layout->set_Admin();
            $this->layout->ajouter_js('bootstrap-confirmation');
            $this->layout->view('creneau/gestLieux', $data);
        }
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 7ff721c (initial commit)
