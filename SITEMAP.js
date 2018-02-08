// http://weoapplf6/ccportal/csrdw/
// index.htm
onload=window.open("szputnyik.php?fal=091027","szputnyik","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,left=0,top=0,width=1020,height=708");
// szputnyik.php
<?php header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0",false);header("Pragma: no-cache");
//require_once('config.inc.php');
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belsos=1;}else{$belsos=0;}
if(!function_exists('belsos')){function belsos(){global $belsos;return $belsos;}}
if(belsos()){$CONFIG['web_root']="http://weoapplf6/ccportal/";$CONFIG['file_root']="P:/inetpub/wwwroot/ccportal/";}else{$CONFIG['web_root']="http://dmzapplf6/";$CONFIG['file_root']="P:/inetpub/wwwroot/";}$CONFIG['adatb_szerver']="weoapplf6";$CONFIG['adatb_felhnev']="";$CONFIG['adatb_jelszo']="";$CONFIG['adatb_nev']="CCPortal";$CONFIG['adatb_tabla_menu']="dbo.csrdw_menu";$CONFIG['adatb_tabla_users']="dbo.csrdw_users";$CONFIG['adatb_tabla_usergroups']="dbo.csrdw_usergroups";$CONFIG['adatb_tabla_content_visibility']="dbo.csrdw_content_visibility";
if(!function_exists('getlogin')){function getlogin(){$login=strtolower($_SERVER['REMOTE_USER']);$login=substr(strrchr($login,'\\'),1);return $login;}}
if(!function_exists('getnev')){function getnev(){if(preg_match("/^[a-zA-z0-9]+\.[a-zA-z0-9]+$/",getlogin())){$user=explode(".",getlogin());$nev=ucfirst($user[1])." ".ucfirst($user[0]);return $nev;}else{return getlogin();}}}
if(!function_exists('dbconnect'))
{function dbconnect()
{global $CONFIG;
 $connection=@mssql_connect($CONFIG['adatb_szerver'],$CONFIG['adatb_felhnev'],$CONFIG['adatb_jelszo']) or die ('Az adatbazis nem elerheto.<br>A hiba kijavitasa folyamatban van, kis turelmet kerunk.');
 $db=@mssql_select_db($CONFIG['adatb_nev'],$connection) or die ('Nem erheto el az adatbazis.<br>A hiba kijavitasa folyamatban van, kis turelmet kerunk.');
 if(belsos()){$result=mssql_query("SET ANSI_NULLS ON");$result=mssql_query("SET ANSI_WARNINGS ON");}
 return $connection;
}
}
if(!function_exists('jomail')){function jomail($Email){$result=eregi("^[_a-z0-9-]+[\._a-z0-9-]+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$Email);return $result;}}
if(!function_exists('slashes')){function slashes($data){$data=str_replace("'","''",$data);return $data;}}
?>
//request=new XMLHttpRequest();
//request.open("GET","../Admin/akt_cron.php?ts="+(new Date()).getTime(),true);
//request.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=ISO-8859-2");
//request.send(null);
//request.onreadystatechange=function blank(){};
//request=new XMLHttpRequest();
//request.open("GET","../Admin/cron_csrdw_menu.php?ts="+(new Date()).getTime(),true);
//request.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=ISO-8859-2");
//request.send(null);
//request.onreadystatechange=function blank(){};
showsize=function(){document.getElementById("one").innerHTML=document.body.clientWidth+", "+document.body.clientHeight;};
resize=function(){if(document.body.clientWidth<1015||height<708){setTimeOut("window.resizeTo(1028,746)",200)};};
cookieset=function(){document.cookie='vip=1';};
cookiedelete=function()
{document.cookie = "vip=1; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
 <?php if(!belsos()){?>
 window=window.open("http://dmzapplf6/csrdw/szputnyik.php?fal=091027","_blank","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,left=0,top=0,width=1020,height=708");
 window.name='szputnyik';
 window.opener.close();
 <?php }?>
};

document.cookie = 'vip=2';

// NEVNAP
<div><?php //include_once('nevnaptar_include.php');
$CONFIG['nevnaptar_table']="[CCPortal].[dbo].[nevnaptar]";
$result=mssql_query("SELECT [azon],[nev],[datum] FROM ".$CONFIG['nevnaptar_table']." WHERE [datum]='".date('m-d')."' ORDER BY nev";,dbconnect()) or die();
$elso=true;while($sor=mssql_fetch_assoc($result)){if(!$elso)echo(", ");echo($sor["nev"]);$elso=false;}?>
</div>

// KONTAKT
<div onclick="window.open('mailto:cmucontentbox.hu@vodafone.com?subject=V.I.P.');">Contact</div>

// SITEMAP
<a href="sitemap.php"/>

// PERSONAL
<a href="sajat.php"/>

// BACK
<div onclick=function(){history.go(-1)}>VISSZA</div>

// ALERTS
<?php include("akt_riaszt.php"); ?>

// LOGGING
<?php 
if((isset($_GET['log']))&&($_GET['log']==2)){mssql_query("INSERT dbo.logger(datum, login,oldal) VALUES ('".date('Y-m-d H:i:s')."','".getlogin()."','VIP megnyitasa napibol')") or die();}
else{mssql_query("INSERT dbo.logger(datum,login,oldal) VALUES ('".date('Y-m-d H:i:s')."','".getlogin()."','VIP megnyitasa')") or die();}?>

// SEARCH
<form name="kereses" action="keres.php" method="post" target="kozep" onsubmit=function(){document.body.style.cursor='wait';kozep.document.body.style.cursor='wait'}>
<input type="text" name="kulcsszo">
<div onclick=submit()/>
<a href="akt2.php" target="kozep">Napi Info Kereso</a></form>

<iframe name="kozep" src="kezdo.php"></iframe>

// MENU
load=function(category){document.getElementsByTagName("iframe").map(function(iframe,index){iframe.src="menu.php?menu="+category+"&almenu="+document.getElementsByName("mi")[index].value})}
<div onclick=load('folyamatok')>FOLYAMATOK</div>
<div onclick=load('szolgaltat')>SZOLGALTAT</div>
<div onclick=load('szamlazzzz')>SZAMLAZZZZ</div>
<div onclick=load('szerzodess')>SZERZODESS</div>
<div onclick=load('akcioooooo')>AKCIOOOOOO</div>
<div onclick=load('panaszzzzz')>PANASZZZZZ</div>
<div onclick=load('kulfoldddd')>KULFOLDDDD</div>
<form name="f1"><select name="mi" onchange=eval(this.value)>
<option value="document.all.bf.src=document.forms.h.menu.value+'almenu=Prepaid'" selected>Prepaid</option>
<option value="document.all.bf.src=document.forms.h.menu.value+'almenu=Postpaid'">Postpaid</option>
<option value="document.all.bf.src=document.forms.h.menu.value+'almenu=Corporate'">Corporate</option>
<option value="document.all.bf.src=document.forms.h.menu.value+'almenu=SOHO'">SOHO</option>
<option value="document.all.bf.src='menu.php?menu=egyeb&almenu=Egyeb'">Other</option>
<option value="document.all.bf.src='menu.php?menu=egyeb&almenu=Linkek'">Linkek</option>
<option value="document.all.bf.src='menu.php?menu=egyeb&almenu=Technikai'">Technikai</option>
<?php if(1==1){ ?><option value="document.all.bf.src='menu.php?menu=egyeb&almenu=Dealer info'">Dealer info</option><?php }?>
</select></form>
<iframe name="bf" src="menu.php?menu=folyamatok&almenu=prepaid"></iframe>
[...]
<form name="f4"><select name="mi" onchange=eval(this.value);>
<option value="document.all.ja.src=document.forms.h.menu.value+'almenu=Prepaid'" selected>Prepaid</option>
<option value="document.all.ja.src=document.forms.h.menu.value+'almenu=Postpaid'">Postpaid</option>
<option value="document.all.ja.src=document.forms.h.menu.value+'almenu=Corporate'">Corporate</option>
<option value="document.all.ja.src=document.forms.h.menu.value+'almenu=SOHO'">SOHO</option>
<option value="document.all.ja.src='menu.php?menu=egyeb&almenu=Egyeb'">Other</option>
<option value="document.all.ja.src='menu.php?menu=egyeb&almenu=Linkek'" <?php if(belsos()){echo("selected");} ?>>Linkek</option>
<option value="document.all.ja.src='menu.php?menu=egyeb&almenu=Technikai'">Technikai</option>
<?php if(1==1){ ?><option value="document.all.ja.src='menu.php?menu=egyeb&almenu=Dealer info'" <?php if(!belsos()){echo("selected");} ?>>Dealer info</option><?php }?>
</select></form>
<iframe name="ja" src="menu.php?menu=egyeb&almenu=<?php if(belsos()){echo('linkek');}else{echo('Dealer info');} ?>"></iframe>

// FEEDBACK
<a href="mailto:CSContentBox@vodafone.hu?subject=V.I.P.&cc=zoltan.gaal@vodafone.com"/>

// akt_riaszt.php
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
$query1="SELECT * FROM ".$CONFIG['riaszt_tabla']." LEFT JOIN ccportal.dbo.csrdw_riaszt_usergroup ON ".$CONFIG['riaszt_tabla'].".azon=ccportal.dbo.csrdw_riaszt_usergroup.riaszt_id and ccportal.dbo.csrdw_riaszt_usergroup.aktiv=1 WHERE ".$CONFIG['riaszt_tabla'].".aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_riaszt_usergroup.aktiv=0)))";
if(mssql_num_rows($result_user)!=0){mssql_data_seek($result_user,0);}
if(mssql_num_rows($result_user)>0){while($record=mssql_fetch_assoc($result_user)){$query1.=" OR ((usergroup_id=".$record['usergroup_id'].")AND(csrdw_riaszt_usergroup.aktiv=1))";}}
$query1.=") ORDER BY ".$CONFIG['riaszt_tabla'].".azon DESC";
$result=mssql_query($query1) or die();?><head><title>Riasztások</title></head><div id="riasztbox" style="overflow-x:hidden;overflow-y:scroll;padding:4px;background:E6E6FF;border:3px inset;font-size:12px;font-weight:bold;width:100%;height:82px;text-align:left"><?php 

// ADMIN
if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(belsos()))
{?><div title="Új riasztás" style="border:0px solid red;font-size:14px;font-weight:bold;color:black;width:7%;height:15px;float:right;" onclick="window.open('akt_riaszt.php?function=add_form','_blank','titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,height=390')"><img src="Orb-Blue-Plus-36.gif" style="width:22 px;height:22px;"></div><?php 
}
?><div style="overflow-x:hidden;float:left;width:92%;height:100%;border:0px solid red;"><?php 
$alerts=array();$elozo=0;while($record=mssql_fetch_row($result)){if($record[0]!=$elozo){$alerts[]=$record;$elozo=$record[0];}}
foreach($alerts as $record)
{$nev=explode('.',trim($record[1]));
 echo('<li style="cursor:pointer; list-style:disc inside url(\'1rightarrow-12.gif\');" title="'.ucfirst($nev[1]).' '.ucfirst($nev[0]).' r�gz�tette '.$record[3].trim($record[4]).'-kor." 
 onclick="detaileswindow=window.open(\'akt_riaszt.php?function=details&id='.$record[0].'\',\'_blank\',\' titlebar=no,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,directories=no,width=500,height=370\')">'.$record[2].'</li>');
}?></div></div>

// DELETE
if((isset($_GET["function"]))&&($_GET["function"]=="delete")&&isset($_GET["id"])&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos())){mssql_query("UPDATE ".$CONFIG['riaszt_tabla']." SET aktiv=0,torldatum='".date('Y-m-d')."',torlido='".date('H:i:s')."',torl='".getlogin()."' WHERE azon=".$_GET['id']) or die();}

// ADD_FORM
if((isset($_GET["function"]))&&($_GET["function"]=="add_form")&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos()))
{?><head><title>Új riasztás hozzáadása</title></head><body style="padding:4px;background:rgb(230,230,255);font-size:14px;font-weight:bold;">
 <form action="akt_riaszt.php" method="get"><input type="hidden" name="function" value="add">Cím:<br>
 <input type="text" name="subject" id="subject" maxlength="100" style="width: 100%;"><br><br>Szöveg:<br>
 <textarea name="details" style="width:100%;height:150px"></textarea><br><br>A következő csoportok számára legyen látható:<br><span style="font-weight: normal;">(Ha egyet sem jelölsz be, mindenki látni fogja.)</span><br><br><?php 
 $result=mssql_query("SELECT azon,megj_nev FROM ccportal.dbo.csrdw_usergroups WHERE aktiv=1 AND azon<>1 AND azon<>4",$connection) or die();
 while($record=mssql_fetch_array($result)){echo('<input type="checkbox" name="usergroup[]" value="'.$record["azon"].'">'.$record["megj_nev"].'&nbsp;&nbsp;');}?><br><br><center><input type="submit" value="Hozzáadás"></form><script>document.getElementById('subject').focus();</script></body><?php
 die();
}

// ADD
if((isset($_GET["function"]))&&($_GET["function"]=="add")&&(($currentadmin[2]==2)||($currentadmin[2]==3))&&(belsos()))
{if(isset($_GET["subject"])&&($_GET["subject"]!=""))
{$result=mssql_query("SELECT MAX(azon) FROM ".$CONFIG['riaszt_tabla']) or die();
 $max=mssql_fetch_row($result);
 $max[0]++;
 $subject=wordwrap($_GET["subject"],30,"\n",true);
 mssql_query("INSERT INTO ".$CONFIG['riaszt_tabla']." VALUES (".$max[0].",'".getlogin()."','".$subject."','".date('Y-m-d')."','".date('H:i:s')."',1,'".$_GET['details']."',null,null,null)") or die();
 if((isset($_GET['usergroup']))&&(is_array($_GET['usergroup'])))
{$result=mssql_query("SELECT MAX(azon) FROM ".$CONFIG['riaszt_usergroup_tabla']) or die();
 $max2=mssql_fetch_row($result);
 $query="";
 foreach($_GET['usergroup'] as $record)
{$max2[0]++;mssql_query("INSERT INTO ".$CONFIG['riaszt_usergroup_tabla']." (azon,riaszt_id,usergroup_id,aktiv) VALUES (".$max2[0].",".$max[0].",".$record[0].",1)",$connection)or die();
}
}?><script>window.opener.location.reload();window.close();</script><?php 
}
}

// DETAILS
if((isset($_GET["function"]))&&($_GET["function"]=="details")&&isset($_GET["id"]))
{$result=mssql_query("SELECT * FROM ".$CONFIG['riaszt_tabla']." WHERE aktiv>=0 AND azon=".$_GET["id"]) or die();
 if(mssql_num_rows($result)>0)
{$record=mssql_fetch_row($result);?><head><title><?php echo($record[2]); ?></title></head><body style="padding:4px;padding-top:0px;background:E6E6FF;font-size:14px;font-weight:bold;"><div style="margin-bottom:4px;border-bottom:1px solid black;text-align:center;padding:4px;font-size:14px;font-weight:bold;width:480px;height:20px;overflow:none;"><?php 
 echo($record[1]." rögzítette ".$record[3]." ".$record[4]."-kor:");?></div><div style="padding:4px;font-size:14px;font-weight:bold;width:480px;height:240px;overflow:auto;"><?php 
 echo(nl2br($record[6]));?></div><center><?php 
 $usergroups=mssql_query("SELECT megj_nev FROM csrdw_riaszt_usergroup LEFT JOIN dbo.csrdw_usergroups ON dbo.csrdw_usergroups.azon=csrdw_riaszt_usergroup.usergroup_id WHERE (dbo.csrdw_riaszt_usergroup.riaszt_id =".$_GET['id'].") AND (dbo.csrdw_riaszt_usergroup.aktiv=1) AND (dbo.csrdw_usergroups.aktiv=1)") or die("Invalid query");
 if(mssql_num_rows($usergroups)==0){echo('<p class="heading2" style="font-size: 11px;">A riasztás nincs csoporthoz rendelve (Mindenki láthatja).<br>');}
 else{echo('<p class="heading2" style="color: black; font-size: 11px;">A riasztás a következő csoportokhoz van hozzárendelve (Csak ők láthatják):<br><span style="color:red;">');$i=1;while($grouprecord=mssql_fetch_row($usergroups)){if($i>1){echo(',');}echo(trim($grouprecord[0]));$i++;}}
 echo('</span></p></center>');
 if(($currentadmin[2]==2)||($currentadmin[2]==3)&&(belsos()))
{echo('<center><a href="akt_riaszt.php?function=delete&id='.$record[0].'" style="font-size: 16px; color: red; font-weight: bold; text-decoration: none;">RIASZTÁS TÖRLÉSE</a></center>');
}
}
 else{echo('Nincs ilyen azonosítójú riasztás, vagy nincs jogosultságod a megtekintéséhez.');}die();
}

// ARCHIVEDETAILS
if((isset($_GET["function"]))&&($_GET["function"]=="archivedetails")&&isset($_GET["id"]))
{$result=mssql_query("SELECT * FROM ".$CONFIG['riaszt_tabla']." WHERE azon=".$_GET["id"]) or die();
 $record=mssql_fetch_row($result);?><head><title><?php 
 echo($record[2]);?></title></head><body style="padding:4px;background:rgb(230,230,255);font-size:14px;font-weight:bold;"><div style="padding:4px;font-size:14px;font-weight:bold;width:480px;height:240px;overflow:auto;"><?php 
 echo(nl2br($record[6]));?></div><?php /*if(($currentadmin[2]==2)||($currentadmin[2]==3)){echo('<center><a href="akt_riaszt.php?function=delete&id='.$record[0].'" style="font-size:16px;color:red;font-weight:bold;text-decoration:none;">RIASZTÁS TÖRLÉSE</a></center>');} // if($currentadmin[2]>4) */
 die();
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

// ENNEK A BEFEJEZÉSNEK A SZEREPÉT A TÖMÖRÍTÉS UTÁN SEM ÉRTETTEM, KIHAGYTAM....

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
 $previous=0;
 while($record=mssql_fetch_row($result)){if($record[0]!=$previous){$alerts[]=$record;$previous=$record[0];}}
 echo(json_encode($alerts));
}die();
}?>

<script>
var first=true;
var previous="";
var formers=new Array();
<?php $formers=array();foreach($alerts as $record){array_push($formers,$record[0])};?>

var latters=new Array();
request=window.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest();
request.setRequestHeader("Content-Type","application/json;charset=ISO-8859-2");
request.onreadystatechange=function()
{if(request.readyState==4)
{responses=request.responseText.split('oO|||Oo');
 response=responses[1].substring(0,responses[1].length-1);
 var latters=new Array();
 latters=response.split(',');
 included=new Array();
 disposed=new Array();
 for(var latter in latters){var new=true;for(var former in formers){if(latters[latter]==formers[former]){new=false;}}if(new==true){included.push(latters[latter]);}}
 for(var former in formers){var old=true;for(var latter in latters){if(latters[latter]==formers[former]){old=false;}}if(old==true){disposed.push(formers[former]);}}
 if(previous!=responses[0]&&first==false){riasztablak=window.open("akt_riaszt_ablak.php?bekerult="+included+"&kikerult="+disposed,"","width=200,height=200,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,overflow:scroll;",false);}
 previous=responses[0];
 first=false;
 formers=latters;
 setTimeout("request.send(null);",300000);
}
};
request.open("GET","akt_riaszt.php?ajax="+(new Date()).getTime()+"&login=<?php echo(getlogin()); ?>",true);
setTimeout("request.send(null);",150000);</script><div id="testbox"></div>

// .......EDDIG. 


// sajat.php

<?php header("Content-Type: text/html; charset=ISO-8859-2"); 
require('config.inc.php');
$CONFIG['sajatlink_table']="[CCPortal].[dbo].[sajatlink_new]";
$CONFIG['csrdw_menu_table']="[CCPortal].[dbo].[csrdw_menu]";
$CONFIG['csrdw_content_usergroup_table']="[CCPortal].[dbo].[csrdw_content_usergroup]";
$connection=dbconnect();//if((getlogin()!='zoltan.gaal')&&(getlogin()!='daniel.gilan')){die('Az alkalmazás karbantartás miatt átmenetileg nem érhető el.');}
$getlogin=getlogin();
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belso=1;}else{$belso=0;} 
// AUTHENTICATION
$authenticated=false;
if($belso)
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dmzapplf6.ccportal.dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();
 $result_regisztralt=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dmzapplf6.ccportal.dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1)") or die();
}else
{$result_user=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva,csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon=csrdw_usergroup_user.user_id WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1) AND (csrdw_usergroup_user.aktiv=1)") or die();
 $result_regisztralt=mssql_query("SELECT A.azon,A.ntlogin,A.megj_nev,A.email,A.utolso_belepes,A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin='".$getlogin."') AND (A.aktiv=1)") or die();
}
if(mssql_num_rows($result_user)>0){while($myrow=mssql_fetch_assoc($result_user)){$user[]=$myrow;}/*mssql_query("UPDATE dbo.csrdw_users SET utolso_belepes=".time()." WHERE azon=".$user[0]['azon']) or die("");*/$authenticated=true;}
elseif(mssql_num_rows($result_regisztralt)>0){$myrow=mssql_fetch_assoc($result_regisztralt);$user[]=$myrow;$user[0]['usergroup_id']=1;/*print_r($user); die();*/$authenticated=true;}
else{die("Hiba: Nem regisztrált user");}//print_r($user);
// OPTIONS
if((isset($_GET['ajax']))&&($_GET['ajax']==1))
{$output='<select id="almenu_select" name="almenu" size="1" style="width:250px;" onChange="almenu_valt();"><option>Válassz!!!</option>';
 $menu=iconv("UTF-8","ISO-8859-2",$_GET['menu']);
 $query="SELECT DISTINCT almenu FROM ".$CONFIG['csrdw_menu_table']." left join ".$CONFIG['csrdw_content_usergroup_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['csrdw_content_usergroup_table'].".tartalom_id WHERE (jog=".$belso." OR jog=".($belso+1).") AND menu='".$menu."' AND ".$CONFIG['csrdw_menu_table'].".aktiv=1 AND almenu <> 'Teszt' AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=0)))"; 
 foreach($user as $sor){$query.=" OR ((usergroup_id=".$sor['usergroup_id'].") AND (".$CONFIG['csrdw_content_usergroup_table'].".aktiv=1))";}
 $query.=") ORDER by almenu";//die($query);
 $almenuk=mssql_query($query) or die ("Invalid query1");//$query="SELECT DISTINCT almenu FROM ".$CONFIG['csrdw_menu_table']." WHERE menu='".$_GET['menu']."'";/*die($query);*/$result=mssql_query($query, $connection) or die();
 while($sor=mssql_fetch_assoc($almenuk)){$output.='<option value="'.$sor["almenu"].'">'.$sor["almenu"].'</option>';}
 $output.='</select>';
 echo($output);
 die();
}
if((isset($_GET['ajax']))&&($_GET['ajax']==2))
{$output='<select name="link" size="1" style="width: 250px;">';
 $menu=iconv("UTF-8", "ISO-8859-2", $_GET['menu']);
 $almenu=iconv("UTF-8", "ISO-8859-2", $_GET['almenu']);
 $query="SELECT ".$CONFIG['csrdw_menu_table'].".azon, menu, almenu, nev, tartalom, belso, kulcsszo, jog, tipus, tartalom_id FROM ".$CONFIG['csrdw_menu_table']." left join ".$CONFIG['csrdw_content_usergroup_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['csrdw_content_usergroup_table'].".tartalom_id WHERE (jog=".$belso." OR jog=".($belso+1).") AND menu='".$menu."' AND almenu='".$almenu."' AND ".$CONFIG['csrdw_menu_table'].".aktiv=1 AND almenu <> 'Teszt' AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=0)))";
 foreach($user as $sor){$query.=" OR ((usergroup_id=".$sor['usergroup_id'].")AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=1))";}
 $query.=") ORDER by nev";/*die($query);*/
 $linkek=mssql_query($query) or die ("Invalid query1");
 while($sor=mssql_fetch_assoc($linkek)){$output.='<option value="'.$sor["azon"].'">'.$sor["nev"].'</option>';}
 $output.='</select>';
 echo($output);
 die();
}
// PRIORITIES
if((isset($_GET['function']))&&($_GET['function']=="priorle")&&(isset($_GET['link']))&&(preg_match("/^[0-9]+$/",$_GET['link'])))
{$result=mssql_query("SELECT azon,prioritas FROM ".$CONFIG['sajatlink_table']." WHERE login='".getlogin()."' ORDER BY prioritas",$connection) or die();
 while($sor=mssql_fetch_assoc($result))
{if(isset($eredeti)&&(!isset($kiutott))){$kiutott=$sor;}
 if($sor['azon']==$_GET['link']){$eredeti=$sor;}
}
if(isset($kiutott)){$ujprior=$kiutott['prioritas'];mssql_query("UPDATE ".$CONFIG['sajatlink_table']." SET prioritas=".$eredeti['prioritas']." WHERE azon=".$kiutott['azon']." AND login='".getlogin()."'", $connection) or die();mssql_query("UPDATE ".$CONFIG['sajatlink_table']." SET prioritas=".$ujprior." WHERE azon=".$eredeti['azon']." AND login='".getlogin()."'", $connection) or die();}
}
if((isset($_GET['function']))&&($_GET['function']=="priorfel")&&(isset($_GET['link']))&&(preg_match("/^[0-9]+$/", $_GET['link'])))
{$result=mssql_query("SELECT azon, prioritas FROM ".$CONFIG['sajatlink_table']." WHERE login='".getlogin()."' ORDER BY prioritas DESC", $connection) or die();
 while($sor=mssql_fetch_assoc($result))
{if(isset($eredeti)&&(!isset($kiutott))){$kiutott=$sor;}
 if($sor['azon']==$_GET['link']){$eredeti=$sor;}
}
if(isset($kiutott)){$ujprior=$kiutott['prioritas'];mssql_query("UPDATE ".$CONFIG['sajatlink_table']." SET prioritas=".$eredeti['prioritas']." WHERE azon=".$kiutott['azon']." AND login='".getlogin()."'", $connection) or die();mssql_query("UPDATE ".$CONFIG['sajatlink_table']." SET prioritas=".$ujprior." WHERE azon=".$eredeti['azon']." AND login='".getlogin()."'", $connection) or die();}/*echo("UPDATE ".$CONFIG['sajatlink_table']." SET prioritas=".$eredeti['prioritas']." WHERE azon=".$kiutott['azon']." AND login='".getlogin()."'");*/
}
// TOPLINKS
if(isset($_GET["link"]))
{if($_GET["link"]==0){$msg='Válassz linket!';}else
{if(!isset($_GET["menu"])&&(isset($_GET['function']))&&($_GET['function']=='delete')){mssql_query("DELETE FROM ".$CONFIG['sajatlink_table']." WHERE azon='".$_GET['link']."' AND login='".getlogin()."'",$connection) or die ("HIBA...");}
 elseif((isset($_GET['function']))&&($_GET['function']=='add'))
{$linkek=mssql_query("SELECT * FROM ".$CONFIG['sajatlink_table']." WHERE azon=".$_GET['link']." AND login='".getlogin()."'",$connection) or die ("Invalid query1");
 if(mssql_num_rows($linkek)){$msg='Ezt a linket már korábban hozzáadtad!';}
 else{mssql_query("INSERT INTO ".$CONFIG['sajatlink_table']."([azon],[login],[prioritas]) VALUES (".$_GET['link'].",'".getlogin()."',case when (select MAX([prioritas]) from ".$CONFIG['sajatlink_table']." where [login]='".getlogin()."') is null then 1 else((select MAX([prioritas]) from ".$CONFIG['sajatlink_table']." where [login]='".getlogin()."')+1) end)",$connection) or die ("Invalid query8");$msg='A link hozzáadása sikerült.';}
}
}
}
// PERSONAL LINKS /*case when (select MAX([prioritas]) from [CCPortal].[dbo].[sajatlink_new] where [login]='zoltan.gaal') is null then 1 else (select MAX([prioritas]) from [CCPortal].[dbo].[sajatlink_new] where [login]='zoltan.gaal')+1 end as prioritas*/
if(isset($_GET["sorrend"])){$sorrend=$_GET["sorrend"];}else{$sorrend='prioritas';}
$query="SELECT ".$CONFIG['csrdw_menu_table'].".azon,menu,almenu,nev,tartalom,belso,kulcsszo,jog,tipus,tartalom_id,prioritas FROM ".$CONFIG['csrdw_menu_table']." left join ".$CONFIG['csrdw_content_usergroup_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['csrdw_content_usergroup_table'].".tartalom_id join ".$CONFIG['sajatlink_table']." on ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['sajatlink_table'].".azon and [CCPortal].dbo.sajatlink_new.login='".getlogin()."' WHERE (jog=".$belso." OR jog=".($belso+1).") AND ".$CONFIG['csrdw_menu_table'].".aktiv=1 AND almenu <> 'Teszt' AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=0)))"; 
foreach($user as $sor){$query.="OR ((usergroup_id=".$sor['usergroup_id'].")AND(".$CONFIG['csrdw_content_usergroup_table'].".aktiv=1))";}
$query.=") ORDER by prioritas";/*die($query);*/
$slinkek=mssql_query($query) or die ("Invalid query1");
/*$slinkek=mssql_query("SELECT ".$CONFIG['csrdw_menu_table'].".* FROM ".$CONFIG['csrdw_menu_table'].", ".$CONFIG['sajatlink_table']." WHERE ".$CONFIG['csrdw_menu_table'].".azon=".$CONFIG['sajatlink_table'].".azon AND login='".getlogin()."' ORDER BY ".$sorrend,$connection) or die ("Invalid query2");*/
// TOP10 --><!--<link rel="stylesheet" type="text/css" href="buttonstyles/css/default.css" /><link rel="stylesheet" type="text/css" href="buttonstyles/css/component.css" /><script src="buttonstyles/js/modernizr.custom.js"></script>-->
<!--
if((isset($_GET['view']))&&($_GET['view']=="top10"))
{?><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2"><title>Top10</title><LINK REL="stylesheet" HREF="intranet.css" TYPE="TEXT/CSS">
<style type="text/css">.btn{background:#ffffff;font-family:Vodafone Rg;color:rgb(255,255,255);font-size:14px;font-weight:bold;padding:7px 5px 7px 5px;text-decoration:none;width:120px;height:30px;overflow:hidden;background-image:url("gomb03_blue.gif");}.btn:hover{color:white;text-decoration:none;background-image:url("gomb03_blue_hover.gif");}</style></head><body bgcolor="#FFFFFF" onload='parent.resizeIframe(document.body.scrollHeight)'><center><span style="font-size:14px;font-weight:bold;">Saját TOP10 linked:<br></span><div style="height:30px;margin-bottom:5px;margin-top:5px;border:none;width:100%;"><?php 
 $i=0;while(($myrow=mssql_fetch_assoc($slinkek))&&($i<10))
{if($myrow['tipus']==1)
{?><a class="btn" title="<?php echo($myrow['nev']); ?>" onclick="doWork('<?php echo($myrow['menu']); ?>','<?php echo($myrow['almenu']); ?>','<?php echo($myrow['nev']); ?>');" href="<?php if($belso){echo($myrow['tartalom']);}else{echo(str_replace("weoapplf6/ccportal","dmzapplf6",$myrow['tartalom']));}?>"<?php if(!$myrow['belso']){echo ' target="kozep"';}else{echo ' target="_blank"';}?>><?php 
 if(strlen($myrow['nev'])>11){$pontpontpont="...";}else{$pontpontpont="";}
 echo(str_replace(" ","&nbsp;",substr($myrow['nev'],0,11).$pontpontpont)); ?></a><?php 
}elseif($myrow['tipus']==2)
{?><a title="<?php echo($myrow['nev']); ?>" class="btn" onclick="doWork('<?php echo($myrow['menu']); ?>','<?php echo($myrow['almenu']); ?>','<?php echo($myrow['nev']); ?>');" href="content.php?content_id=<?php echo($myrow['azon']); ?>"<?php if(!$myrow['belso']){echo ' target="kozep"';}else{echo ' target="_blank"';} ?>><?php 
 if(strlen($myrow['nev'])>10){$pontpontpont="...";}else{$pontpontpont="";}
 echo(str_replace(" ","&nbsp;",substr($myrow['nev'],0,10).$pontpontpont)); ?></a><?php 
}$i++;if($i==5){echo('</div><div style="height: 30px;">');}
}?></div><br><a href="sajat.php" target="kozep" style="font-size:10px;color:rgb(0,100,150);font-weight:bold;text-decoration:none;">Ide kattintva szerkesztheted a linkjeidet.</a></center><?php die();
}?><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2"><title>Leírás</title><LINK REL="stylesheet" HREF="intranet.css" TYPE="TEXT/CSS"><style type="text/css">a{font-size:10px;font-weight:bold;color:black;text-decoration:none;}tr.top10 td{color:#a60000;FONT-weight:bold;}tr.top10 td a{color:#a60000;}tr.fejlec td{color:#FFFFFF;}</style></head><body bgcolor="#FFFFFF"><?php 
if(isset($msg)){echo "<script>alert('".$msg."')</script>";}?>
<script>
/*htmlkommentelve..?*/function torol(link,nev){if(window.confirm("Link:\n"+nev+"\n\nBiztos törölni akarod?")){window.open("sajat.php?function=delete&link="+link,target="_self");}}
function getHTTPObject(){if(window.ActiveXObject)return new ActiveXObject("Microsoft.XMLHTTP");else if(window.XMLHttpRequest)return new XMLHttpRequest();else{alert("Your browser does not support AJAX.");return null;}}
function setOutput(){if(httpObject.readyState==4){/*document.getElementById('lista').innerHTML=httpObject.responseText;lista.innerHTML=httpObject.responseText;alert(httpObject.responseText);*/}}
function doWork(menu,almenu,nev)
{var d=new Date();
 httpObject=getHTTPObject();
 if(httpObject!=null)
{httpObject.open("GET","click_logger.php?nev="+nev+"&menu="+menu+"&almenu="+almenu+"&login=<?php echo(getlogin()); ?>&timestamp="+d.getTime(),true);
 httpObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=ISO-8859-2");
 httpObject.send(null);
 httpObject.onreadystatechange=setOutput;
}
}
var httpObject=null;
function setOutput2(){if(httpObject2.readyState==4){document.getElementById('almenu_selector').innerHTML=httpObject2.responseText;almenu_selector.innerHTML=httpObject2.responseText;}}
function fomenu_valt()
{document.getElementById('almenu_selector').innerHTML='<select id="almenu_select" name="almenu" size="1" style="width: 250px;" onChange="almenu_valt();"><option value=""></option></select>';
 var d=new Date();
 httpObject2=getHTTPObject();
 if(httpObject2!=null)
{httpObject2.open("GET","sajat.php?ajax=1&menu="+encodeURIComponent(document.getElementById('fomenu_selector').value)+"&login=<?php echo(getlogin()); ?>&timestamp="+d.getTime(),true);
 httpObject2.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=ISO-8859-2");
 httpObject2.send(null);
 httpObject2.onreadystatechange=setOutput2;
}
}
function setOutput3(){if(httpObject3.readyState==4){document.getElementById('link_selector').innerHTML=httpObject3.responseText;link_selector.innerHTML=httpObject3.responseText;}}
function almenu_valt()
{var d=new Date();
 httpObject3=getHTTPObject();
 if(httpObject3!=null)
{httpObject3.open("GET", "sajat.php?ajax=2&menu="+encodeURIComponent(document.getElementById('fomenu_selector').value)+"&almenu="+encodeURIComponent(document.getElementById('almenu_select').value)+"&login=<?php echo(getlogin()); ?>&timestamp="+d.getTime(), true);
 httpObject3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=ISO-8859-2");
 httpObject3.send(null);
 httpObject3.onreadystatechange=setOutput3;
}
}
</script>
<p align="center" class="heading3red">Saját linkek</p>
<?php if(mssql_num_rows($slinkek))
{?><p><table width="80%" align="center" cellspacing="1" cellpadding="4"><tr bgcolor="#E60000" CLASS="fejlec"><td align="center" width="15%"><b>Menü</b></td><td align="center" width="15%"><b>Almenü</b></td><td align="center" width="55%"><b>Link</b></td><td align="center" width="55%"><b>Sorrend</b></td><td align="center"><span class="txtwhite"><b>Link törlése</b></span></td></tr><?php 
 $i=0;while($myrow=mssql_fetch_assoc($slinkek))
{if($i<10){if($i%2){$bgcolor='#CDCDCD';}else{$bgcolor='#EEEEEE';}}
 else{if($i%2){$bgcolor='#CDCDCD';}else{$bgcolor='#EEEEEE';}}
 ?><tr bgcolor="<?php echo $bgcolor;
 ?>"<?php if($i<10){echo(' class="top10"');}
 ?>><td><?php echo $myrow['menu'] ?></td><td><?php echo $myrow['almenu'] ?></td><td><?php 
 if($myrow['tipus']==1){?><a onclick="doWork('<?php echo($myrow['menu']);?>','<?php echo($myrow['almenu']);?>','<?php echo($myrow['nev']);?>');" href="<?php if($belso){echo($myrow['tartalom']);}else{echo(str_replace("weoapplf6/ccportal", "dmzapplf6", $myrow['tartalom']));}?>" <?php if(!$myrow['belso']) {echo ' target="kozep"';} else {echo ' target="_blank"';}?>><?php echo $myrow['nev'] ?></a><?php }
 elseif($myrow['tipus']==2){?><a onclick="doWork('<?php echo($myrow['menu']); ?>','<?php echo($myrow['almenu']); ?>','<?php echo($myrow['nev']); ?>');" href="content.php?content_id=<?php echo($myrow['azon']); ?>" <?php if(!$myrow['belso']){echo ' target="kozep"';}else{echo ' target="_blank"';}?>><?php echo $myrow['nev'] ?></a><?php }
 ?></td><td align="center"><a href="sajat.php?function=priorfel&link=<?php echo $myrow['azon'] ?>"><img src="triangle_red_up.gif" style="width: 15px; height: 10px; text-decoration: none; border: none;"></a>&nbsp;&nbsp;&nbsp;<a href="sajat.php?function=priorle&link=<?php echo $myrow['azon'] ?>"><img src="triangle_red_down.gif" style="width:15px;height:10px;text-decoration:none;border:none;"></a></td><td align="center"><a href="#" onClick="torol(<?php echo $myrow['azon'] ?>,'<?php echo $myrow['menu'] ?> - <?php echo $myrow['almenu'] ?> - <?php echo $myrow['nev'] ?>')" class="lnkred">Törlés</a></td></tr><?php $i++;
}?></table></p><?php 
}?>
<hr color="red" width="100%">
<p align="center" class="heading2">Új link hozzáadása</p>
<form name="ujlink" method="get">
<table width="60%" align="center" cellspacing="1" cellpadding="1">
<tr bgcolor="#E60000" height="30"><td align="center" colspan="2" class="txtwhite"><b>Új link</b></td></tr>
<tr bgcolor="#EEEEEE"><td align="center"><select name="menu" id="fomenu_selector" size="1" style="width: 250px;" onChange="fomenu_valt();"><option>Válassz!!!</option><?php 
$fomenuk=mssql_query("SELECT distinct [menu] FROM ".$CONFIG['csrdw_menu_table'],$connection) or die();
while($sor=mssql_fetch_assoc($fomenuk)){echo('<option value="'.$sor["menu"].'">'.$sor["menu"].'</option>');}?></select></td></tr>
<tr bgcolor="#EEEEEE"><td align="center"><div id='almenu_selector'><select id='almenu_select' name="almenu" size="1" style="width: 250px;" onChange="almenu_valt();"></select></div></td></tr>
<tr bgcolor="#EEEEEE"><td align="center"><div id='link_selector'><select name="link" size="1" style="width: 250px;"></select></div></td></tr>
<tr bgcolor="#CDCDCD"><td align="center"><input type="submit" value=" Hozzáadás "></td></tr>
<input type="hidden" name="function" value="add"></table></form></body></html>


// KEZDO.PHP
<?php require('config.inc.php');$connection=dbconnect();$nyitott=0;
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belsos=1;}else{$belsos=0;}
$CONFIG["hl_top_questions_main_table"]="[CCPortal].[dbo].[hl_top_questions_main]";
$CONFIG["hl_top_questions_temak_table"]="[CCPortal].[dbo].[hl_top_questions_temak]";
$temalista=mssql_query("SELECT distinct a.[tema] as azon,b.[tema] FROM ".$CONFIG["hl_top_questions_main_table"]." as a LEFT JOIN ".$CONFIG["hl_top_questions_temak_table"]." as b ON a.tema = b.azon WHERE a.[aktiv] = 1",$connection) or die();
?>
<html><head><meta http-equiv="Content-Type" content="text/html;charset=iso-8859-2"><title>Kezdőoldal</title><LINK REL="stylesheet" HREF="csrdw.css" TYPE="TEXT/CSS">
<style>td.xy{font-size:10px;border-width:0px 0px 0px 0px;border-spacing:0px;border-style:solid solid solid solid;border-color:white white white white;border-collapse:collapse;}.btn{display:inline-block;background-color:#ff0000;font-family:Vodafone Rg;color:#ffffff;font-size:14px;font-weight:bold;padding:7px 5px 7px 5px;text-decoration:none;width:120px;height:30px;overflow:hidden;}.felso_btn{display:inline-block;background-image:url("gomb03_200_30_blue.gif");background-size:100%;font-family:Vodafone Rg;color:rgb(255,255,255);font-size:14px;font-weight:bold;padding:7px 5px 7px 5px;text-decoration:none;width:200px;height:30px;overflow:hidden;margin-top:4px;}.felso_btn:hover{background-image:url("gomb03_200_30_blue_hover.gif");}.btn:hover{background:#ff8888;text-decoration:none;}</style>
<script src="jquery-1.12.4.min.js"></script>
<script>
String.prototype.replaceArray=function(find,replace)
{var replaceString=this;
 for(vari=0;i<find.length;i++){replaceString=replaceString.replace(find[i],replace[i]);}
 return replaceString;
};
function utf8_to_latin2_hun(str)
{var find=["\xc3\xb6","\xc3\xbc","\xc3\xb3","\xc5\x91","\xc3\xba","\xc3\xa9","\xc3\xa1","\xc5\xb1","\xc3\xad","\xc3\x96","\xc3\x9c","\xc3\x93","\xc5\x90","\xc3\x9a","\xc3\x89","\xc3\x81","\xc5\xb0","\xc3\x8d"];//var replace=["\xc3\xb6","\xc3\xbc","\xc3\xb3","\xc5\x91","\xc3\xba","\xc3\xa9","\xc3\xa1","\xc5\xb1","\xc3\xad","\xc3\x96","\xc3\x9c","\xc3\x93","\xc5\x90","\xc3\x9a","\xc3\x89","\xc3\x81","\xc5\xb0","\xc3\x8d"];
 var replace=["\xf6","\xfc","\xf3","\xf5","\xfa","\xe9","\xe1","\xfb","\xed","\xd6","\xdc","\xd3","\xd5","\xda","\xc9","\xc1","\xdb","\xcd"];//var find=["\xf6","\xfc","\xf3","\xf5","\xfa","\xe9","\xe1","\xfb","\xed","\xd6","\xdc","\xd3","\xd5","\xda","\xc9","\xc1","\xdb","\xcd"];
 return str.replaceArray(find,replace);
}
function latin2_to_utf8_hun(str)
{var find=["\xf6","\xfc","\xf3","\xf5","\xfa","\xe9","\xe1","\xfb","\xed","\xd6","\xdc","\xd3","\xd5","\xda","\xc9","\xc1","\xdb","\xcd"];//var find=["\xf6","\xfc","\xf3","\xf5","\xfa","\xe9","\xe1","\xfb","\xed","\xd6","\xdc","\xd3","\xd5","\xda","\xc9","\xc1","\xdb","\xcd"];
 var replace=["\xc3\xb6","\xc3\xbc","\xc3\xb3","\xc5\x91","\xc3\xba","\xc3\xa9","\xc3\xa1","\xc5\xb1","\xc3\xad","\xc3\x96","\xc3\x9c","\xc3\x93","\xc5\x90","\xc3\x9a","\xc3\x89","\xc3\x81","\xc5\xb0","\xc3\x8d"];//var replace=["\xc3\xb6","\xc3\xbc","\xc3\xb3","\xc5\x91","\xc3\xba","\xc3\xa9","\xc3\xa1","\xc5\xb1","\xc3\xad","\xc3\x96","\xc3\x9c","\xc3\x93","\xc5\x90","\xc3\x9a","\xc3\x89","\xc3\x81","\xc5\xb0","\xc3\x8d"];
 return str.replaceArray(find,replace);
}
function hl_top_questions_smart_search(){$.ajax(
{type:'POST',
 url:'hl_top_questions/hl_top_questions_include.php',
 data:{'hl_keresoszo':$('#hl_keresoszo').val(),'hl_tema':$('#hl_tema').val(),'hl_sorszam':$('#hl_sorszam').val(),'hl_datum_tol':$('#hl_datum_tol').val(),'hl_datum_ig':$('#hl_datum_ig').val(),'ajax':1},
 error:function(msg){alert('Hiba az ajax kéréskor');},
 success:function(msg){$("#included_hl_top_questions").html(msg);}
});}
function resizeIframe(newHeight){document.getElementById('top10iframe').style.height=parseInt(newHeight,10)+10+'px';}
function mainlinks_link_change()
{document.getElementById('mainlinks_link').href=document.getElementById('mainlinks_chooser').value;
 if(document.getElementById('mainlinks_chooser').value=="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/akt3.php?azon=22319"){document.getElementById('mainlinks_link').target="_blank";}
 else{document.getElementById('mainlinks_link').target="_self";}
}</script></head>
<body bgcolor="#FFFFFF" onload="mainlinks_link_change();">
<select style="width:100%;" onchange="mainlinks_link_change();" id="mainlinks_chooser">
<option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/kuldo.php">Kik�ldhet� anyagok</option>
<option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/ufe_feedback/index.php">UFE Visszajelz�sek</option>
<option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/akt3.php?azon=22319">Hibabejelent�s folyamata</option>
<option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/hl_top_questions/view_archive_posts.php">HL Top Inform�ci�k Arch�v</option></select>
<a id="mainlinks_link" href="" style="text-decoration:none;">>></a>
<div style="width:33.3%;height:100%;float:left;border:none;text-align:center;"><a href="portals.php" class="felso_btn">PORTÁLOK</a></div>
<div style="width:33.3%;height:100%;float:left;border:none;text-align:center;"><a href="contacts.php" class="felso_btn">Visszajelzés, Boxok</a></div>

<center><iframe id="top10iframe" src="sajat.php?view=top10" style="width:100%;height:50px;border:2px solid blue;border-color:rgb(0,100,150);margin-bottom:10px;" frameborder=none></iframe></center>
<center><div style="width:90%;height:25px;font-size:18px;font-weight:bold;background-color:rgb(0,100,150);color:white;text-align:center;margin-bottom:10px;">HelpLine Top Információk</div>
<div style="width:90%;height:45px;border:0px solid green;overflow:hidden;"><div style="width:100%;height:50px;border:0px solid green;overflow:hidden;margin-bottom:-35px;"><div style="width:25%;text-align:center;float:left;">Keresőszó</div><div style="width:25%;text-align:center;float:left;">Téma</div><div style="width:12%;text-align:center;float:right;">Sorszám</div><div style="width:34%;text-align:center;float:right;">Dátum (éééé.nn)</div></div>
<div style="width:100%;height:45px;border:0px solid green;overflow:hidden;clear:both;">
<div style="width:25%;text-align:center;float:left;"><input id="hl_keresoszo" type="text" style="width:95%;height:22px;" onKeyUp="hl_top_questions_smart_search()"></div>
<div style="width:25%;text-align:center;float:left;"><select class="hl_tema" id="hl_tema" style="width:95%;margin-top:1px;" onChange="hl_top_questions_smart_search()"><option value=0></option><?php while($sor=mssql_fetch_assoc($temalista)){echo('<option value='.$sor["azon"].'>'.$sor["tema"].'</option>');}?></select></div>
<div style="width:12%;text-align:center;float:right;"><input type="text" style="width:95%;height:22px;" id="hl_sorszam" onKeyUp="hl_top_questions_smart_search()"></div>
<div style="width:17%;text-align:center;float:right;"><input type="text" style="width:95%;height:22px;" id="hl_datum_ig" onKeyUp="hl_top_questions_smart_search()"></div>
<div style="width:2%;font-weight:bold;text-align:center;float:right;padding-top:6px;">-</div>
<div style="width:17%;text-align:center;float:right;"><input type="text" style="width:95%;height:22px;" id="hl_datum_tol" onKeyUp="hl_top_questions_smart_search()"></div>
</div>
</div>
<div style="width:100%;" id="included_hl_top_questions"><?php include("hl_top_questions/hl_top_questions_include.php");?></div>
</center>
</body>
</html>