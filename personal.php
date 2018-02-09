<?php header("Content-Type:application/json;charset=UTF-8");header("Expires:Sat, 1 Jan 2000 00:00:00 GMT");header("Last-Modified:".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control:no-store,no-cache,must-revalidate");header("Cache-Control:post-check=0,pre-check=0",false);header("Pragma:no-cache");
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belso=1;}else{$belso=0;}
require('config.inc.php');
$CONFIG['sajatlink_table']="[dbo].[sajatlink_new]";
$CONFIG['csrdw_menu_table']="[dbo].[csrdw_menu]";
$CONFIG['csrdw_content_usergroup_table']="[dbo].[csrdw_content_usergroup]";
$connection=dbconnect();
$getlogin=getlogin();
$authenticated=false;
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$internal=true;}else{$internal=false;}
if($internal)
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();
 $result_regisztralt=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1)") or die();
}else
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();
 $result_registered=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1)") or die();
}
if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$user[]=$record;};$authenticated=true;}
elseif(mssql_num_rows($result_registered)>0){$record=mssql_fetch_assoc($result_registered);$user[]=$record;$user[0]['usergroup_id']=1;$authenticated=true;}
else{die("User not registered");}

if((isset($_GET['view']))&&($_GET['view']=="favorites"))
{$query="SELECT ".$CONFIG['csrdw_menu_table'].".azon,menu,almenu,nev,tartalom,belso,kulcsszo,jog,tipus,tartalom_id,prioritas FROM ".$CONFIG['csrdw_menu_table']." left join ".$CONFIG['csrdw_content_usergroup_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['csrdw_content_usergroup_table'].".tartalom_id join ".$CONFIG['sajatlink_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['sajatlink_table'].".azon and dbo.sajatlink_new.login='".getlogin()."' WHERE (jog=".$belso." OR jog=".($belso+1).") AND ".$CONFIG['csrdw_menu_table'].".aktiv=1 AND almenu <> 'Teszt' AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=0)))"; 
 foreach($user as $sor){$query.=" OR ((usergroup_id=".$sor['usergroup_id'].")AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=1))";}
 $query.=") ORDER by prioritas";
 $links=mssql_query($query) or die("Invalid query");
 $favorites=array();
 if(mssql_num_rows($links)){$index=0;while(($record=mssql_fetch_assoc($links))&&($index<10)){if($record['tipus']==1){array_push($favorites,$record['nev']);}}}
 echo json_encode($favorites);
}

if(isset($_GET['log']))
{mssql_query("INSERT dbo.logger(datum,login,oldal) VALUES ('".date('Y-m-d H:i:s')."','".getlogin()."','VIP megnyitasa'+'".($_GET['log']==2)?" napibol":""."')") or die();
};

if(isset($_GET['nameday']))
{$CONFIG['nevnaptar_table']="[dbo].[nevnaptar]";
 $result=mssql_query("SELECT [azon],[nev],[datum] FROM ".$CONFIG['nevnaptar_table']." WHERE [datum]='".date('m-d')."' ORDER BY nev",dbconnect()) or die();
 $names=array();
 while($record=mssql_fetch_assoc($result)){array_push($names,$record["nev"]);}
 echo(json_encode($names));
}?>