<?php header("Content-Type:application/json;charset=UTF-8");
require('config.inc.php');
//$connection=dbconnect();
if((getenv("COMPUTERNAME")==='WEOAPPLF6')){$belsos=1;}else{$belsos=0;}
$CONFIG["hl_top_questions_main_table"]="[CCPortal].[dbo].[hl_top_questions_main]";
$CONFIG["hl_top_questions_temak_table"]="[CCPortal].[dbo].[hl_top_questions_temak]";
//$temalista=mssql_query("SELECT distinct a.[tema] as azon,b.[tema] FROM ".$CONFIG["hl_top_questions_main_table"]." as a LEFT JOIN ".$CONFIG["hl_top_questions_temak_table"]." as b ON a.tema = b.azon WHERE a.[aktiv] = 1",$connection) or die();
$topics=array();
//while($record=mssql_fetch_assoc($temalista)){array_push($topics,$record);};
json_encode($topics);
?>