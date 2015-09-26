<html>
<?php
require("config.php");
require($config["path"]["sql"]);
?>
<head>
<meta charset="UTF-8">
<title>對外網速檢測系統</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<center>
<h1>對外網速檢測系統</h1>
<?php
function P($x,$olda,$oldb,$newa,$newb){
	return (($x-$olda)*$newb+($oldb-$x)*$newa)/($oldb-$olda);
}
$width=500*2;
$height=135*2;

$start_x=80*2;
$end_x=480*2;
$start_y=15*2;
$end_y=115*2;
$img=ImageCreateTrueColor($width+1,$height+1);
imagefilledrectangle($img,0,0,$width,$height,imagecolorallocate($img,255,255,255));

imageline($img,0,0,$width,0,imagecolorallocate($img,0,0,0));
imageline($img,0,$height,$width,$height,imagecolorallocate($img,0,0,0));
imageline($img,0,0,0,$height,imagecolorallocate($img,0,0,0));
imageline($img,$width,0,$width,$height,imagecolorallocate($img,0,0,0));

$end_time=time();
$start_time=$end_time-60*60;

$count=5;
for($i=0;$i<=$count;$i++){
	imageline($img,$start_x,P($i,0,$count,$start_y,$end_y),$end_x,P($i,0,$count,$start_y,$end_y),imagecolorallocate($img,0,0,0));
	imagettftext($img,10,0,$start_x-60,5+P($i,0,$count,$start_y,$end_y),imagecolorallocate($img,0,0,0),"arial.ttf",round(P($i,0,$count,100,0))."Mbit/s");
}
$count=6;
for($i=0;$i<=$count;$i++){
	imageline($img,P($i,0,$count,$start_x,$end_x),$start_y,P($i,0,$count,$start_x,$end_x),$end_y,imagecolorallocate($img,0,0,0));
	imagettftext($img,10,0,($i*$end_x+($count-$i)*$start_x)/$count-20,$end_y+20,imagecolorallocate($img,0,0,0),"arial.ttf",date("H:i:s",round(P($i,0,$count,$start_time,$end_time))));
}

$query=new query;
$query->dbname = "network";
$query->table = "speedtest";
$query->where = array(
	array("time",date("Y-m-d H:i:s",$start_time),">")
);
$row=SELECT($query);

$point_size=5;
foreach($row as $temp){
	imagefilledellipse($img,P(strtotime($temp["time"]),$start_time,$end_time,$start_x,$end_x),P($temp["upload"],0,100,$end_y,$start_y),$point_size,$point_size,imagecolorallocate($img,255,0,0));
	imagefilledellipse($img,P(strtotime($temp["time"]),$start_time,$end_time,$start_x,$end_x),P($temp["download"],0,100,$end_y,$start_y),$point_size,$point_size,imagecolorallocate($img,0,0,255));
}

imagepng($img,"temp.png");
imagedestroy($img);
?>

<img src="temp.png"><br>
顏色意義
<font color="#0000FF">下載</font>
<font color="#FF0000">上傳</font>
</center>
</body>
</html>
