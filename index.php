<?php

require "vendor/autoload.php";

//server ip 93.92.242.205:80

$app = new \Slim\Slim();

$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());


$app->get('/test', function() use ($app) {
	
        $app->render(200,array(
                'msg' => 'Test action',
            ));
});

$app->post('/execute/sold/store', function() use ($app) {

  $post = $app->request->post();

  //concatenare i parametri;
  $concat = implode('~', $post);

  $concat_json = json_encode($post);

  exec("WebGiac.exe $concat~");

  $app->render(200,array(
                //'msg' => "File started: $data",
                'msg' => $concat,
            ));
    });

$app->get('/', function() use ($app) {

        $app->render(200,array(
                'msg' => 'Getting action',
            ));
    });

$app->run();

?>
