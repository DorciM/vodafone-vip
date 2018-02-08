<?php require_once('config.inc.php');
$connection=dbconnect();
$CONFIG['riaszt_tabla']="[CCPortal].[dbo].[Aktual_riasztas]";
$CONFIG['riaszt_usergroup_tabla']="[CCPortal].[dbo].[csrdw_riaszt_usergroup]";
$getlogin=getlogin();
$admins=mssql_query("SELECT * FROM dbo.Aktual_admins WHERE (login='".getlogin()."') AND (aktiv=1)") or die('Invalid query: actual admins');
$currentadmin=mssql_fetch_row($admins);

// AUTHENTICATION
if(belsos()){$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dmzapplf6.ccportal.dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}
else{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}

// SELECT ALERTS
if(!(isset($_GET["function"])))
{$query1="SELECT * FROM ".$CONFIG['riaszt_tabla']." LEFT JOIN ccportal.dbo.csrdw_riaszt_usergroup ON ".$CONFIG['riaszt_tabla'].".azon=ccportal.dbo.csrdw_riaszt_usergroup.riaszt_id and ccportal.dbo.csrdw_riaszt_usergroup.aktiv=1 WHERE ".$CONFIG['riaszt_tabla'].".aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_riaszt_usergroup.aktiv=0)))";
 if(mssql_num_rows($result_user)!=0){mssql_data_seek($result_user,0);}
 if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$query1.=" OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_riaszt_usergroup.aktiv=1))";}}
 $query1.=") ORDER BY ".$CONFIG['riaszt_tabla'].".azon DESC";
 $result=mssql_query($query1) or die();
 $issues=array();$previous=0;while($record=mssql_fetch_row($result)){if($record[0]!=$previous){$issues[]=$record;$previous=$record[0];}}
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(belsos())){$admin=true;array_push($issues,$admin)}
 echo(json_encode($issues));
}

// USERGROUP LIST
if((isset($_GET["function"]))&&($_GET["function"]=="grouplist")&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos()))
{$result=mssql_query("SELECT azon,megj_nev FROM ccportal.dbo.csrdw_usergroups WHERE aktiv=1 AND azon<>1 AND azon<>4",$connection) or die();
 $groups=array();
 while($record=mssql_fetch_array($result)){array_push($groups,$record)} die();
}

// INSERT ALERT
if((isset($_GET["function"]))&&($_GET["function"]=="add")&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos())&&(isset($_GET["subject"])&&($_GET["subject"]!=""))
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
{$details=mssql_fetch_row($result);
 $groups=array();
 $usergroups=mssql_query("SELECT megj_nev FROM csrdw_riaszt_usergroup LEFT JOIN dbo.csrdw_usergroups ON dbo.csrdw_usergroups.azon=csrdw_riaszt_usergroup.usergroup_id WHERE (dbo.csrdw_riaszt_usergroup.riaszt_id =".$_GET['id'].") AND (dbo.csrdw_riaszt_usergroup.aktiv=1) AND (dbo.csrdw_usergroups.aktiv=1)") or die("Invalid query");
 if(mssql_num_rows($usergroups)>0){while($grouprecord=mssql_fetch_row($usergroups)){array_push($groups,trim($grouprecord[0]));}}
 array_push($details,$groups)
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(belsos())){$admin=true;array_push($details,$admin)}
 echo(json_encode($details));
}
 else{echo(json_encode($details='Nincs ilyen azonosítójú riasztás, vagy nincs jogosultságod a megtekintéséhez.'));}die();
}

// ARCHIVEDETAILS
if((isset($_GET["function"]))&&($_GET["function"]=="archivedetails")&&isset($_GET["id"]))
{$result=mssql_query("SELECT * FROM ".$CONFIG['riaszt_tabla']." WHERE azon=".$_GET["id"]) or die();
 $record=mssql_fetch_row($result);
 echo(json_encode($record));
 die();
}

// DELETE
if((isset($_GET["function"]))&&($_GET["function"]=="delete")&&isset($_GET["id"])&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos()))
{mssql_query("UPDATE ".$CONFIG['riaszt_tabla']." SET aktiv=0,torldatum='".date('Y-m-d')."',torlido='".date('H:i:s')."',torl='".getlogin()."' WHERE azon=".$_GET['id']) or die();
}
?>