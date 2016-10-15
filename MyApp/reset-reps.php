<?php
$reset = exec('/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/reset_reps.py');
if ($reset)
	echo "Rep reset success!";
?>