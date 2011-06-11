<?php
function plugin_install()
{
  global $conf;
  
  if (!isset($conf['cryptographp_theme']))
  {
    pwg_query('INSERT INTO '.CONFIG_TABLE.' (param,value,comment) VALUES ("cryptographp_theme","cryptographp,reject","CryptograPHP config");');
  }
}

function plugin_activate()
{
  global $conf;
  $conf['cryptographp_theme'] = explode(',', $conf['cryptographp_theme']);
  
  if(count($conf['cryptographp_theme']) == 1)
  {
    pwg_query('UPDATE '.CONFIG_TABLE.' SET value = "'.$conf['cryptographp_theme'][0].',reject" WHERE param = "cryptographp_theme";');
  }
}
function plugin_uninstall()
{
  pwg_query('DELETE FROM '.CONFIG_TABLE.' WHERE param="cryptographp_theme" LIMIT 1');
}

?>