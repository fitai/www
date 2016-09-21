<?php
$return = "";
$output = "";
$results = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/www/html/MyApp/test_ws.py -d ");
if ($results)
	echo "Success!";

?>