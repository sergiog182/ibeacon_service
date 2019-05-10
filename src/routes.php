<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/ibeacons', function (Request $request, Response $response) use ($container) {
        $beacons = $container->get('db')->table('beacon')->get()->toArray();
        $args = array();
        $args["beacons"] = $beacons;
        return $container->get('renderer')->render($response, 'ibeacons.phtml', $args);
    });
};
