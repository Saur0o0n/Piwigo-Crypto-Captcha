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

// fichiers modifiés pour utiliser la session ouverte par Piwigo

define('PHPWG_ROOT_PATH','../../../');
include_once( PHPWG_ROOT_PATH.'include/common.inc.php' );

error_reporting(E_ALL ^ E_NOTICE);
pwg_set_cookie_var("cryptcookietest", "1");
Header("Location: cryptographp.inc.php?cfg=".$_GET['cfg']."&sn=".session_name()."&".SID);
?>
