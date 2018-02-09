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