<?php
$title = "Lift Summary";
include("/var/www/html/header.php");

$collarID = $_REQUEST['collarID'];
$redisArray = array(
				"collar_id" => $collarID,
				"active" => false
				);
$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/update_redis.py -v -j '".json_encode($redisArray)."'");


$json = '{"key1":"val1", "key2":"val2"}';
$data = json_decode($redisReturn, true);
?>
<h1><?php echo $title; ?></h1>
<div>
	Raw redis return string: <?php print_r($redisReturn); ?>
</div>
<div>
	Parsed string:
	<pre>
		<?php print_r($data); ?>
	</pre>
</div>
<div class="lift-summary-links">
<a href="/now/">Start A New Lift</a>
</div>
<?php
include('/var/www/html/footer.php');
?>
