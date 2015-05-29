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
	$concat = $concat . "~";

  $concat_json = json_encode($post);

  exec("WebGiac.exe $concat");

  $app->render(200,array(
                //'msg' => "File started: $data",
                'msg' => $concat,
            ));
    });


		$app->get('/execute/place/order', function() use ($app) {

		  $post = $app->request->post();

		  //concatenare i parametri;
		  //$concat = implode('~', $post);

		  //$concat_json = json_encode($post);


			$order = '{
											"order_number": "1001",
											"date": "2015-05-20",
											"items_count": "3",
											"items": [{
											"sku_id": "1",
											"product_reference": "XXX",
											"color_reference": "XXX",
											"quantity": "1",
											"size": "XXX",
											"purchase_price": "XXX"
											},
											{
											"sku_id": "2",
											"product_reference": "XXX",
											"color_reference": "XXX",
											"quantity": "1",
											"size": "XXX",
											"purchase_price": "XXX"
											},
									{
									"sku_id": "3",
									"product_reference": "XXX",
									"color_reference": "XXX",
									"quantity": "1",
									"size": "XXX",
									"purchase_price": "XXX"
									}]
								}';


			$order_array = json_decode($order, true);

			$str_concat = "~";

			foreach ($order_array['items'] as $item) {

				foreach ($item as $key=>$value) {

					$str_concat .= $value . "~";
				}

			}

		  exec("WebOrder.exe $str_concat");

		  $app->render(200,array(
		                //'msg' => "File started: $data",
		                'msg' => $str_concat,
		            ));
		    });



$app->get('/', function() use ($app) {

        $app->render(200,array(
                'msg' => 'Getting action',
            ));
    });

$app->run();

?>
