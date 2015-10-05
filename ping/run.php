<?php
if(php_sapi_name()!='cli')die("Permission Denied");

require("config.php");
require($config["sql"]);
$result=array();
$OS=strtoupper(PHP_OS);
echo "system os : ".$OS."\r\n";
if(strpos($OS,"WIN")!==false){
	$parameter="n";
}else if(strpos($OS,"LINUX")!==false){
	$parameter="c";
}else die("Cannot identify system os.");
for($i=1;$i<=$config["runtimes"]||$config["runtimes"]==0;$i++){
	echo "# ".$i."\n";
	foreach($config["domain"] as $domain => $color){
		$command="ping ".$domain." -".$parameter." ".$config["pingtimes"];
		echo $command."\n";
		$time=date("Y-m-d H:i:s");
		ob_start();
		system($command);
		$text=ob_get_contents();
		ob_end_clean();
		preg_match("/(\d*)%/",$text,$match);
		$percent=$match[1];
		$percent=100-$percent;
		echo $time."  ".$domain."  ".$percent."%\n";
		$query=new query;
		$query->dbname = "network";
		$query->table = "ping";
		$query->value = array(
			array("time",$time),
			array("domain",$domain),
			array("percent",$percent)
		);
		INSERT($query);
	}
	sleep($config["sleeptime"]);
}
?>