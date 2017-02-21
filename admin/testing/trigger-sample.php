<?php
$returnArray = "";
$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/pub_sample_data.py -l 1 -a 2", $returnArray); //set to athlete ID 2 for Patrick to test
var_dump($returnArray);
?>