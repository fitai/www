<?php
$arg1=array(
	"1" => "Suck ",
	"2" => "it ",
	"3" => "Kyle! "
);
$arg1 = json_encode($arg1);
$arg2=array(
	"1" => "Patrick",
	"2" => "rules"
);
$arg2 = json_encode($arg2);
echo exec('python test.py '.$arg1.' '.$arg2);
?>