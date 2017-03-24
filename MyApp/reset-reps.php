<?php
$reset = exec('/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/reset_reps.py -c 555');
if ($reset)
	echo "Rep reset success!";
?>
