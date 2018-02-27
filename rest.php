<?php 

require_once('config.inc.php');

// akt_riaszt.php
// require_once('config.inc.php');
$connection=dbconnect();
$CONFIG['riaszt_tabla']="[dbo].[Aktual_riasztas]";
$CONFIG['riaszt_usergroup_tabla']="[dbo].[csrdw_riaszt_usergroup]";
$getlogin=getlogin();
$admins=mssql_query("SELECT * FROM dbo.Aktual_admins WHERE (login='".getlogin()."') AND (aktiv=1)") or die('Invalid query: actual admins');
$currentadmin=mssql_fetch_row($admins);
if(internal())
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();
}
else
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();
}

if(isset($_GET["function"])&&($_GET["function"]=="alertlist"))
{$query="SELECT * FROM ".$CONFIG['riaszt_tabla']." LEFT JOIN dbo.csrdw_riaszt_usergroup ON ".$CONFIG['riaszt_tabla'].".azon=dbo.csrdw_riaszt_usergroup.riaszt_id and dbo.csrdw_riaszt_usergroup.aktiv=1";
 $query.=" WHERE ".$CONFIG['riaszt_tabla'].".aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_riaszt_usergroup.aktiv=0)))";
 if(mssql_num_rows($result_user)!=0){mssql_data_seek($result_user,0);}
 if(mssql_num_rows($result_user)>0)
{while($record=mssql_fetch_assoc($result_user))
{$query.=" OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_riaszt_usergroup.aktiv=1))";
}
}
 $query.=") ORDER BY ".$CONFIG['riaszt_tabla'].".azon DESC";
 $result=mssql_query($query) or die();
 $response=array();
 $response["alerts"]=array();
 $previous=0;
 while($record=mssql_fetch_row($result))
{if($record[0]!=$previous)
{array_push($response["alerts"],$record);
 $previous=$record[0];
}
}
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(internal())){$response["admin"]=true;}
 $response["admin"]=true;
 echo(json_encode($response));
}

// REFRESH
if(isset($_GET["update"]))
{if((preg_match("/^[a-z0-9]+\.[a-z0-9]+$/",$getlogin))||(preg_match("/^[a-z0-9]+$/",$getlogin)))
{$query="SELECT * FROM ".$CONFIG['riaszt_tabla']." LEFT JOIN dbo.csrdw_riaszt_usergroup ON ".$CONFIG['riaszt_tabla'].".azon=dbo.csrdw_riaszt_usergroup.riaszt_id and dbo.csrdw_riaszt_usergroup.aktiv=1"
 $query.=" WHERE ".$CONFIG['riaszt_tabla'].".aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_riaszt_usergroup.aktiv=0)))";
 if(mssql_num_rows($result_user)!=0){mssql_data_seek($result_user,0);}
 if(mssql_num_rows($result_user)>0)
{while($record=mssql_fetch_assoc($result_user))
{$query.="OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_riaszt_usergroup.aktiv=1))";
}
}
 $query.=") ORDER BY ".$CONFIG['riaszt_tabla'].".azon DESC";
 $result=mssql_query($query) or die();
 /*$result=mssql_query("SELECT * FROM ".$CONFIG['riaszt_tabla']." WHERE aktiv=1 ORDER BY datum DESC,ido DESC") or die();*/
 $response=array();
 $previous=0;
 while($record=mssql_fetch_row($result))
{if($record[0]!=$previous)
{$response["alerts"][]=$record;$previous=$record[0];
}
}
 echo(json_encode($response));
}die();
}

if((isset($_GET["function"]))&&($_GET["function"]=="grouplist")/*&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(internal())*/)
{$result=mssql_query("SELECT azon,megj_nev FROM dbo.csrdw_usergroups WHERE aktiv=1 AND azon<>1 AND azon<>4",$connection) or die();
 $response=array();
 $response["groups"]=array();
 while($record=mssql_fetch_array($result)){array_push($response["groups"],$record);};
 echo(json_encode($response));
 die();
}

// INSERT ALERT
if((isset($_GET["function"]))&&($_GET["function"]=="add")&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(internal())&&(isset($_GET["subject"])&&($_GET["subject"]!="")))
{$result=mssql_query("SELECT MAX(azon) FROM ".$CONFIG['riaszt_tabla']) or die();
 $lastalert=mssql_fetch_row($result);
 mssql_query("INSERT INTO ".$CONFIG['riaszt_tabla']." VALUES (".$lastalert[0]++.",'".getlogin()."','".wordwrap($_GET["subject"],30,"\n",true)."','".date('Y-m-d')."','".date('H:i:s')."',1,'".$_GET['details']."',null,null,null)") or die();
 // GROUP ASSOCIATIONS
 if((isset($_GET['usergroup']))&&(is_array($_GET['usergroup'])))
{$result=mssql_query("SELECT MAX(azon) FROM ".$CONFIG['riaszt_usergroup_tabla']) or die();
 $lastgroup=mssql_fetch_row($result);
 foreach($_GET['usergroup'] as $group){mssql_query("INSERT INTO ".$CONFIG['riaszt_usergroup_tabla']." (azon,riaszt_id,usergroup_id,aktiv) VALUES (".$lastgroup[0]++.",".$lastalert[0].",".$group[0].",1)",$connection)or die();}
}
}

// ALERT DETAILS
if((isset($_GET["function"]))&&($_GET["function"]=="details")&&isset($_GET["id"]))
{$result=mssql_query("SELECT * FROM ".$CONFIG['riaszt_tabla']." WHERE aktiv>=0 AND azon=".$_GET["id"]) or die();
 if(mssql_num_rows($result)>0)
{$response=array();
 $response["details"]=mssql_fetch_row($result);
 $response["groups"]=array();
 $result=mssql_query("SELECT megj_nev FROM csrdw_riaszt_usergroup LEFT JOIN dbo.csrdw_usergroups ON dbo.csrdw_usergroups.azon=csrdw_riaszt_usergroup.usergroup_id WHERE (dbo.csrdw_riaszt_usergroup.riaszt_id =".$_GET['id'].") AND (dbo.csrdw_riaszt_usergroup.aktiv=1) AND (dbo.csrdw_usergroups.aktiv=1)") or die("Invalid query");
 if(mssql_num_rows($result)>0){while($record=mssql_fetch_row($result)){array_push($response["groups"],trim($record[0]));}}
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(internal())){array_push($response["admin"]=true);}
 $response["admin"]=true;
 echo(json_encode($response));
}
 else
{$response["details"]='Nincs ilyen azonosítójú riasztás, vagy nincs jogosultságod a megtekintéséhez.'
 echo(json_encode($response));
}die();
}

// ARCHIVEDETAILS
if((isset($_GET["function"]))&&($_GET["function"]=="archivedetails")&&isset($_GET["id"]))
{$result=mssql_query("SELECT * FROM ".$CONFIG['riaszt_tabla']." WHERE azon=".$_GET["id"]) or die();
 $record=mssql_fetch_row($result);
 echo(json_encode($record));
 die();
}

// DELETE
if((isset($_GET["function"]))&&($_GET["function"]=="delete")&&isset($_GET["id"])&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(internal()))
{mssql_query("UPDATE ".$CONFIG['riaszt_tabla']." SET aktiv=0,torldatum='".date('Y-m-d')."',torlido='".date('H:i:s')."',torl='".getlogin()."' WHERE azon=".$_GET['id']) or die();
}

// content.php
// header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");
// require_once('config.inc.php');
dbconnect();
if(isset($_GET["content_id"]))
{if(internal()){$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users) UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso )) as A INNER JOIN csrdw_usergroup_user ON A.azon = csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".getlogin()."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}
 else{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users) UNION ALL (SELECT * FROM dbo.csrdw_users_kulso)) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".getlogin()."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}
 $query="SELECT tartalom FROM dbo.csrdw_menu LEFT JOIN dbo.csrdw_content_usergroup ON dbo.csrdw_menu.azon = dbo.csrdw_content_usergroup.tartalom_id WHERE dbo.csrdw_menu.azon=".$_GET['content_id']." AND (jog=".internal()." OR jog=".(internal()+1).") AND csrdw_menu.aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)))";
 if(mssql_num_rows($result_user)>0)
{while($record=mssql_fetch_assoc($result_user))
{$query.=" OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";
}
}
 $query.=")";
 $result=mssql_query($query) or die("Database error.");
 if(mssql_num_rows($result)>0)
{$record=mssql_fetch_assoc($result);
 if(internal()){$content=$record['tartalom'];}
 else{$content=str_replace("weoapplf6/ccportal","dmzapplf6/",$record['tartalom']);}
}
 else{$content='Nem található az azonosítónak megfelelő tartalom, vagy nincs jogosultságod a megtekintéséhez.';}
 echo(json_encode($content));
}

// menu.php
// require('config.inc.php');
if(isset($_GET["menu"]))
{$connection=dbconnect();
 $getlogin=getlogin();
 if(($getlogin=='zoltan.gaal')||($getlogin=='daniel.gilan')||($getlogin=='tamas.terenyei')){$vipadmin=true;}else{$vipadmin=false;};
 if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$internal=1;}else{$internal=0;}
 $authenticated=false;do
{if($internal)
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv = 1)") or die();
 $result_regisztralt=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1)") or die();
}else{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv = 1)") or die();$result_regisztralt=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1)") or die();}
 if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$user[]=$record;}$authenticated=true;} //mssql_query("UPDATE dbo.csrdw_users SET utolso_belepes=".time()." WHERE azon = ".$user[0]['azon']) or die("");
 elseif(mssql_num_rows($result_regisztralt)>0){$record=mssql_fetch_assoc($result_regisztralt);$user[]=$record;$user[0]['usergroup_id']=1;$authenticated=true;}
 else
{if($internal){
 $usermaxsql=mssql_query("SELECT MAX(azon) FROM dbo.csrdw_users") or die("");
 $usermax=mssql_fetch_row($usermaxsql);
 $ujsorsz=$usermax[0]+1;
 mssql_query("INSERT INTO dbo.csrdw_users (azon,ntlogin,megj_nev,email,utolso_belepes,aktiv,letrehozva) VALUES(".$ujsorsz.", '".getlogin()."', '".getnev()."', '', ".time().", 1, ".time().")") or die("Invalid query: User létrehozása 1");
}else
{$usermaxsql=mssql_query("SELECT MAX(azon) FROM dbo.csrdw_users_kulso") or die("");
 $usermax=mssql_fetch_row($usermaxsql);
 $ujsorsz=$usermax[0]+1;
 mssql_query("INSERT INTO dbo.csrdw_users_kulso (azon,ntlogin,megj_nev,email,utolso_belepes,aktiv,letrehozva) VALUES(".$ujsorsz.", '".getlogin()."', '".getnev()."', '', ".time().", 1, ".time().")") or die("Invalid query: User létrehozása 1");
}
}
}
 while($authenticated==false);
 if(isset($_GET["menu"])){$menu=$_GET["menu"];}
 if(isset($_GET["almenu"])){$almenu=$_GET["almenu"];}
 $query="SELECT [dbo].[csrdw_menu].azon,menu,almenu,nev,tartalom,belso,kulcsszo,jog,tipus,tartalom_id,[dbo].[csrdw_usergroups].azon as csopazon, [dbo].[csrdw_usergroups].megj_nev FROM [dbo].[csrdw_menu] left join [dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[dbo].[csrdw_content_usergroup].tartalom_id left join [dbo].[csrdw_usergroups] on [dbo].[csrdw_content_usergroup].usergroup_id=[dbo].[csrdw_usergroups].azon WHERE (jog=".$internal." OR jog=".($internal+1).") AND csrdw_menu.aktiv=1 AND ( ((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)) OR ((usergroup_id>0) AND(csrdw_content_usergroup.aktiv=1)))) ORDER by nev";
 //$query="SELECT [dbo].[csrdw_menu].azon, menu, almenu, nev, tartalom, belso, kulcsszo, jog, tipus, tartalom_id FROM [CCPortal].[dbo].[csrdw_menu] left join [dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[dbo].[csrdw_content_usergroup].tartalom_id WHERE menu='".$menu."' AND almenu='".$almenu."' AND (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)))";foreach ($user as $sor){$query.="OR ((usergroup_id=".$sor['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";}$query.=") ORDER by nev";//$query="SELECT [CCPortal].[dbo].[csrdw_menu].azon,menu,almenu,nev,tartalom,belso,kulcsszo,jog,tipus,tartalom_id,[CCPortal].[dbo].[csrdw_usergroups].megj_nev FROM [CCPortal].[dbo].[csrdw_menu] left join [CCPortal].[dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[CCPortal].[dbo].[csrdw_content_usergroup].tartalom_id left join [CCPortal].[dbo].[csrdw_usergroups] on [CCPortal].[dbo].[csrdw_content_usergroup].usergroup_id=[CCPortal].[dbo].[csrdw_usergroups].azon WHERE menu='".$menu."' AND almenu='".$almenu."' AND (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL) AND (csrdw_content_usergroup.aktiv=0)))";foreach($user as $record){$query.="OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";}$query.=") ORDER by nev";
 $result=mssql_query($query) or die ("Invalid query1");
 foreach($user as $record){$jogsi[]=$record['usergroup_id'];}

 if(internal()&&getenv("COMPUTERNAME")!='WEOAPPLF6'){mssql_query("INSERT INTO dbo.kulsobelsohiba_log (login,ido) VALUES ('".getlogin()."','".date('Y-m-d H:i:s')."')") or die();}
 $response=array();
 $response["categories"]=array();
 while($record=mssql_fetch_assoc($result))
{if((1==0)&&(preg_match("/vodafone\.hu/",$record['tartalom']))&&(preg_match("/MSIE 6/",$_SERVER['HTTP_USER_AGENT']))&&(($getlogin=='zoltan.gaal')||($getlogin=='daniel.gilan')||($getlogin=='tamas.terenyei'))){$forcedchrome=true;$record['tartalom']="javascript:openurlinchrome('".$record['tartalom']."')";}
 else{$record['tartalom']=str_replace("weoapplf6/ccportal","dmzapplf6",$record['tartalom']);}
 array_push($response["categories"],$record);
};
 echo(json_encode($response));
}

// personal.php
// header("Content-Type:application/json;charset=UTF-8");header("Expires:Sat, 1 Jan 2000 00:00:00 GMT");header("Last-Modified:".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control:no-store,no-cache,must-revalidate");header("Cache-Control:post-check=0,pre-check=0",false);header("Pragma:no-cache");
// require('config.inc.php');
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$internal=1;}else{$internal=0;}
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
{$query="SELECT ".$CONFIG['csrdw_menu_table'].".azon,menu,almenu,nev,tartalom,belso,kulcsszo,jog,tipus,tartalom_id,prioritas FROM ".$CONFIG['csrdw_menu_table']." left join ".$CONFIG['csrdw_content_usergroup_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['csrdw_content_usergroup_table'].".tartalom_id join ".$CONFIG['sajatlink_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['sajatlink_table'].".azon and dbo.sajatlink_new.login='".getlogin()."' WHERE (jog=".$internal." OR jog=".($internal+1).") AND ".$CONFIG['csrdw_menu_table'].".aktiv=1 AND almenu <> 'Teszt' AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=0)))"; 
 foreach($user as $record){$query.=" OR ((usergroup_id=".$record['usergroup_id'].")AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=1))";}
 $query.=") ORDER by prioritas";
 $links=mssql_query($query) or die("Invalid query");
 $response=array();
 $response["favorites"]=array();
 if(mssql_num_rows($links))
{$index=0;
 while(($record=mssql_fetch_assoc($links))&&($index<10))
{if($record['tipus']==1){array_push($response["favorites"],$record['nev']);}
}
}
 echo json_encode($response);
}

if(isset($_GET['log']))
{mssql_query("INSERT dbo.logger(datum,login,oldal) VALUES ('".date('Y-m-d H:i:s')."','".getlogin()."','VIP megnyitasa'+'".($_GET['log']==2)?" napibol":""."')") or die();
};

if(isset($_GET['nameday']))
{$CONFIG['nevnaptar_table']="[dbo].[nevnaptar]";
 $result=mssql_query("SELECT [azon],[nev],[datum] FROM ".$CONFIG['nevnaptar_table']." WHERE [datum]='".date('m-d')."' ORDER BY nev",dbconnect()) or die();
 $response=array();
 $response["names"]=array();
 while($record=mssql_fetch_assoc($result)){array_push($response["names"],$record["nev"]);}
 echo(json_encode($response));
}

// header("Content-Type:application/json;charset=UTF-8");
// require('config.inc.php');
// if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belsos=1;}else{$belsos=0;}
$connection=dbconnect();
$CONFIG["hl_top_questions_main_table"]="[CCPortal].[dbo].[hl_top_questions_main]";
$CONFIG["hl_top_questions_temak_table"]="[CCPortal].[dbo].[hl_top_questions_temak]";
if(isset($_GET['topics']))
{//$temalista=mssql_query("SELECT distinct a.[tema] as azon,b.[tema] FROM ".$CONFIG["hl_top_questions_main_table"]." as a LEFT JOIN ".$CONFIG["hl_top_questions_temak_table"]." as b ON a.tema = b.azon WHERE a.[aktiv] = 1",$connection) or die();
 $response=array();
 $response["topics"]=array();
 //while($record=mssql_fetch_assoc($temalista)){array_push($topics,$record);};
 echo(json_encode($response));
}
?>
