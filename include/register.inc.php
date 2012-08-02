<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

load_language('plugin.lang', CRYPTO_PATH);
add_event_handler('loc_end_page_header', 'add_crypto');
add_event_handler('register_user_check', 'check_crypto');

function add_crypto()
{
  global $template;

  $template->set_prefilter('register', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  global $conf, $user;
    
  $search = '#\(\{\'useful when password forgotten\'\|@translate\}\)(\s*)((\{/if\})?)#i';
  $replace = '({\'useful when password forgotten\'|@translate})$1$2
      </li>
      <li>
        <span class="property">
          <label for="captcha_code">{\''.($conf['cryptographp']['captcha_type']=='string'?'Enter code':'Solve equation').'\'|@translate} <img id="captcha" src="'.CRYPTO_PATH.'securimage/securimage_show.php" alt="CAPTCHA Image"></label>
          <a href="#" onclick="document.getElementById(\'captcha\').src = \'{$ROOT_URL}'.CRYPTO_PATH.'securimage/securimage_show.php?\' + Math.random(); return false">
            <img src="{$ROOT_URL}'.CRYPTO_PATH.'template/refresh.png"></a>
        </span>
        <input type="text" id="captcha_code" name="captcha_code" size="'.($conf['cryptographp']['code_length']+1).'" maxlength="'.$conf['cryptographp']['code_length'].'" />';

  return preg_replace($search, $replace, $content);
}

function check_crypto($errors)
{
  include_once(CRYPTO_PATH.'securimage/securimage.php');
  $securimage = new Securimage();
  
  if ($securimage->check($_POST['captcha_code']) == false)
  {
    array_push($errors, l10n('Invalid Captcha'));
  }

  return $errors;
}

?>