<?php
use Ratchet\Server\IoServer;
use MyApp\Chat;

    require '/var/www/html/MyApp/vendor/autoload.php';

      $server = IoServer::factory(
        new Chat(),
        8081
    );

    $server->run();