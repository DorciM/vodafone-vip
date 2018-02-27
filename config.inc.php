<?php 

if((getenv("COMPUTERNAME")==='WEOAPPLF6'))$internal=1;else $internal=0;
if(!function_exists('internal')){function internal(){global $internal;return $internal;}}
if(internal())
{$CONFIG['web_root']="http://weoapplf6/ccportal/";
 $CONFIG['file_root']="P:/inetpub/wwwroot/ccportal/";
}else
{$CONFIG['web_root']="http://dmzapplf6/";
 $CONFIG['file_root']="P:/inetpub/wwwroot/";
}
$CONFIG['adatb_szerver']="weoapplf6";
$CONFIG['adatb_felhnev']="csrdw_dev";
$CONFIG['adatb_jelszo']="CSRDWru13z789";
$CONFIG['adatb_nev']="CCPortal_dev2";
$CONFIG['adatb_tabla_menu']="dbo.csrdw_menu";
$CONFIG['adatb_tabla_users']="dbo.csrdw_users";
$CONFIG['adatb_tabla_usergroups']="dbo.csrdw_usergroups";
$CONFIG['adatb_tabla_content_visibility']="dbo.csrdw_content_visibility";

if(!function_exists('getlogin'))
{function getlogin()
{$login=strtolower($_SERVER['REMOTE_USER']);$login=substr(strrchr($login,'\\'),1);return $login;
}
}

if(!function_exists('getnev'))
{function getnev()
{if(preg_match("/^[a-zA-z0-9]+\.[a-zA-z0-9]+$/",getlogin()))
{$user=explode(".",getlogin());$nev=ucfirst($user[1])." ".ucfirst($user[0]);return $nev;
}else{return getlogin();
}
}
}

if(!function_exists('dbconnect'))
{function dbconnect()
{global $CONFIG;
 $connection=@mssql_connect($CONFIG['adatb_szerver'],$CONFIG['adatb_felhnev'],$CONFIG['adatb_jelszo']) or die('Az adatbazis nem elerheto. A hiba kijavitasa folyamatban van, kis turelmet kerunk.');
 $db=@mssql_select_db($CONFIG['adatb_nev'],$connection) or die('Nem erheto el az adatbazis. A hiba kijavitasa folyamatban van, kis turelmet kerunk.');
 if(internal()){$result=mssql_query("SET ANSI_NULLS ON");$result=mssql_query("SET ANSI_WARNINGS ON");}
 return $connection;
}
}

if(!function_exists('jomail'))
{function jomail($Email)
{$result=eregi("^[_a-z0-9-]+[\._a-z0-9-]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$Email);return $result;
}
}

if(!function_exists('slashes'))
{function slashes($data)
{$data=str_replace("'","''",$data);return $data;
}
}

?>