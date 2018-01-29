// index.htm
onload=window.open("szputnyik.php?fal=091027","szputnyik","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,left=0,top=0,width=1020,height=708");
// szputnyik.php <?php require_once("config.inc.php"); ?>
request=new XMLHttpRequest();
request.open("GET","../Admin/akt_cron.php?ts="+(new Date()).getTime(),true);
request.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=ISO-8859-2");
request.send(null);
request.onreadystatechange=function blank(){};

request=new XMLHttpRequest();
request.open("GET","../Admin/cron_csrdw_menu.php?ts="+(new Date()).getTime(),true);
request.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=ISO-8859-2");
request.send(null);
request.onreadystatechange=function blank(){};

load=function(button,image){document.forms.h.menu.value="menu.php?menu="+button+"&";eval(document.forms.f[1-4].mi.value);};
back=function(){history.go(-1)};

sthg=funtion(){document.getElementById("one").innerHTML=document.body.clientWidth+", "+document.body.clientHeight;};