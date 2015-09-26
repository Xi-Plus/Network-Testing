<?php
if(php_sapi_name()!='cli')die("Permission Denied");

require("config.php");
require($config["sql"]);
$result=array();
echo "system os : ".PHP_OS."\r\n";
if(strpos(PHP_OS,"WIN")!==false){
	$parameter="n";
}else if(strpos(PHP_OS,"LINUX")!==false){
	$parameter="c";
}else die("Cannot identify system os.");
while(true){
	foreach($config["domain"] as $domain => $color){
		echo "run\r\n";
		$time=date("Y-m-d H:i:s");
		ob_start();
		system("ping ".$domain." -".$parameter." 10");
		$text=ob_get_contents();
		ob_end_clean();
		preg_match("/(\d*)%/",$text,$match);
		$percent=$match[1];
		$percent=100-$percent;
		echo $time."  ".$domain."  ".$percent."%\r\n";
		$query=new query;
		$query->dbname = "network";
		$query->table = "ping";
		$query->value = array(
			array("time",$time),
			array("domain",$domain),
			array("percent",$percent)
		);
		INSERT($query);
		echo "sleep one\r\n";
		sleep($config["sleeptime"]["one"]);
	}
	echo "sleep all\r\n";
	sleep($config["sleeptime"]["all"]);
}
?>