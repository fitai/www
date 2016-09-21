<?php
$id = 1;
if ($_REQUEST['id'])
	$id = $_REQUEST['id'];
?>
<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
<script>
    var conn = new ab.Session('ws://52.204.229.101:8081',
        function() {
            conn.subscribe('<?php echo $id; ?>', function(topic, data) {
                // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                console.log('New article published to category "' + topic + '" : ' + data.title);
				document.write('You just received a push for id: ' + topic + '.');
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );
</script>