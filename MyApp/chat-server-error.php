<?php
use Ratchet\Server\IoServer;
use MyApp\Chat;

    require '/var/www/html/MyApp/vendor/autoload.php';

      $server = IoServer::factory(
        new Chat('stopCallback'),
        8081
    );
    echo "Done loading server";
    $server->run();
	
	echo "if the server ever determines it should close, this will be printed.";
	
	// when loop completed, run this function
	function stopCallback() {
		$server->loop->stop();
	}
