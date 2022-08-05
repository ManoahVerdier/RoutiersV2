<?php

class Mailing {

    public function __construct()
    {
        $this->CI =& get_instance();

        //Chargement de la configuration par défaut des mails
        $this->CI->config->load('mailing');
        //Chargement de la bibliothèque d'envoi de mail
        $this->CI->load->library('email');
    }
    /**
     * Envoi d'un mail avec lien d'inscription et code
     * @param string $mail
     * @param string $code
     * @param string $link
     * @param string $nom
     */
    function sendInvitLink($mail, $code, $link, $nom = '') {
        

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        ;

        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject('Invitation à participer au témoignage aux routiers');
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Tu as été invité par ton responsable prédication à participer au témoignage aux routiers. Pour cela, merci de t'inscrire en cliquant sur le lien suivant : </td></tr>
                 <tr><td>&nbsp;</td></tr>
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Inscription</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>Note qu' un code te sera demandé. Le voici : </td></tr>
                 <tr><td>&nbsp;</td></tr>

                 " . '<table width="180" border="0" cellpadding="0" cellspacing="0" class="">
                      <tbody>
                        <tr>
                          <td align="left">
                            <table border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                                <tr>
                                  <td style="font-size: 30px;font-weight: bold;padding: 10px;background: #E6EAED;text-align: center;border-radius: 5px;border: 1px solid #D7DCE0;line-height:30px;"> <span class="appleLinks">
                                    ' . $code . "
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>

                 </td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Note que ce lien n'est valable qu'une semaine. Nous comptons sur ta réactivité !</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);

        $this->CI->email->send();
    }

    function sendAnnulationMail($mail, $nom = '', $date) {

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        ;

        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Annulation d'un créneau");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Malheureusement, le créneau du $date est <b>annulé</b>. N'hésite pas à te rapprocher de ton responsable prédication pour plus d'informations.</td></tr>
                 <tr><td>&nbsp;</td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);
        
        $this->CI->email->send();
    }
    
    function sendAnnulationMailRP($mail, $nom = '', $date) {

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        ;

        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Annulation d'une participation");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Malheureusement, $nom a annulé sa participation du $date. N'hésite pas à te rendre sur la page de ce créneau afin de consulter la liste des participants.</td></tr>
                 <tr><td>&nbsp;</td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);
        
        $this->CI->email->send();
    }
    
    function sendAnnulationInvitMail($mail, $nom = '', $date) {

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        ;

        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Annulation d'une invitation");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Malheureusement, le créneau du $date est <b>annulé</b>. Il est donc inutile de répondre à l'invitation qui t'avais été envoyée. </td></tr>
                 <tr><td>&nbsp;</td></tr>                 
                 <tr><td>N'hésite pas à te rapprocher de ton responsable prédication pour plus d'informations.</td></tr>
                 <tr><td>&nbsp;</td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);

        $this->CI->email->send();
    }
    
    function sendInvitationMail($mail, $nom = '', $date) {

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        

        $link = site_url('planning');
        
        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Invitation reçue");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Tu as reçu une invitation pour le $date. Tu peux l'accepter ou la refuser sur ta page 'planning'. </td></tr>
                 <tr><td>&nbsp;</td></tr>                 
                 <tr><td>Merci de le faire au plus vite ! N'hésite pas à te rapprocher de ton responsable prédication pour plus d'informations.</td></tr>
                 <tr><td>&nbsp;</td></tr>
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Voir mes invitations</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);

        $this->CI->email->send();
    }
    
    function sendRelanceMail($mail, $nom = '', $date) {

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        ;

        $link = site_url('rapport');
        
        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Rapport non complété");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Ta participation du $date n'a pas encore fait l'objet d'un rapport. Merci de le faire au plus vite ! </td></tr>
                 <tr><td>&nbsp;</td></tr>                 
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Mes rapports</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);

        $this->CI->email->send();
    }
    
    public function sendAvertMailInv($mail, $old, $new,$oldLieu,$newLieu){
        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        
        setlocale(LC_ALL, 'fr_FR');
        $dateOld = utf8_encode(ucfirst(strftime('%A %e %B %Y',strtotime($old->date))));
        $heureOld = $old->heure;
        
        $dateNew = utf8_encode(ucfirst(strftime('%A %e %B %Y',strtotime($new->date))));
        $heureNew = $new->heure;
        
        $link = site_url('planning');
        
        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Information importante concernant une invitation");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Tu as récemment reçu une invitation pour le $dateOld à $heureOld heures ($oldLieu). Veuille noter que ce créneau a été modifié. Voici les nouvelles informations : </td></tr>
                 <tr><td>&nbsp;</td></tr>                 
                 <tr><td><b>$dateNew à $heureNew heures ($newLieu)</b></td></tr>
                 <tr><td>&nbsp;</td></tr>                 
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Mes invitations</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);

        $this->CI->email->send();
    }
    
    public function sendAvertMailPart($mail, $old, $new,$oldLieu,$newLieu){
        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        
        setlocale(LC_ALL, 'fr_FR');
        $dateOld = utf8_encode(ucfirst(strftime('%A %e %B %Y',strtotime($old->date))));
        $heureOld = $old->heure;
        
        $dateNew = utf8_encode(ucfirst(strftime('%A %e %B %Y',strtotime($new->date))));
        $heureNew = $new->heure;
        
        $link = site_url('planning');
        
        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Information importante concernant une participation");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Tu es prévu pour participer à l'activité du $dateOld à $heureOld heures $oldLieu). Veuille noter que ce créneau a été modifié. Voici les nouvelles informations : </td></tr>
                 <tr><td>&nbsp;</td></tr>                 
                 <tr><td><b>$dateNew à $heureNew heures ($newLieu)</b></td></tr>
                 <tr><td>&nbsp;</td></tr>                 
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Mes invitations</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);

        $this->CI->email->send();
    }
    
     public function sendSupprMail($mail,$cren,$type){
         //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        
        setlocale(LC_ALL, 'fr_FR');
        $dateOld = utf8_encode(ucfirst(strftime('%A %e %B %Y',strtotime($cren->date))));
        $heureOld = $cren->heure;
        
        $msg=($type=='invit')?' auquel tu as été invité':'auquel tu devais participer';
        
        $link = site_url('planning');
        
        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Supression d'un créneau");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Le créneau du $dateOld à $heureOld heures, $msg, a été supprimé. 
                 <tr><td>&nbsp;</td></tr>                 
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Mes invitations</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);

        $this->CI->email->send();
     }
     
     public function sendMonthReportMail($mail,$table){
         //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->CI->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->CI->config->item('default_head');
        $footerHTML = $this->CI->config->item('default_footer');
        
        setlocale(LC_ALL, 'fr_FR');
        
        $link = site_url('activiteAdmin');
        
        $this->CI->email->initialize($config);

        $this->CI->email->from('support@verdier-developpement.com', 'Routiers');
        $this->CI->email->to($mail);

        $this->CI->email->subject("Rapport mensuel d'activité");
        $this->CI->email->message($headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:70%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Voici le rapport mensuel de l'activité déployée auprès des routiers. 
                 <tr><td>&nbsp;</td></tr>                 
                 <tr><td>$table</td></tr>                 
                 <tr><td>&nbsp;</td></tr>    
                 <tr><td>Note que lorsqu'un créneau concerne plusieurs assemblées, il est compté pour l'assemblée qui l'administre. 
                 <tr><td>&nbsp;</td></tr>    
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Plus de détail dans l'espace administrateur</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML);
        echo $headerHTML .
                '<table border="0" style="width:100%">
              <tr>
                <td style="width:70%"><h1>Témoignage aux routiers</h1></td>
              <tr>    
             </table>        
             <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour,</td></tr>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Voici le rapport mensuel de l'activité déployée auprès des routiers. 
                 <tr><td>&nbsp;</td></tr>                 
                 <tr><td>$table</td></tr>                 
             </table>
             <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                 <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Plus de détail dans l'espace administrateur</a></span></td></tr></tbody></table></td></tr>
             </table>
             <table border='0' cellpadding='0' cellspacing='0'>
                 <tr><td>&nbsp;</td></tr>
                 <tr><td>Bonne journée !</td></tr>
                 <tr><td>L'équipe des routiers</td></tr>
                 </tbody>
                 </table>
                 </td>
                 </tr>
              </table>" .
                $footerHTML;

        $this->CI->email->send();
     }
}