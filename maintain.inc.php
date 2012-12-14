<?php
define('crypto_default_config', serialize(array(
  'activate_on'     => array(
        'picture'     => true,
        'category'    => true,
        'register'    => true,
        'contactform' => true,
        'guestbook'   => true,
        ),
  'comments_action' => 'reject',
  'theme'           => 'gray',
  'captcha_type'    => 'string',
  'case_sensitive'  => 'false',
  'code_length'     => 6,
  'width'           => 180, 
  'height'          => 70,
  'perturbation'    => 1, 
  'image_bg_color'  => 'ffffff', 
  'text_color'      => '8a8a8a', 
  'num_lines'       => 2, 
  'line_color'      => '8a8a8a', 
  'noise_level'     => 0.1, 
  'noise_color'     => '8a8a8a', 
  'ttf_file'        => 'TopSecret',
  'button_color'    => 'dark',
)));

function plugin_install()
{
  global $conf;
  
  conf_update_param('cryptographp', crypto_default_config);
}

function plugin_activate()
{
  global $conf;
  
  if (isset($conf['cryptograph_theme']))
  {
    pwg_query('DELETE FROM '.CONFIG_TABLE.' WHERE param="cryptographp_theme" LIMIT 1;');
  }
  
  if (!isset($conf['cryptographp']))
  {
    conf_update_param('cryptographp', crypto_default_config);
  }
  else
  {
    $conf['cryptographp'] = unserialize($conf['cryptographp']);
    if (!isset($conf['cryptographp']['activate_on']))
    {
      $conf['cryptographp']['activate_on'] = array(
        'picture'     => $conf['cryptographp']['comments_action'] != 'inactive',
        'category'    => $conf['cryptographp']['comments_action'] != 'inactive',
        'register'    => true,
        'contactform' => true,
        'guestbook'   => true,
        );
      conf_update_param('cryptographp', serialize($conf['cryptographp']));
    }
    if (!isset($conf['cryptographp']['button_color']))
    {
      $conf['cryptographp']['button_color'] = 'dark';
      conf_update_param('cryptographp', serialize($conf['cryptographp']));
    }
  }
}

function plugin_uninstall()
{
  pwg_query('DELETE FROM '.CONFIG_TABLE.' WHERE param="cryptographp" LIMIT 1');
}

?>