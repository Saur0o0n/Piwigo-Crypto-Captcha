<?php
function plugin_install()
{
  global $conf;

  
  if (!isset($conf['cryptograph_theme']))
  {
    pwg_query('INSERT INTO '.CONFIG_TABLE.' (param,value,comment) VALUES ("cryptographp_theme","cryptographp","CryptograPHP theme");');
  }
}

function plugin_activate($id, $version, &$errors)
{
  global $conf;

  if (!isset($conf['cryptographp_theme']))
  {
    plugin_install();
  }
}


function plugin_uninstall()
{
  pwg_query('DELETE FROM '.CONFIG_TABLE.' WHERE param="cryptographp_theme" LIMIT 1');
}

?>