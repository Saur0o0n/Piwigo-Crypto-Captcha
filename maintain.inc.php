<?php
define('crypto_default_config', serialize(array(
  'comments_action' => 'reject',
  'theme'           => 'gray',
  'captcha_type'    => 'string',
  'case_sensitive'  => 'false',
  'code_length'     => 6,
  'width'           => 120, 
  'height'          => 40,
  'perturbation'    => 1, 
  'image_bg_color'  => 'ffffff', 
  'text_color'      => '8a8a8a', 
  'num_lines'       => 2, 
  'line_color'      => '8a8a8a', 
  'noise_level'     => 0.1, 
  'noise_color'     => '8a8a8a', 
  'ttf_file'        => 'TopSecret',
)));

function plugin_install()
{
  global $conf;
  
  if (!isset($conf['cryptographp']))
  {
    pwg_query('INSERT INTO '.CONFIG_TABLE.' (param,value,comment) VALUES ("cryptographp",\''.crypto_default_config.'\',"CryptograPHP config");');
  }
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
    pwg_query('INSERT INTO '.CONFIG_TABLE.' (param,value,comment) VALUES ("cryptographp",\''.crypto_default_config.'\',"CryptograPHP config");');
  }
}

function plugin_uninstall()
{
  pwg_query('DELETE FROM '.CONFIG_TABLE.' WHERE param="cryptographp" LIMIT 1');
}

?>