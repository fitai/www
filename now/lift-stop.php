<?php
$collarID = $_REQUEST['collarID'];
$redisArray = array(
				"collar_id" => $collarID,
				"active" => false
				);
$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/update_redis.py -v -j '".json_encode($redisArray)."'"); 

print($redisReturn);

?>