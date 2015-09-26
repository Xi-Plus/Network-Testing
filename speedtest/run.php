<?php
if(php_sapi_name()!='cli')die("Permission Denied");

require("config.php");
require($config["path"]["sql"]);
$result=array();
while(true){
	echo "run\r\n";
	$time=date("Y-m-d H:i:s");
	ob_start();
	system($config["path"]["python"]." ".$config["path"]["speedtest_cli"]);
	$text=ob_get_contents();
	ob_end_clean();
	preg_match("/Download: (.*?) Mbit\/s/",$text,$match);
	$speed_down=$match[1];
	preg_match("/Upload: (.*?) Mbit\/s/",$text,$match);
	$speed_up=$match[1];
	echo $time."  Down:".$speed_down."  Up:".$speed_up."\r\n";
	$query=new query;
	$query->dbname = "network";
	$query->table = "speedtest";
	$query->value = array(
		array("time",$time),
		array("download",$speed_down),
		array("upload",$speed_up)
	);
	INSERT($query);
	echo "sleep\r\n";
	sleep($config["sleeptime"]);
}
?>