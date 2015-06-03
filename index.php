<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require "vendor/autoload.php";

//server ip 93.92.242.205:80

$app = new \Slim\Slim();

$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());



$app->post('/execute/sold/store', function() use ($app) {

  $post = $app->request->post();

  //concatenare i parametri;
  $concat = implode('~', $post);
	$concat = $concat . "~";

  $concat_json = json_encode($post);

  exec("WebGiac.exe $concat");

  $app->render(200,array(
                //'msg' => "File started: $data",
                'msg' => $concat,
            ));
    });


		$app->post('/execute/place/order', function() use ($app) {

    //
		//   $post = $app->request->post();
    //
    //   $log = getLogger('executePlaceOrder');
    //
    //   $a = 'a';
    //
    //   //$order = file_get_contents("php://input");
    //
    //   //$order = $post['order'];
    //
		// 	// $order = '{
		// 	// 								"order_number": "1001",
		// 	// 								"date": "2015-05-20",
		// 	// 								"items_count": "3",
		// 	// 								"items": [{
		// 	// 								"sku_id": "1",
		// 	// 								"product_reference": "XXX",
		// 	// 								"color_reference": "XXX",
		// 	// 								"quantity": "1",
		// 	// 								"size": "XXX",
		// 	// 								"purchase_price": "XXX"
		// 	// 								},
		// 	// 								{
		// 	// 								"sku_id": "2",
		// 	// 								"product_reference": "XXX",
		// 	// 								"color_reference": "XXX",
		// 	// 								"quantity": "1",
		// 	// 								"size": "XXX",
		// 	// 								"purchase_price": "XXX"
		// 	// 								},
		// 	// 						{
		// 	// 						"sku_id": "3",
		// 	// 						"product_reference": "XXX",
		// 	// 						"color_reference": "XXX",
		// 	// 						"quantity": "1",
		// 	// 						"size": "XXX",
		// 	// 						"purchase_price": "XXX"
		// 	// 						}]
		// 	// 					}';
    //
    //
    //   //var_dump(file_get_contents("php://input"));
    //
		// 	// $order_array = json_decode($order, true);
    //   //
		// 	// $str_concat = "~" . $order_array['order_number'] . "~" . $order_array['date'] ."~";
    //   //
		// 	// foreach ($order_array['items'] as $item) {
    //   //
		// 	// 	foreach ($item as $key=>$value) {
    //   //
		// 	// 		$str_concat .= $value . "~";
		// 	// 	}
    //   //
		// 	// }
    //
		//   //exec("WebOrder.exe $str_concat");
    //   //exec("ElabSeco.bat $str_concat");
    //
    //
    //   //$log->addNotice('Execute WebOrder with ' . $str_concat . ' at ' . date("Y/m/d h:i:sa"));
    //
		  $app->render(200,array(
		                //'msg' => "File started: $data",
		                //'msg' => $str_concat,
                    'test' = $a,
                    'msg' => $post,
		            ));
		});

        $app->get('/test', function() use ($app) {

                $app->render(200,array(
                        'msg' => 'Test action',
                    ));
        });

$app->get('/', function() use ($app) {

        $app->render(200,array(
                'msg' => 'Getting action',
            ));
    });


    function getLogger($name) {
      $log = new Logger($name);
      $log->pushHandler(new StreamHandler('./dev.log', Logger::DEBUG));

      return $log;
    }

$app->run();

?>
