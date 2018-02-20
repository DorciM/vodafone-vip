<?php if((getenv("COMPUTERNAME")==='WEOAPPLF6'))$belsos=1;else $belsos=0;
if(!function_exists('belsos')){function belsos(){global $belsos;return $belsos;}}
if(belsos())
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
if(!function_exists('getlogin')){function getlogin(){$login=strtolower($_SERVER['REMOTE_USER']);$login=substr(strrchr($login,'\\'),1);return $login;}}
if(!function_exists('getnev')){function getnev(){if(preg_match("/^[a-zA-z0-9]+\.[a-zA-z0-9]+$/",getlogin())){$user=explode(".",getlogin());$nev=ucfirst($user[1])." ".ucfirst($user[0]);return $nev;}else{return getlogin();}}}
if(!function_exists('dbconnect'))
{function dbconnect()
{global $CONFIG;
 $connection=@mssql_connect($CONFIG['adatb_szerver'],$CONFIG['adatb_felhnev'],$CONFIG['adatb_jelszo']) or die('Az adatbazis nem elerheto. A hiba kijavitasa folyamatban van, kis turelmet kerunk.');
 $db=@mssql_select_db($CONFIG['adatb_nev'],$connection) or die('Nem erheto el az adatbazis. A hiba kijavitasa folyamatban van, kis turelmet kerunk.');
 if(belsos()){$result=mssql_query("SET ANSI_NULLS ON");$result=mssql_query("SET ANSI_WARNINGS ON");}
 return $connection;
}
}
if(!function_exists('jomail')){function jomail($Email){$result=eregi("^[_a-z0-9-]+[\._a-z0-9-]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$Email);return $result;}}
if(!function_exists('slashes')){function slashes($data){$data=str_replace("'","''",$data);return $data;}}
?>


<?php require_once('config.inc.php');
$connection=dbconnect();
$CONFIG['riaszt_tabla']="[dbo].[Aktual_riasztas]";
$CONFIG['riaszt_usergroup_tabla']="[dbo].[csrdw_riaszt_usergroup]";
$getlogin=getlogin();
$admins=mssql_query("SELECT * FROM dbo.Aktual_admins WHERE (login='".getlogin()."') AND (aktiv=1)") or die('Invalid query: actual admins');
$currentadmin=mssql_fetch_row($admins);

// AUTHENTICATION
if(belsos()){$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.dmzapplf6_csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}
else{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();}

// SELECT ALERTS
if(!(isset($_GET["function"])))
{$query1="SELECT * FROM ".$CONFIG['riaszt_tabla']." LEFT JOIN dbo.csrdw_riaszt_usergroup ON ".$CONFIG['riaszt_tabla'].".azon=dbo.csrdw_riaszt_usergroup.riaszt_id and dbo.csrdw_riaszt_usergroup.aktiv=1 WHERE ".$CONFIG['riaszt_tabla'].".aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_riaszt_usergroup.aktiv=0)))";
 if(mssql_num_rows($result_user)!=0){mssql_data_seek($result_user,0);}
 if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$query1.=" OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_riaszt_usergroup.aktiv=1))";}}
 $query1.=") ORDER BY ".$CONFIG['riaszt_tabla'].".azon DESC";
 $result=mssql_query($query1) or die();
 $issues=array();$previous=0;while($record=mssql_fetch_row($result)){if($record[0]!=$previous){$issues[]=$record;$previous=$record[0];}}
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(belsos())){$admin=true;array_push($issues,$admin);}
 echo(json_encode($issues));
}

// USERGROUP LIST
if((isset($_GET["function"]))&&($_GET["function"]=="grouplist")&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos()))
{$result=mssql_query("SELECT azon,megj_nev FROM dbo.csrdw_usergroups WHERE aktiv=1 AND azon<>1 AND azon<>4",$connection) or die();
 $groups=array();
 while($record=mssql_fetch_array($result)){array_push($groups,$record);} die();
}

// INSERT ALERT
if((isset($_GET["function"]))&&($_GET["function"]=="add")&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos())&&(isset($_GET["subject"])&&($_GET["subject"]!="")))
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
 array_push($details,$groups);
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(belsos())){$admin=true;array_push($details,$admin);}
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

// AJAX
if(isset($_GET["ajax"]))
{if((isset($_GET["login"])&&((preg_match("/^[a-z0-9]+\.[a-z0-9]+$/",$_GET["login"]))||(preg_match("/^[a-z0-9]+$/",$_GET["login"])))))
{if(belsos()){$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dmzapplf6.ccportal.dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv=1)") or die();}/*$result=mssql_query("SELECT * FROM ".$CONFIG['riaszt_tabla']." WHERE aktiv=1 ORDER BY datum DESC,ido DESC") or die();*/
 else{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon = csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv = 1)") or die();}
 $query1="SELECT * FROM ".$CONFIG['riaszt_tabla']." LEFT JOIN ccportal.dbo.csrdw_riaszt_usergroup ON ".$CONFIG['riaszt_tabla'].".azon=ccportal.dbo.csrdw_riaszt_usergroup.riaszt_id and ccportal.dbo.csrdw_riaszt_usergroup.aktiv=1 WHERE ".$CONFIG['riaszt_tabla'].".aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_riaszt_usergroup.aktiv=0)))";
 if(mssql_num_rows($result_user)!=0){mssql_data_seek($result_user,0);}
 if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$query1.="OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_riaszt_usergroup.aktiv=1))";}}
 $query1.=") ORDER BY ".$CONFIG['riaszt_tabla'].".azon DESC";
 $result=mssql_query($query1) or die();
 $alerts=array();
 $elozo=0;
 while($record=mssql_fetch_row($result)){if($record[0]!=$elozo){$alerts[]=$record;$elozo=$record[0];}}
 $output="";
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(belsos()))
{$output.='<div title="Új riasztás" style="border:0px solid red;font-size:14px;font-weight:bold;color:black;width:7%;height:15px;float:right;" onclick="window.open(\'akt_riaszt.php?function=add_form\',\'_blank\',\'titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,height=390\')"><img src="Orb-Blue-Plus-36.gif" style="width:22 px;height:22px;"></div>';//$output.='<div title="Új riasztás" style="font-weight:bold;font-size:14px;color:black;width:15px;height:15px;float:right;" onclick="window.open(\'akt_riaszt.php?function=add_form\',\'_blank\',\'titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,eight=390\')"><img src="Orb-Blue-Plus-36.gif" style="width:22 px;height:22px;"></div>';
}
 $output.='<div style="overflow-x:hidden;float:left;width:92%;height:100%;border:0px solid red;">';
 foreach($alerts as $record)
{$nev=explode('.',trim($record[1]));
 $output.='<li style="cursor:pointer;list-style:disc inside url(\'1rightarrow-12.gif\');" title="'.ucfirst($nev[1]).' '.ucfirst($nev[0]).' rögzítette '.$record[3].trim($record[4]).'-kor." //$output.='<li style="cursor: pointer; list-style:disc inside url(\'1rightarrow-12.gif\');" title="'.ucfirst($nev[1]).' '.ucfirst($nev[0]).' rögzítette '.$record[3].trim($record[4]).'-kor." onclick="detaileswindow=window.open(\'akt_riaszt.php?function=details&id='.$record[0].'\',\'_blank\',\' titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,height=370\')">'.$record[2].'</li>'; //onclick="detaileswindow=window.open(\'akt_riaszt.php?function=details&id='.$record[0].'\',\'_blank\',\' titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,height=370\')">'.nl2br($record[2]).'</li>';
}
 $output.='</div>oO|||Oo';
 foreach($alerts as $record){$output.=$record[0].',';} /*while($record=mssql_fetch_row($result)){$nev=explode('.',trim($record[1]));$output.='<li style="cursor:pointer;list-style:disc inside url(\'1rightarrow-12.gif\');" title="'.ucfirst($nev[1]).' '.ucfirst($nev[0]).' rögzítette '.$record[3].trim($record[4]).'-kor." onclick="detaileswindow=window.open(\'akt_riaszt.php?function=details&id='.$record[0].'\',\'_blank\',\' titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,height=320\')">'.nl2br($record[2]).'</li>'; //echo('<li title="'.ucfirst($nev[1]).' '.ucfirst($nev[0]).' rögzítette '.$record[3].trim($record[4]).'-kor." //onclick="window.open(\'akt_riaszt.php?function=details&id='.$record[0].'\',\'_blank\',\' titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,height=300\')">'.$record[2].'</li>');}*/
 echo(utf8_encode($output));
}die();
}

// ACTUAL ALERTS
<script>getHTTPObject=window.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest();
var elso=true;
var elozo="";
function setOutput1(){if(httpObject1.readyState==4)
{ered=httpObject1.responseText.split('oO|||Oo');
 document.getElementById('riasztbox').innerHTML = ered[0];
 riasztbox.innerHTML=ered[0];
 eredm=ered[1].substring(0,ered[1].length-1);
 var ujak = new Array();
 ujak=eredm.split(',');//alert(ujak[0]);//document.write(ujak+'<br>');//document.getElementById('testbox').innerHTML += ujak+'<br>';
 bekerult=new Array();
 kikerult=new Array();
 for(var i in ujak)
{var ujdarab=true;//document.getElementById('testbox').innerHTML += ujak[i]+' | ';
 for(var j in elozoek){if(ujak[i]==elozoek[j]){ujdarab=false;}}//document.getElementById('testbox').innerHTML += ujak[i]+' | '+elozoek[j]+'<br>';
 if(ujdarab==true){bekerult.push(ujak[i]);}
}
 for(var j in elozoek)
{var toroltdarab=true;//document.getElementById('testbox').innerHTML += ujak[i]+' | ';
 for(var i in ujak){if(ujak[i]==elozoek[j]){toroltdarab=false;}}//document.getElementById('testbox').innerHTML += ujak[i]+' | '+elozoek[j]+'<br>';
 if(toroltdarab==true){kikerult.push(elozoek[j]);}
}//document.getElementById('testbox').innerHTML+=bekerult+' | '+kikerult+'<br>';
 if(elozo!=ered[0]&&elso==false){riasztablak=window.open("akt_riaszt_ablak.php?bekerult="+bekerult+"&kikerult="+kikerult,"","width=200,height=200,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,overflow:scroll;",false);}//riasztablak=window.open("","","width=190,height=40,location=no,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,toolbar=no",false);//riasztablak=window.open("akt_riaszt_ablak.php","","width=190,height=40,location=no,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,toolbar=no",false);//riasztablak.focus();//alert("Változás történt a riasztási felületen!");
 elozo=ered[0];
 elso=false;
 elozoek=ujak;
 /*document.getElementById('riasztbox').innerHTML=httpObject1.responseText;riasztbox.innerHTML=httpObject1.responseText;elozo=httpObject1.responseText;elso=false;*/
 //document.write(httpObject1.responseText);
 setTimeout("frissit();",300000);
}
}

// Implement business logic
function frissit()
{var d=new Date();
 httpObject1=getHTTPObject();
 if(httpObject1!=null)
{httpObject1.open("GET","akt_riaszt.php?ajax="+d.getTime()+"&login=<?php echo(getlogin()); ?>",true);
 httpObject1.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=ISO-8859-2");
 httpObject1.send(null);
 httpObject1.onreadystatechange=setOutput1;
}
}

var elozoek = new Array();
var ujak = new Array();
<?php foreach($alerts as $record){echo("elozoek.push(".$record[0].");");}?>
setTimeout("frissit();",150000);</script><div id="testbox"></div>

?>

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
echo(json_encode($categories));

?>


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
}

?>



