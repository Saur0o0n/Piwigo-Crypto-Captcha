<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

add_event_handler('loc_end_page_header', 'add_crypto');
add_event_handler('register_user_check', 'check_crypto');

function add_crypto()
{
  global $template;

  $template->set_prefilter('register', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  global $conf;
  
  $search = '#\<\/ul\>(.{0,10})\<\/fieldset\>(.{0,10})\<p class\=\"bottomButtons\"\>#is';
  $replace = '
      <li>
        <span class="property">
          <label><img id="captcha" src="'.CRYPTO_PATH.'securimage/securimage_show.php" alt="CAPTCHA Image"></label>
        </span>
        <b>{\''.($conf['cryptographp']['captcha_type']=='string'?'Enter code':'Solve equation').'\'|@translate} :</b><br>
        <input type="text" name="captcha_code" size="'.($conf['cryptographp']['code_length']+1).'" maxlength="'.$conf['cryptographp']['code_length'].'" />
        <a href="#" onclick="document.getElementById(\'captcha\').src = \''.CRYPTO_PATH.'securimage/securimage_show.php?\' + Math.random(); return false">
          <img src="'.CRYPTO_PATH.'template/refresh.png"></a>
      </li>
  </ul>

  </fieldset>

  <p class="bottomButtons">';

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