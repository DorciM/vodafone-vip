<?php require('config.inc.php');
$connection=dbconnect();
$getlogin=getlogin();
if(($getlogin=='zoltan.gaal')||($getlogin=='daniel.gilan')||($getlogin=='tamas.terenyei')){$vipadmin=true;}else{$vipadmin=false;};
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belso=1;}else{$belso=0;}
$azonositva=false;do
{if($belso)
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv = 1)") or die();
 $result_regisztralt=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1)") or die();
}
 else{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv = 1)") or die();$result_regisztralt=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1)") or die();}
 if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$user[]=$record;}$azonositva=true;} //mssql_query("UPDATE dbo.csrdw_users SET utolso_belepes=".time()." WHERE azon = ".$user[0]['azon']) or die("");
 elseif(mssql_num_rows($result_regisztralt)>0){$record=mssql_fetch_assoc($result_regisztralt);$user[]=$record;$user[0]['usergroup_id']=1;$azonositva=true;}
 else
{if($belso){$usermaxsql=mssql_query("SELECT MAX(azon) FROM dbo.csrdw_users") or die("");$usermax=mssql_fetch_row($usermaxsql);$ujsorsz=$usermax[0]+1;mssql_query("INSERT INTO dbo.csrdw_users (azon, ntlogin, megj_nev, email, utolso_belepes, aktiv, letrehozva) VALUES(".$ujsorsz.", '".getlogin()."', '".getnev()."', '', ".time().", 1, ".time().")") or die("Invalid query: User létrehozása 1");}
 else{$usermaxsql=mssql_query("SELECT MAX(azon) FROM dbo.csrdw_users_kulso") or die("");$usermax=mssql_fetch_row($usermaxsql);$ujsorsz=$usermax[0]+1;mssql_query("INSERT INTO dbo.csrdw_users_kulso (azon, ntlogin, megj_nev, email, utolso_belepes, aktiv, letrehozva) VALUES(".$ujsorsz.", '".getlogin()."', '".getnev()."', '', ".time().", 1, ".time().")") or die("Invalid query: User létrehozása 1");}
}
}
while($azonositva==false); 
if(isset($_GET["menu"])){$menu=$_GET["menu"];}
if(isset($_GET["almenu"])){$almenu=$_GET["almenu"];}
$query="SELECT [dbo].[csrdw_menu].azon,menu,almenu,nev,tartalom,belso,kulcsszo,jog,tipus,tartalom_id,[dbo].[csrdw_usergroups].azon as csopazon, [dbo].[csrdw_usergroups].megj_nev FROM [dbo].[csrdw_menu] left join [dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[dbo].[csrdw_content_usergroup].tartalom_id left join [dbo].[csrdw_usergroups] on [dbo].[csrdw_content_usergroup].usergroup_id=[dbo].[csrdw_usergroups].azon WHERE (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND ( ((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)) OR ((usergroup_id>0) AND(csrdw_content_usergroup.aktiv=1)))) ORDER by nev";
//$query="SELECT [dbo].[csrdw_menu].azon, menu, almenu, nev, tartalom, belso, kulcsszo, jog, tipus, tartalom_id FROM [CCPortal].[dbo].[csrdw_menu] left join [dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[dbo].[csrdw_content_usergroup].tartalom_id WHERE menu='".$menu."' AND almenu='".$almenu."' AND (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)))";foreach ($user as $sor){$query.="OR ((usergroup_id=".$sor['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";}$query.=") ORDER by nev";//$query="SELECT [CCPortal].[dbo].[csrdw_menu].azon,menu,almenu,nev,tartalom,belso,kulcsszo,jog,tipus,tartalom_id,[CCPortal].[dbo].[csrdw_usergroups].megj_nev FROM [CCPortal].[dbo].[csrdw_menu] left join [CCPortal].[dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[CCPortal].[dbo].[csrdw_content_usergroup].tartalom_id left join [CCPortal].[dbo].[csrdw_usergroups] on [CCPortal].[dbo].[csrdw_content_usergroup].usergroup_id=[CCPortal].[dbo].[csrdw_usergroups].azon WHERE menu='".$menu."' AND almenu='".$almenu."' AND (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL) AND (csrdw_content_usergroup.aktiv=0)))";foreach($user as $record){$query.="OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";}$query.=") ORDER by nev";
$result=mssql_query($query) or die ("Invalid query1");
foreach($user as $record){$jogsi[]=$record['usergroup_id'];}

if(belsos()&&getenv("COMPUTERNAME")!='WEOAPPLF6'){mssql_query("INSERT INTO dbo.kulsobelsohiba_log (login,ido) VALUES ('".getlogin()."','".date('Y-m-d H:i:s')."')") or die();}
$categories=array();
while($record=mssql_fetch_assoc($result))
{if((1==0)&&(preg_match("/vodafone\.hu/",$record['tartalom']))&&(preg_match("/MSIE 6/",$_SERVER['HTTP_USER_AGENT']))&&(($getlogin=='zoltan.gaal')||($getlogin=='daniel.gilan')||($getlogin=='tamas.terenyei'))){$forcedchrome=true;$record['tartalom']="javascript:openurlinchrome('".$record['tartalom']."')";}
 else{$record['tartalom']=str_replace("weoapplf6/ccportal","dmzapplf6",$record['tartalom']);}
 array_push($categories,$record);
};
echo(json_encode($categories));?>