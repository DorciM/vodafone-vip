<div class="helpline-form pb-4">
    <h2>HelpLine top információk</h2>
    <form>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="searchKeyword">Keresőszó</label>
                    <input type="text" class="form-control" id="searchKeyword">
                </div>
                <div class="form-group">
                    <label for="number">Sorszám</label>
                    <input type="text" class="form-control" id="number">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="topics">Témák</label>
                    <select class="form-control" id="topics">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="startDate">Kezdő dátum</label>
                    <input type="text" class="form-control datepicker" id="startDate">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="endDate">Záró dátum</label>
                    <input type="text" class="form-control datepicker" id="endDate">
                </div>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-lg btn-primary">Küldés</button>
        </div>
    </form>
</div>
<!-- KEZDO.PHP -->
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
 return replaceString
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
function hl_top_questions_smart_search(){$.ajax({type:'POST',url:'hl_top_questions/hl_top_questions_include.php',data:{'hl_keresoszo':$('#hl_keresoszo').val(),'hl_tema':$('#hl_tema').val(),'hl_sorszam':$('#hl_sorszam').val(),'hl_datum_tol':$('#hl_datum_tol').val(),'hl_datum_ig':$('#hl_datum_ig').val(),'ajax':1},/*processData:false,contentType:false,*/error:function(msg){alert('Hiba az ajax kéréskor');},success:function(msg){$("#included_hl_top_questions").html(msg);}});}// make sure you respect the same origin policy with this url:http://en.wikipedia.org/wiki/Same_origin_policy //alert($('#hl_keresoszo').val());var formData = "name=ravi&age=31";var request = new FormData();request.append('key',123);request.append('action','getorders');
function resizeIframe(newHeight){document.getElementById('top10iframe').style.height=parseInt(newHeight,10)+10+'px';}
function mainlinks_link_change()
{document.getElementById('mainlinks_link').href=document.getElementById('mainlinks_chooser').value;
 if(document.getElementById('mainlinks_chooser').value=="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/akt3.php?azon=22319"){document.getElementById('mainlinks_link').target="_blank";}
 else{document.getElementById('mainlinks_link').target="_self";}
}</script></head>
<body bgcolor="#FFFFFF" onload="mainlinks_link_change();"><!-- felső legördülők><div style="width:100%;height:40px;border:none;"><div style="width:33.3%;height:100%;float:left;border:none;text-align:center;">Kiemelt linkek:<br><div style="height:28px;width:100%;text-align:center;border:none;"><div style="width:80%;height:100%;color:rgb(230,0,0);border:none;float:left;"><select style="width:100%;" onchange="mainlinks_link_change();" id="mainlinks_chooser"><option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/kuldo.php">Kik�ldhet� anyagok</option><option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/ufe_feedback/index.php">UFE Visszajelz�sek</option><option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/akt3.php?azon=22319">Hibabejelent�s folyamata</option><option value="http://<?php if($belsos){echo("weoapplf6/ccportal");}else{echo("dmzapplf6");}?>/csrdw/hl_top_questions/view_archive_posts.php">HL Top Inform�ci�k Arch�v</option></select></div><a id="mainlinks_link" href="" style="text-decoration:none;"><div style="width:20%;height:22px;background-color:none;border:none;float:left;color:rgb(0,100,150);font-weight:bold;font-size:17px;cursor:pointer;">>></div></a></div></div><div style="width:33.3%;height:100%;float:left;border:none;text-align:center;"><a href="portals.php" class="felso_btn">PORTÁLOK</a></div><div style="width:33.3%;height:100%;float:left;border:none;text-align:center;"><a href="contacts.php" class="felso_btn">Visszajelzés, Boxok</a></div></div></div>-->
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