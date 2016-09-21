<?php
$category = 1;
if ($_REQUEST['cat'])
	$category = $_REQUEST['cat'];

    $entryData = array(
        'category' => $category
      /*, 'title'    => 'Post Title'
      , 'article'  => 'article content goes here'
      , 'when'     => time()*/
    );

    //$pdo->prepare("INSERT INTO blogs (title, article, category, published) VALUES (?, ?, ?, ?)")
       // ->execute($entryData['title'], $entryData['article'], $entryData['category'], $entryData['when']);

    // This is our new stuff
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");

    $socket->send(json_encode($entryData));
	
?>