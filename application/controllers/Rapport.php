<?php

class Rapport extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('layout');
        $this->load->model('M_utilisateurs');
        $this->load->model('M_participation');
        $this->load->model('M_rapport');
    }
    
    public function mesRapports(){
        if ($this->require_min_level(1)) {
            $parts=$this->M_participation->getPastPartCompleteByUser($this->auth_user_id);
            $misses=false;
            $raps = array();
            foreach($parts as $part){
                $rap = new stdClass;
                if($this->M_rapport->isReportMissingByCren($part->idCren)){
                    $rap->missing=1;
                    $rap->idCren=$part->idCren;
                    $rap->date=$part->date;
                    $misses=true;
                }
                else{
                    $rap->missing=0;
                    $rap->idRap=$this->M_rapport->getIdByCren($part->idCren)->id;
                    $rap->date=$part->date;
                }
                $raps[]=$rap;
            }
            $data['parts']=$raps;
            $data['misses']=$misses;
            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $this->layout->set_container(1);
            $this->layout->view('rapport/aFaire',$data);
        }
    }
    
    public function rapportsAssemblee(){
        if ($this->require_min_level(6)) {
            $idCong = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $parts=$this->M_participation->getPastPartCompleteByCong($idCong);
            $partsWithNoCren = $this->M_participation->getPastRapNoCrenByCong($idCong);
            $misses=false;
            $raps = array();
            foreach($parts as $part){
                $rap = new stdClass;
                if($this->M_rapport->isReportMissingByCren($part->id)){
                    $rap->missing=1;
                    $rap->idCren=$part->id;
                    $rap->date=$part->date;
                    if(strtotime($part->date)> strtotime("-90 days")){
                        $misses=true;
                    }
                }
                else{
                    $rap->missing=0;
                    $rap->idRap=$this->M_rapport->getIdByCren($part->id)->id;
                    $rap->date=$part->date;
                }
                $raps[]=$rap;
            }
            
            foreach($partsWithNoCren as $part){
                $rap = new stdClass;
                $rap->missing=0;
                
                $rap->idRap=$this->M_rapport->getIdByCren($part->id)->id;
                $rap->date=$part->date;
                $raps[]=$rap;
            }
            
            usort($raps,array($this,"sortParts"));
            
            $data['parts']=$raps;
            $data['misses']=$misses;
            $data['relance']=false;
            if($this->input->post()) {
                if(isset($this->input->post()['type']) && $this->input->post()['type']="annuler"){
                    $this->annulerApres($this->input->post()['idCren']);
                } else {
                    $this->relancer($this->input->post()['idCren']);
                    $data['relance']=true;
                }
            }
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('rapport/aFaireAssemblee',$data);
        }
    }
    


    private function sortParts($a, $b)
    {
        return strtotime($b->date)-strtotime($a->date);
    }
    
    public function rapportRP(){
        if ($this->require_min_level(6)) {
            $this->load->model('M_creneau');
            $idCong = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $parts=$this->M_participation->getPastPartCompleteByCong($idCong);
            $raps = array();
            foreach($parts as $part){
                $cren=$this->M_creneau->get($part->idCreneau);
                $rap = new stdClass;
                if($this->M_rapport->isReportMissingByCren($part->id) && is_object($cren)){
                    print_r($cren);
                    $rap->missing=1;
                    $rap->idCren=$part->id;
                    $rap->date=$part->date;
                    $rap->heure=$cren->heure;
                    $raps[]=$rap;
                }
            }
            if($this->input->post()) {
                if(isset($this->input->post()['idCren'])){
                    redirect(base_url().'Rapport/ajouter/'.$this->input->post()['idCren'].'/true');
                }
                else{
                    
                    $cren=new stdClass;
                    $cren->heure=$this->input->post()['heure'];
                    $cren->date=$this->input->post()['date'];
                    $cren->idCongSeul=$idCong;
                    $cren->idCreneau=-1;
                    $cren->annule=0;
                    $idCren= $this->M_creneau->addCrenDate($cren);
                    $part = new stdClass;
                    $part->idCren = $idCren;
                    $part->idUtil = -1;
                    $part->status = 1;
                    $this->M_participation->addPart($part);
                    redirect(base_url().'Rapport/ajouter/'.$idCren.'/true');
                }
            }
            if($this->session->flashdata())
               $data['message']=$this->session->flashdata()['message'];
            $data['parts']=$raps;
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('rapport/rapportRP',$data);
        }
    }
    
    public function ajouter($idCren,$redirection=false){
        if($idCren == NULL || $idCren=='')
            show_404();
        if ($this->require_min_level(1)) {
            $data['langs']=$this->M_rapport->getAllLang();
            $data['pubs']=$this->M_rapport->getAllPub();
            $data['populate']=true;
            if(isset($this->input->post()['nbLng']))
                       $data['nbLng']=$this->input->post()['nbLng'];
            else
                $data['nbLng']=1;
            
            $data['idRap']=(isset($this->input->post()['idRap']) && $this->input->post()['idRap']!='')?$this->input->post()['idRap']:-1;
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->layout->set_container(1);
            if ($this->input->post()) {
                $data['nbPub']=0;
                $data['nbFait']=0;
                
                $this->form_validation->set_rules('langue', 'Langue', 'required');
                $this->form_validation->set_rules('nbRenc', 'Nombre de personnes rencontrées', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('qte1' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte2' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte3' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte4' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte5' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte6' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte7' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte8' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte9' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte10', 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('pub1' , 'Choix de la publication', 'is_natural_no_zero', array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub2' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub3' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub4' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub5' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub6' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub7' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub8' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub9' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub10', 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                
                
                
                if($this->form_validation->run() == false)
                {
                   if(isset($this->input->post()['nbPub']))
                       $data['nbPub']=$this->input->post()['nbPub'];
                   
                   if(isset($this->input->post()['nbFait']))
                       $data['nbFait']=$this->input->post()['nbFait'];
                   /*Erreur ici*/
                   /*Infos urgentes ?*/
                    $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
                    $data['urgent']=0;
                    foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                        if($info->urgent && $info->actif=='on')
                            $data['urgent']=1;
                    }
                   $this->layout->view('rapport/ajouter', $data);
                }
                else
                {
                    if($data['idRap']==-1)
                        $data['idRap']=$this->ajouterRapport($idCren);
                    else
                        $this->ajouterRapport($idCren,$data['idRap']);
                    if($_POST['fin']=='fin'){
                        $data['message'] = "Rapport envoyé !";
                        if($redirection){
                            $this->session->set_flashdata('message', 'Rapport envoyé !');
                            redirect(base_url().'Rapport/rapportRP');
                        }
                        else 
                            redirect('rapport');
                    }
                    else{
                        $data['populate']=false;
                        $data['nbLng']++;
                        /*Infos urgentes ?*/
                        $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
                        $data['urgent']=0;
                        foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                            if($info->urgent && $info->actif=='on')
                                $data['urgent']=1;
                        }
                        
                        $this->layout->view('rapport/ajouter', $data);
                    }
                }
                
             }else{
                 /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
                $this->layout->view('rapport/ajouter',$data);
             }
        }
    }
    
    public function consulter($idRap){
        
        if ($this->require_min_level(1)) {
            if($this->require_min_level(6))
                redirect('Rapport/consulterRP/'.$idRap);
            if(! $this->isOKUtil($idRap) && !$this->require_min_level(6))
                show_404();
            
            $data['faits']=$this->M_rapport->getFaits($idRap);
            $data['placs']=$this->M_rapport->getPlacs($idRap);
            $data['rencs']=$this->M_rapport->getRencs($idRap);
            $data['part'] =$this->M_rapport->getPart($idRap);
            $data['idRap']=$idRap;
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $this->layout->set_container(1);
                $this->layout->view('rapport/consulter',$data);
        }
    }
    
    public function supprLng($idRap,$idLang){
        if ($this->require_min_level(1)) {
            if(! $this->isOKUtil($idRap)&& !$this->require_min_level(6))
                show_404();
            $this->M_rapport->deleteRapportLng($idRap,$idLang);
            if($this->require_min_level(6))
                redirect('Rapport/consulterRP/'.$idRap);
            else
                redirect('Rapport/consulter/'.$idRap);
        }
    }
    
    public function modifier($idRap,$idLang){
        if ($this->require_min_level(1)) {
            if(! $this->isOKUtil($idRap))
                show_404();
            $data['faits']=$this->M_rapport->getFaitsLng($idRap,$idLang);
            $data['placs']=$this->M_rapport->getPlacsLng($idRap,$idLang);
            $rencs=$this->M_rapport->getRencs($idRap);
            $this->load->helper('form');
            $this->load->library('form_validation');
            $data['lngNom']='';
            foreach($rencs as $renc){
                if($renc->idLang==$idLang){
                    $data['lngNom']=$renc->intitule;
                    $data['renc']=$renc;
                }
            }
            
            $data['part'] =$this->M_rapport->getPart($idRap);
            $data['idLang']=$idLang;
            $data['idRap']=$idRap;
            
            $data['langs']=$this->M_rapport->getAllLang();
            $data['pubs']=$this->M_rapport->getAllPub();
            $data['populate']=true;
            if(isset($this->input->post()['nbLng']))
                       $data['nbLng']=$this->input->post()['nbLng'];
            else
                $data['nbLng']=1;
            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            if ($this->input->post()) {
                $data['nbPub']=0;
                $data['nbFait']=0;
                
                $this->form_validation->set_rules('langue', 'Langue', 'required');
                $this->form_validation->set_rules('nbRenc', 'Nombre de personnes rencontrées', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('qte1' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte2' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte3' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte4' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte5' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte6' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte7' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte8' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte9' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte10', 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('pub1' , 'Choix de la publication', 'is_natural_no_zero', array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub2' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub3' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub4' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub5' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub6' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub7' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub8' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub9' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub10', 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                
                
                
                if($this->form_validation->run() == false)
                {
                   if(isset($this->input->post()['nbPub']))
                       $data['nbPub']=$this->input->post()['nbPub'];
                   
                   if(isset($this->input->post()['nbFait']))
                       $data['nbFait']=$this->input->post()['nbFait'];
                   /*Erreur ici*/
                   /*Infos urgentes ?*/
                    $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
                    $data['urgent']=0;
                    foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                        if($info->urgent && $info->actif=='on')
                            $data['urgent']=1;
                    }
                   $this->layout->view('rapport/modifier', $data);
                }
                else
                {
                    $this->M_rapport->deleteRapportLng($idRap,$idLang);
                    $this->ajouterRapport($this->M_rapport->getCren($idRap),$idRap);
                    redirect(base_url().'Rapport/consulter/'.$idRap);
                }
                
             }
            
            
            $this->layout->set_container(1);
            $this->layout->view('rapport/modifier',$data);
        }
    }
    
    public function ajoutLangueExistant($idRap){
        if ($this->require_min_level(1)) {
            if(! $this->isOKUtil($idRap) && !$this->require_min_level(6))
                show_404();
            $this->load->helper('form');
            $this->load->library('form_validation');
            $data['idRap']=$idRap;
            $data['part'] =$this->M_rapport->getPart($idRap);
            $data['langs']=$this->M_rapport->getAllLang();
            $data['pubs']=$this->M_rapport->getAllPub();
            $data['populate']=true;
            if(isset($this->input->post()['nbLng']))
                    $data['nbLng']=$this->input->post()['nbLng'];
            else
                $data['nbLng']=1;
            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            if ($this->input->post()) {
                $data['nbPub']=0;
                $data['nbFait']=0;
                
                $this->form_validation->set_rules('langue', 'Langue', 'required');
                $this->form_validation->set_rules('nbRenc', 'Nombre de personnes rencontrées', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('qte1' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte2' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte3' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte4' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte5' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte6' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte7' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte8' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte9' , 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('qte10', 'Quantité', 'is_natural_no_zero');
                $this->form_validation->set_rules('pub1' , 'Choix de la publication', 'is_natural_no_zero', array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub2' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub3' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub4' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub5' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub6' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub7' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub8' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub9' , 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                $this->form_validation->set_rules('pub10', 'Choix de la publication', 'is_natural_no_zero',array('is_natural_no_zero' =>'Merci de choisir une publication'));
                
                
                
                if($this->form_validation->run() == false)
                {
                   if(isset($this->input->post()['nbPub']))
                       $data['nbPub']=$this->input->post()['nbPub'];
                   
                   if(isset($this->input->post()['nbFait']))
                       $data['nbFait']=$this->input->post()['nbFait'];
                   /*Erreur ici*/
                   /*Infos urgentes ?*/
                    $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
                    $data['urgent']=0;
                    foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                        if($info->urgent && $info->actif=='on')
                            $data['urgent']=1;
                    }
                   $this->layout->view('rapport/ajoutLangue', $data);
                }
                else
                {
                    $this->ajouterRapport($this->M_rapport->getCren($idRap),$idRap);
                    redirect(base_url().'Rapport/consulter/'.$idRap);
                }
                
             }
            
            
            $this->layout->set_container(1);
            $this->layout->view('rapport/ajoutLangue',$data);
        }
    }
    
    public function consulterRP($idRap){
        
        if ($this->require_min_level(6)) {
            $data['faits']=$this->M_rapport->getFaits($idRap);
            $data['placs']=$this->M_rapport->getPlacs($idRap);
            $data['rencs']=$this->M_rapport->getRencs($idRap);
            $data['part'] =$this->M_rapport->getPart($idRap);
            $data['cren'] =$this->M_rapport->getCren($idRap);
            $data['idRap']=$idRap;
            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('rapport/consulter',$data);
        }
    }
    
    private function isOKUtil($idRap){
        $utils = $this->M_rapport->getUtils($idRap);
        
        foreach($utils as $util){
            if($util->idUtil==$this->auth_user_id)
                return true;
        }
        return false;
    }
    
    private function ajouterRapport($idCren,$idRap=""){
        if($idRap==""){
            $rap=new stdClass;
            $rap->idCren=$idCren;
            $idRap = $this->M_rapport->addRap($rap);
        }
        
        $renc = new stdClass;
        $renc->idRap=$idRap;
        $renc->idLang=$this->input->post()['langue'];
        $renc->nb=$this->input->post()['nbRenc'];
        
        $this->M_rapport->addRenc($renc);
        
        for($cpt = 0;$cpt<10;$cpt++){
            if(isset($this->input->post()['pub'.$cpt]) && isset($this->input->post()['qte'.$cpt]))
            {
                $pub = new stdClass;
                $pub->idLang=$this->input->post()['langue'];
                $pub->idPub = $this->input->post()['pub'.$cpt];
                $pub->idRap = $idRap;
                $pub->qte   = $this->input->post()['qte'.$cpt];
                $this->M_rapport->addPlac($pub);
            }
        }
        
        for($cpt = 0;$cpt<10;$cpt++){
            if(isset($this->input->post()['fait'.$cpt]))
            {
                $fait = new stdClass;
                $fait->idLang=$this->input->post()['langue'];
                $fait->fait = $this->input->post()['fait'.$cpt];
                $fait->idRap = $idRap;
                $this->M_rapport->addFait($fait);
            }
        }
        
        return $idRap;
    }
    
    private function ajouterRencontre($idRap,$idLang,$nb){
        if($idRap==""){
            $rap=new stdClass;
            $rap->idCren=$idCren;
            $idRap = $this->M_rapport->addRap($rap);
        }
        
        print_r($this->input->post());
        $renc = new stdClass;
        $renc->idRap=$idRap;
        $renc->idLang=$this->input->post()['langue'];
        $renc->nb=$this->input->post()['nbRenc'];
        
        $this->M_rapport->addRenc($renc);
    }
    
    private function relancer($idCren){
        setlocale(LC_ALL, 'fr_FR');
        $parts=$this->M_participation->getPartByCren($idCren);
        $this->load->model('M_creneau');
        $dateObj=$this->M_creneau->getDate($idCren);
        $date = utf8_encode(strftime('%A %d %B %Y', strtotime($dateObj->date))).' à '.$dateObj->heure.'h';
        $this->load->library('mailing');
        foreach($parts as $part){
            $this->mailing->sendRelanceMail($part->mail,$part->prenom,$date);
        }
    }

    public function annulerApres($idCren){
        $this->load->model('M_creneau');
        $this->M_creneau->annuleCrenDate($idCren);
    }
    
    public function gestionPubs(){
        /* Connexion requise + Admin */
        if ($this->require_min_level(9)) {
            if($this->input->get())
            {
                $objet=$this->input->get()['objet'];
                $type=$this->input->get()['type'];
                if($objet=='langue'){
                    if($type=='suppr'){
                        $id  =$this->input->get()['id'];
                        $this->M_rapport->deleteLang($id);
                    }
                    else if($type=='maj'){
                        $id  =$this->input->get()['id'];
                        $pub = new stdClass;
                        $pub->intitule = $this->input->get()['intitule'];
                        $pub->id = $id;
                        $this->M_rapport->majLang($pub,$id);
                    }
                    else if($type=='new'){
                        $pub = new stdClass;
                        $pub->intitule = $this->input->get()['intitule'];
                        $this->M_rapport->addLang($pub);
                    }
                }else if($objet=='pub'){
                    if($type=='suppr'){
                        $id  =$this->input->get()['id'];
                        $this->M_rapport->deletePub($id);
                    }
                    else if($type=='maj'){
                        $id  =$this->input->get()['id'];
                        $pub = new stdClass;
                        $pub->titre = $this->input->get()['titre'];
                        $pub->type = $this->input->get()['titrePub'];
                        $pub->id = $id;
                        $this->M_rapport->majPub($pub,$id);
                    }
                    else if($type=='new'){
                        $pub = new stdClass;
                        $pub->titre = $this->input->get()['titre'];
                        $pub->type = $this->input->get()['typePub'];
                        $this->M_rapport->addPub($pub);
                    }
                }
            }
            $data['pubs' ] = $this->M_rapport->getAllPub() ;
            $data['langs'] = $this->M_rapport->getAllLang();
            $this->layout->set_container(1);
            $this->layout->set_Admin();
            $this->layout->ajouter_js('bootstrap-confirmation');
            $this->layout->view('rapport/gestPubs', $data);
        }
    }
}