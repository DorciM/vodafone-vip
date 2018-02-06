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
//<div onclick=function(){history.go(-1)}>VISSZA</div>

// LOGGING
<?php include("akt_riaszt.php");
if((isset($_GET['log']))&&($_GET['log']==2))
{mssql_query("INSERT dbo.logger(datum, login, oldal) VALUES ('".date('Y-m-d H:i:s')."','".getlogin()."','VIP megnyitasa napibol')") or die();}
else {mssql_query("INSERT dbo.logger(datum, login, oldal) VALUES ('".date('Y-m-d H:i:s')."','".getlogin()."','VIP megnyitasa')") or die();}?>

// SEARCH
<form name="kereses" action="keres.php" method="post" target="kozep" onsubmit=function(){document.body.style.cursor='wait';kozep.document.body.style.cursor='wait'}>
<input type="text" name="kulcsszo">
<div onclick=submit()/>
<a href="akt2.php" target="kozep">Napi Info Kereso</a>
</form>

// MENU
<iframe name="kozep" src="kezdo.php"></iframe>
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
//<a href="mailto:CSContentBox@vodafone.hu?subject=V.I.P.&cc=zoltan.gaal@vodafone.com"/>

/*menu.php
<?php require('config.inc.php');
$connection=dbconnect();$getlogin=getlogin();if(($getlogin=='zoltan.gaal')||($getlogin=='daniel.gilan')||($getlogin=='tamas.terenyei')){$vipadmin=true;}else{$vipadmin=false;}if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belso=1;}else{$belso=0;}
$azonositva=false;do{if($belso){$result_user=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva, csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dmzapplf6.ccportal.dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon = csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv = 1)") or die();$result_regisztralt=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dmzapplf6.ccportal.dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1)") or die();}
else{$result_user=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva, csrdw_usergroup_user.usergroup_id FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A INNER JOIN csrdw_usergroup_user ON A.azon = csrdw_usergroup_user.user_id WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1) AND (csrdw_usergroup_user.aktiv = 1)") or die();$result_regisztralt=mssql_query("SELECT A.azon, A.ntlogin, A.megj_nev, A.email, A.utolso_belepes, A.letrehozva FROM ((SELECT * FROM dbo.csrdw_users WHERE ntlogin='".$getlogin."') UNION ALL (SELECT * FROM dbo.csrdw_users_kulso WHERE ntlogin='".$getlogin."')) as A WHERE (A.ntlogin = '".$getlogin."') AND (A.aktiv = 1)") or die();}
if(mssql_num_rows($result_user)>0){while($myrow=mssql_fetch_assoc($result_user)){$user[]=$myrow;}$azonositva=true;}//mssql_query("UPDATE dbo.csrdw_users SET utolso_belepes=".time()." WHERE azon = ".$user[0]['azon']) or die("");
elseif(mssql_num_rows($result_regisztralt)>0){$myrow=mssql_fetch_assoc($result_regisztralt);$user[]=$myrow;$user[0]['usergroup_id']=1;$azonositva=true;}//print_r($user); die();
else{if($belso){$usermaxsql=mssql_query("SELECT MAX(azon) FROM dbo.csrdw_users") or die("");$usermax=mssql_fetch_row($usermaxsql);$ujsorsz=$usermax[0]+1;mssql_query("INSERT INTO dbo.csrdw_users (azon, ntlogin, megj_nev, email, utolso_belepes, aktiv, letrehozva) VALUES(".$ujsorsz.", '".getlogin()."', '".getnev()."', '', ".time().", 1, ".time().")") or die("Invalid query: User l�trehoz�sa 1");}
else{$usermaxsql=mssql_query("SELECT MAX(azon) FROM dbo.csrdw_users_kulso") or die("");$usermax=mssql_fetch_row($usermaxsql);$ujsorsz=$usermax[0]+1;mssql_query("INSERT INTO dbo.csrdw_users_kulso (azon, ntlogin, megj_nev, email, utolso_belepes, aktiv, letrehozva) VALUES(".$ujsorsz.", '".getlogin()."', '".getnev()."', '', ".time().", 1, ".time().")") or die("Invalid query: User l�trehoz�sa 1");}}}
while($azonositva==false); 
//user azonos�t�s (v�g) | Kimenet: $user[]-ben a user adatai (azon, ntlogin, megj_nev, email, utolso_belepes, letrehozva, usergroup_id) t�bb usergroup eset�n t�bb rekord
if(isset($_GET["menu"])){$menu=$_GET["menu"];}
if(isset($_GET["almenu"])){$almenu=$_GET["almenu"];}
//$query="SELECT [CCPortal].[dbo].[csrdw_menu].azon, menu, almenu, nev, tartalom, belso, kulcsszo, jog, tipus, tartalom_id FROM [CCPortal].[dbo].[csrdw_menu] left join [dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[dbo].[csrdw_content_usergroup].tartalom_id WHERE menu='".$menu."' AND almenu='".$almenu."' AND (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND (((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)))";foreach ($user as $sor){$query.="OR ((usergroup_id=".$sor['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";}$query.=") ORDER by nev";
//$query="SELECT [CCPortal].[dbo].[csrdw_menu].azon, menu, almenu, nev, tartalom, belso, kulcsszo, jog, tipus, tartalom_id, [CCPortal].[dbo].[csrdw_usergroups].megj_nev FROM [CCPortal].[dbo].[csrdw_menu] left join [CCPortal].[dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[CCPortal].[dbo].[csrdw_content_usergroup].tartalom_id left join [CCPortal].[dbo].[csrdw_usergroups] on [CCPortal].[dbo].[csrdw_content_usergroup].usergroup_id=[CCPortal].[dbo].[csrdw_usergroups].azon WHERE menu='".$menu."' AND almenu='".$almenu."' AND (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND ( ((usergroup_id is NULL) OR ((usergroup_id is not NULL) AND(csrdw_content_usergroup.aktiv=0)))";foreach ($user as $sor){$query.="OR ((usergroup_id=".$sor['usergroup_id'].")AND(csrdw_content_usergroup.aktiv=1))";}$query.=") ORDER by nev";
$query="SELECT [CCPortal].[dbo].[csrdw_menu].azon, menu, almenu, nev, tartalom, belso, kulcsszo, jog, tipus, tartalom_id, [CCPortal].[dbo].[csrdw_usergroups].azon as csopazon, [CCPortal].[dbo].[csrdw_usergroups].megj_nev FROM [CCPortal].[dbo].[csrdw_menu] left join [CCPortal].[dbo].[csrdw_content_usergroup] on csrdw_menu.azon=[CCPortal].[dbo].[csrdw_content_usergroup].tartalom_id left join [CCPortal].[dbo].[csrdw_usergroups] on [CCPortal].[dbo].[csrdw_content_usergroup].usergroup_id=[CCPortal].[dbo].[csrdw_usergroups].azon WHERE menu='".$menu."' AND almenu='".$almenu."' AND (jog=".$belso." OR jog=".($belso+1).") AND csrdw_menu.aktiv=1 AND ( ((usergroup_id is NULL) OR ((usergroup_id is not NULL)AND(csrdw_content_usergroup.aktiv=0)) OR ((usergroup_id>0) AND(csrdw_content_usergroup.aktiv=1)))) ORDER by nev";//die($query);
$result=mssql_query($query) or die ("Invalid query1");foreach($user as $sor){$jogsi[]=$sor['usergroup_id'];}?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2"><LINK REL="stylesheet" HREF="intranet.css" TYPE="TEXT/CSS"></head>
<body leftmargin="0" topmargin="0">
<script language="javascript" type="text/javascript"><!--
// Get the HTTP Object
function getHTTPObject(){if(window.ActiveXObject)return new ActiveXObject("Microsoft.XMLHTTP");else if(window.XMLHttpRequest)return new XMLHttpRequest();else{alert("Your browser does not support AJAX.");return null;}}
// Change the value of the outputText field
function setOutput(){if(httpObject.readyState==4){}}//document.getElementById('lista').innerHTML = httpObject.responseText;//lista.innerHTML = httpObject.responseText;//alert(httpObject.responseText);
// Implement business logic
function doWork(menu, almenu, nev){var d=new Date();httpObject=getHTTPObject();if(httpObject!=null){
httpObject.open("GET","click_logger.php?nev="+nev+"&menu="+menu+"&almenu="+almenu+"&login=<?php echo(getlogin()); ?>&timestamp="+d.getTime(), true);
httpObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=ISO-8859-2");
httpObject.send(null);httpObject.onreadystatechange = setOutput;}}
var httpObject = null;
function openurlinchrome(link){var shell=new ActiveXObject("WScript.Shell");shell.run("chrome "+link);}
</script>
<?php if(belsos()&&getenv("COMPUTERNAME")!='WEOAPPLF6'){mssql_query("INSERT INTO dbo.kulsobelsohiba_log (login, ido) VALUES ('".getlogin()."', '".date('Y-m-d H:i:s')."')") or die();}?>//echo(getenv("COMPUTERNAME")." ");//echo(belsos()." | ".$belsos);
<table width="100%" cellspacing="0" cellpadding="2" style="background-color:rgb(230,230,255);">
<?php while ($myrow = mssql_fetch_assoc($result)){$talalatok[]=$myrow;}$i=0;while(isset($talalatok[$i])){$forcedchrome=false;unset($csoportok);$myrow=$talalatok[$i];if((!isset($elozoazon))||($myrow['azon']!=$elozoazon)){$elozoazon=$myrow['azon'];$j=$i;$mutat=false;$teszt=0;while((isset($talalatok[$j]))&&($talalatok[$j]['azon']==$talalatok[$i]['azon'])){if($talalatok[$j]['csopazon']!=4){$csoportok[]=$talalatok[$j]['megj_nev'];}if(in_array($talalatok[$j]['csopazon'], $jogsi)){$mutat=true;}if($talalatok[$j]['csopazon']==4){$teszt++;}$j++;}if((isset($csoportok))&&(count($csoportok)==1)&&($csoportok[0]==null)){$mutat=true;}if($mutat==true){if($myrow['tipus']==1){?>//echo($myrow['azon']." | ".$myrow['nev']." | ".$myrow['csopazon']." | ".$myrow['megj_nev']."<br><br>");//die();//print_r($talalatok);//echo($talalatok[$j]['azon']);//echo(count($csoportok));//echo($talalatok[$i]['azon'].' | '); print_r($csoportok); echo('<br>');
<tr>
<td><img src="red_arrowfilled.gif" width="3" height="5" border="0"></td>
<td><a title="<?php if(isset($csoportok)){if($csoportok[0]==""){echo("Mindenki");}else{$cs=1;foreach($csoportok as $csop){if($cs>1){echo(", ");}echo(trim($csop));$cs++;}}}?>" onclick="doWork('<?php echo($myrow['menu']);?>','<?php echo($myrow['almenu']); ?>','<?php echo($myrow['nev']); ?>');" href="<?php if(1==0){echo('logger.php?link=');} if($belso){if((1==0)&&(preg_match("/vodafone\.hu/", $myrow['tartalom']))&&(preg_match("/MSIE 6/", $_SERVER['HTTP_USER_AGENT']))&&(($getlogin=='zoltan.gaal')||($getlogin=='daniel.gilan')||($getlogin=='tamas.terenyei'))){$forcedchrome=true;echo("javascript:openurlinchrome('".$myrow['tartalom']."')");}else{echo($myrow['tartalom']);}}else{echo(str_replace("weoapplf6/ccportal","dmzapplf6",$myrow['tartalom']));}?>" class="blacklink" <?php if(!$forcedchrome){if(!$myrow['belso']){echo ' target="kozep"';}else{echo ' target="_blank"';}} if(($teszt>0)&&($vipadmin==true)){if(isset($csoportok)){echo(' style="color: orange;" ');}else{echo(' style="color: red;" ');}}?>><?php echo $myrow['nev'] ?></a></td>
</tr>
<?php }elseif($myrow['tipus']==2){?>
<tr>
<td><img src="red_arrowfilled.gif" width="3" height="5" border="0"></td>
<td><a title="<?php if(isset($csoportok)){if($csoportok[0]==""){echo("Mindenki");}else{$cs=1;foreach($csoportok as $csop){if($cs>1){echo(", ");}echo(trim($csop));$cs++;}}}?>" onclick="doWork('<?php echo($myrow['menu']); ?>', '<?php echo($myrow['almenu']); ?>', '<?php echo($myrow['nev']); ?>');" href="content.php?content_id=<?php echo($myrow['azon']); ?>" class="blacklink"<?php if(!$myrow['belso']) {echo ' target="kozep"';} else {echo ' target="_blank"';} if(($teszt>0)&&($vipadmin==true)){if(isset($csoportok)){echo(' style="color: orange;" ');}else{echo(' style="color: red;" ');}} ?>><?php echo $myrow['nev'] ?></a></td>
</tr>
<?php }}}$i++;}?>
<tr><td colspan="2" height="2" bgcolor="#FF0000"></td></tr>
<tr><td colspan="2" height="30" bgcolor="#FFFFFF"></td></tr>
</table></body>*/

