<?php //header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");
require_once('config.inc.php');
dbconnect();
if(belsos()){$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users) UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso )) as A INNER JOIN csrdw_usergroup_user ON A.azon = csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".getlogin()."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}
else{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users) UNION ALL (SELECT * FROM dbo.csrdw_users_kulso)) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".getlogin()."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}
$query="SELECT tartalom FROM dbo.csrdw_menu LEFT JOIN dbo.csrdw_content_usergroup ON dbo.csrdw_menu.azon = dbo.csrdw_content_usergroup.tartalom_id WHERE dbo.csrdw_menu.azon=".$_GET['content_id']." AND (jog=".belsos()." OR jog=".(belsos()+1).") AND csrdw_menu.aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)))";
if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$query.=" OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";}}
$query.=")";
$result=mssql_query($query) or die("Database error.");
if(mssql_num_rows($result)>0)
{$record=mssql_fetch_assoc($result);
 if(belsos()){$content=$record['tartalom'];}
 else{$content=str_replace("weoapplf6/ccportal","dmzapplf6/",$record['tartalom']);}
}
else{$content='Nem található az azonosítónak megfelelő tartalom, vagy nincs jogosultságod a megtekintéséhez.';}
echo(json_encode($content));
?>