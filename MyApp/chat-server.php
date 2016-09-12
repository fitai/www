<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;

    require '/var/www/html/MyApp/vendor/autoload.php';

    $server = IoServer::factory(
		new HttpServer(
				new WsServer(
					new Chat()
				)
		),
        8080
    );
	echo "Done loading server";
    $server->run();
