<?php
$title = "Testing Suite";
include("/var/www/html/header.php");
?>
<button id="pub-sample-data">Publish Sample Data</button>
<pre>
	<div id="sample-data"></div>
</pre>
<script>
$('#pub-sample-data').click(function() {
	$('#sample-data').html("Loading...");
	$.ajax({
		url: "trigger-sample.php",
		success: function(result)
		{
			$('#sample-data').html(result);
			console.log('Done!');
		}
	});
});
</script>
<?php
include('/var/www/html/footer.php');
?>