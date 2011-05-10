<?php

// -----------------------------------------------
// Cryptographp v1.4
// (c) 2006-2007 Sylvain BRISON 
//
// www.cryptographp.com 
// cryptographp@alphpa.com 
//
// Licence CeCILL modifiée
// => Voir fichier Licence_CeCILL_V2-fr.txt)
// -----------------------------------------------

 //if(session_id() == "") session_start();
 
 pwg_set_session_var('cryptdir', dirname($cryptinstall));
 
 
 function dsp_crypt($cfg=0,$reload=1) {
 // Affiche le cryptogramme
 $dir = pwg_get_session_var('cryptdir');
 $out = "<img id='cryptogram' src='".$dir."/cryptographp.php?cfg=".$cfg."&".SID."'>";
 if ($reload) $out .= "&nbsp;<a title='".($reload==1?'':$reload)."' style=\"cursor:pointer;vertical-align:top;\" onclick=\"javascript:document.images.cryptogram.src='".$dir."/cryptographp.php?cfg=".$cfg."&".SID."&'+Math.round(Math.random(0)*1000)+1\"><img src=\"".$dir."/images/reload.png\"></a>";
 return $out;
 }


 function chk_crypt($code) {
 // Vérifie si le code est correct
 include (pwg_get_session_var('configfile'));
 $code = addslashes ($code);
 $code = str_replace(' ','',$code);  // supprime les espaces saisis par erreur.
 $code = ($difuplow?$code:strtoupper($code));
 switch (strtoupper($cryptsecure)) {    
        case "MD5"  : $code = md5($code); break;
        case "SHA1" : $code = sha1($code); break;
        }
 if (pwg_get_session_var('cryptcode') and (pwg_get_session_var('cryptcode') == $code))
    {
    pwg_unset_session_var('cryptreload');
    if ($cryptoneuse) pwg_unset_session_var('cryptcode');    
    return true;
    }
    else {
         pwg_set_session_var('cryptreload', true);
         return false;
         }
 }

?>
