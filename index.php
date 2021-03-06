<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require "vendor/autoload.php";

//server
//93.92.242.205:80
//http://eds-7.eds-tema.it/eds-service/

$app = new \Slim\Slim();

$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());


$app->get('/sizes/:id/range', function ($id) use ($app) {

    exec("GetSizesByRange.exe $id");

    $content = file_get_contents('GetSizesByRange.csv');

    $app->render(200, array(
        //'msg' => "File started: $data",
        'msg' => $content,
    ));
});


$app->post('/execute/sold/store', function () use ($app) {

    $post = $app->request->post();

    //concatenare i parametri;
    $concat = implode('~', $post);
    $concat = $concat . "~";

    $concat_json = json_encode($post);

    exec("WebGiac.exe $concat");

    $app->render(200, array(
        //'msg' => "File started: $data",
        'msg' => $concat,
    ));
});


$app->post('/execute/place/order', function () use ($app) {

    set_time_limit(10);

    $post = $app->request->post();

    $log = getLogger('executePlaceOrder');

    $order = $post['order'];

    // $order1 = '{
    // 								"order_number": "1001",
    // 								"date": "2015-05-20",
    // 								"items_count": "3",
    // 								"items": [{
    // 								"sku_id": "1",
    // 								"product_reference": "XXX",
    // 								"color_reference": "XXX",
    // 								"quantity": "1",
    // 								"size": "XXX",
    // 								"purchase_price": "XXX"
    // 								},
    // 								{
    // 								"sku_id": "2",
    // 								"product_reference": "XXX",
    // 								"color_reference": "XXX",
    // 								"quantity": "1",
    // 								"size": "XXX",
    // 								"purchase_price": "XXX"
    // 								},
    // 						{
    // 						"sku_id": "3",
    // 						"product_reference": "XXX",
    // 						"color_reference": "XXX",
    // 						"quantity": "1",
    // 						"size": "XXX",
    // 						"purchase_price": "XXX"
    // 						}]
    // 					}';

    $order_array = json_decode($order, true);

    $str_concat = "~" . $order_array['client'] . "~" . $order_array['order_number'] . "~" . $order_array['date'] . "~";

    foreach ($order_array['items'] as $item) {

        foreach ($item as $key => $value) {

            $str_concat .= $value . "~";
        }

    }

    $log->addNotice('Init WebOrder with ' . $str_concat . ' at ' . date("Y/m/d h:i:sa") . ' for ' . $order_array['client']);


    //shell_exec("WebOrder.exe $str_concat");
    //exec("ElabSeco.bat $str_concat");
    exec("putOrder.bat $str_concat");


    $log->addNotice('Execute WebOrder with ' . $str_concat . ' at ' . date("Y/m/d h:i:sa"));


    $app->render(200, array(
        'msg' => "Order placed successfully with " . $str_concat,
    ));

});

$app->get('/test', function () use ($app) {

    $app->render(200, array(
        'msg' => 'Test action',
    ));
});

$app->get('/', function () use ($app) {

    $app->render(200, array(
        'msg' => 'Getting action',
    ));
});


function getLogger($name)
{
    $log = new Logger($name);
    $log->pushHandler(new StreamHandler('./dev.log', Logger::DEBUG));

    return $log;
}

$app->run();

?>
