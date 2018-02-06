<?php header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0",false);header("Pragma: no-cache");
require_once("config.inc.php");?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VIP Grid</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.standalone.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header id="header">
    <div class="logo">
        <img src="images/vodafone-logo.svg" alt="vodafone logo">
        <h1>VIP Portál</h1>
    </div>
    <div class="celebration">
        <span>Mai névnap</span>
        <p>
        <?php $CONFIG['nevnaptar_table']="[CCPortal].[dbo].[nevnaptar]";
        //$result=mssql_query("SELECT [azon],[nev],[datum] FROM ".$CONFIG['nevnaptar_table']." WHERE [datum]='".date('m-d')."' ORDER BY nev",dbconnect()) or die();
        //$first=true;while($sor=mssql_fetch_assoc($result)){if(!$first)echo(", ");echo($sor["nev"]);$first=false;} 
        ?></p>
    </div>
    <form class="form-inline">
        <input type="text" class="form-control" id="quickSearch" placeholder="Gyorskeresés">
        <button type="submit" class="btn btn-primary">
            <img src="images/search.svg" alt="Keresés"/>
        </button>
    </form>
    <div class="advanced-search">
        <a href="#" class="btn btn-lg btn-secondary">Napi Infó Kereső</a>
    </div>
</header>
<main id="content" class="clearfix"></main>
<footer id="footer" class="footer">
    <nav class="nav">
        <a class="nav-link" href="mailto:CSContentBox@vodafone.hu?subject=V.I.P.&cc=zoltan.gaal@vodafone.com">Visszajelzés</a>
        <a class="nav-link" href="mailto:cmucontentbox.hu@vodafone.com?subject=V.I.P.">Kapcsolat</a>
    </nav>
</footer>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
// COOKIE
document.cookie='vip=2';
document.body.onunload=function(){document.cookie="vip=1; expires=Fri, 31 Dec 1999 23:59:59 GMT;";};
content=document.getElementById("content").appendChild(document.createElement("div"));content.setAttribute("class","content-container pt-2");
// ALERT
alert=document.getElementById("content").insertBefore(document.createElement("div"),document.getElementById("content").childNodes[0]);alert.setAttribute("class","alert alert-danger");alert.setAttribute("role","alert");
notices=alert.appendChild(document.createElement("ul"));notices.setAttribute("class","mb-0");
issues=["Papíralapú számlák kézbesítése késik","BD váltás internet ÁFA változás miatt","Vodafone honlap nem működik"];
issues.map(function(notice){notices.appendChild(document.createElement("li")).innerHTML=notice});
// PERSONAL
personal=content.appendChild(document.createElement("div"));personal.setAttribute("class","top-links pb-4");personal.appendChild(document.createElement("h2")).innerHTML="Saját top linkek";
favorites=["Üzletkereső","1SF","Panaszkezelő","Apolló","Eventus","Készüléktár","Nemzetközi számok","Amdocs mobile","Meghatalmazás","Csiribiri"];
favorites.map(function(link){node=document.getElementsByClassName("top-links")[0].appendChild(document.createElement("a"));node.setAttribute("class","btn btn-lg btn-secondary");node.innerHTML=link});
// NAVIGATION
navigation=content.appendChild(document.createElement("div"));navigation.setAttribute("class","selections");navigation.appendChild(document.createElement("h2")).innerHTML="Linkek böngészése";
tabs=document.getElementsByClassName("selections")[0].appendChild(document.createElement("ul"));tabs.setAttribute("id","preselectTab");tabs.setAttribute("class","nav nav-tabs nav-fill");tabs.setAttribute("role","tablist");
menu=document.getElementsByClassName("selections")[0].appendChild(document.createElement("div"));menu.setAttribute("id","preselectTabContent");menu.setAttribute("class","tab-content");
categories=["prepaid","postpaid","soho","corporate","links","other"];
options={service:{alias:"Szolgáltatás",items:[{title:"Autópálya-matrica",scope:[1,2]},{title:"Coca-Cola Free SIM",scope:[1]},{title:"Díjcsomag váltás",scope:[1]},{title:"EGY+EGY GYIK",scope:[1]},{title:"Egyenleginformáció",scope:[1]},{title:"Fix-tárcsázás",scope:[1,2]},{title:"Gyermekzár",scope:[1,2]},{title:"Hangposta",scope:[1]},{title:"CUG aktiválása",scope:[2]}]},
         billing:{alias:"Számlázás",items:[{title:"Feltöltési módok",scope:[1]},{title:"Hívásrészletező és áfás számla igénylése",scope:[1]},{title:"Prepaid opciók felhasználási sorrendje",scope:[1]},{title:"Tarifacsomagok - Adat",scope:[1]},{title:"Tarifacsomagok - Hang",scope:[1]},{title:"Átadási terv",scope:[2]},{title:"Behajtás(Collection) portál",scope:[2]},{title:"Csekkrendező",scope:[2]},{title:"Banki SMS nem érekezik",scope:[2]},{title:"Billing Kisokos",scope:[2]}]},
         flows:{alias:"Folyamatok",items:[{title:"Alkalmazás oldali hibabejelentés",scope:[1,2]},{title:"Tréning Portál",scope:[1,2]}]},
         contract:{alias:"Szerződés",items:[{title:"Adatkezelési hozzájárulások",scope:[1,2]},{title:"Instant kódok",scope:[1]},{title:"Meghatalmazás",scope:[1]},{title:"Számhordozás (mobil)",scope:[1]},{title:"FU rögzítés szabályai",scope:[2]},{title:"Apolló státuszok",scope:[2]},{title:"14 napos elállás",scope:[2]}]},
         promo:{alias:"Akció",items:[{title:"Retention ajánlatok",scope:[1,2]},{title:"Retention portál",scope:[1,2]}]},
         roaming:{alias:"Külföld",items:[{title:"Globális számok",scope:[1,2]},{title:"Nemzetközi díjak",scope:[1,2]},{title:"Nemzetközi telefonszámtartományok, országkódok",scope:[1,2]},{title:"Roaming",scope:[1,2]},{title:"SMS interworking",scope:[1,2]}]},
         complaint:{alias:"Panasz",items:[{title:"Kompenzáció kezelése",scope:[1,2]},{title:"Panaszkezelési segédletek",scope:[1,2]},{title:"Billing Complaint Handling Segédanyagok",scope:[1,2]},{title:"Egyéb szolgáltatások",scope:[1,2]}]},
         links:{alias:"Linkek",items:[{title:"1SF"},{title:"Adatbázisok folder"},{title:"Amdocs Collection"},{title:"Amdocs Mobile"}]},
         other:{alias:"Egyéb",items:[{title:"Aktuális ÁSZF a VIP-en"},{title:"Árlista a honlapon"},{title:"ÁSZF és közlemények"},{title:"Beazonosítás és információ küldés folyamata"}]}};
categories.map(function(category,index)
{tab=tabs.appendChild(document.createElement("li"));tab.setAttribute("class","nav-item");
 node=tab.appendChild(document.createElement("a"));node.setAttribute("class","nav-link main-item");node.setAttribute("id",category+"-tab");node.setAttribute("data-toggle","tab");node.setAttribute("role","tab");node.setAttribute("href","#"+category);node.innerHTML=category.charAt(0).toUpperCase()+(category=="soho"?category.slice(1).toUpperCase():category.slice(1));
 panel=menu.appendChild(document.createElement("div"));panel.setAttribute("id",category);panel.setAttribute("class","tab-pane fade");panel.setAttribute("role","tabpanel");
 miscellaneous=(category=="links"||category=="other");
 if(!miscellaneous){choices=panel.appendChild(document.createElement("ul"));choices.setAttribute("id",category+"Tab");choices.setAttribute("class","nav nav-tabs nav-fill secondary");choices.setAttribute("role","tablist");}
 panes=panel.appendChild(document.createElement("div"));panes.setAttribute("id",category+"TabContent");panes.setAttribute("class","tab-content secondary");
 for(option in options)
{if((option!="promo"&&option!="links"&&option!="other")||(option=="promo"&&category=="postpaid")||option==category)
{if(!miscellaneous){choice=choices.appendChild(document.createElement("li"));choice.setAttribute("class","nav-item");};
 if(!miscellaneous){value=choice.appendChild(document.createElement("a"));value.setAttribute("id","prepaid-"+option+"-tab");value.setAttribute("class","nav-link");value.setAttribute("data-toggle","tab");value.setAttribute("role","tab");value.setAttribute("href","#"+category+option.charAt(0).toUpperCase()+option.slice(1));value.setAttribute("aria-selected","false");value.innerHTML=options[option].alias;};
 if(!miscellaneous||option==category){chart=panes.appendChild(document.createElement("div"));chart.setAttribute("id",category+option.charAt(0).toUpperCase()+option.slice(1));chart.setAttribute("class","tab-pane fade");chart.setAttribute("role","tabpanel")};
 if(!miscellaneous||option==category){list=chart.appendChild(document.createElement("ul"));list.setAttribute("class","link-list card-columns")};
 options[option].items.map(function(item){if(item["scope"]==undefined||(item["scope"].includes(index+1))){node=list.appendChild(document.createElement("li")).appendChild(document.createElement("a"));node.setAttribute("href","#");node.innerHTML=item["title"]}});
}
};
 choices.childNodes[0].childNodes[0].className+=" active";
 panes.childNodes[0].className+=" show active";
});
tabs.childNodes[0].childNodes[0].className+=" active";
menu.childNodes[0].className+=" show active";
// WORKSPACE
workspace=document.createElement("link");workspace.setAttribute("rel","import");workspace.setAttribute("href","helpline.html");workspace.setAttribute("onload","content.insertBefore(workspace.import.documentElement.cloneNode(true),navigation)");document.head.appendChild(workspace);
// FURTHER PORTALS
portals=["http://weoapplf6/ccportal/csrdw/egyéb/amdocstrening/TRÉNING%20PORTÁL%20CUCC/index.html","http://weoapplf6/ccportal/csrdw/redportal/","http://weoapplf6/ccportal/csrdw/promoportal/","http://fleetweb/","http://weoapplf6/ccportal/csrdw/telesalesportal/","http://weoapplf6/ccportal/csrdw/retentionportal/"];
sidebar=document.getElementsByTagName("main")[0].appendChild(document.createElement("div"));sidebar.setAttribute("class","sidebar-container pt-2");
corridor=sidebar.appendChild(document.createElement("div"));corridor.setAttribute("class","portal-links");corridor.appendChild(document.createElement("h2")).innerHTML="Portálok";
portals.map(function(portal,index){gate=corridor.appendChild(document.createElement("a"));gate.setAttribute("href",portal);gate.setAttribute("class","portal-link");gate.appendChild(document.createElement("img")).setAttribute("src","images/portal_"+(index+1)+".png")})
$('.datepicker').datepicker();
</script>
</body>
</html>