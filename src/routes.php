<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->post('/ibeacons/find', function (Request $request, Response $response) use ($container) {
        $beacons = array();
        $data = $request->getParsedBody();
        $limit = $data['page'] * 10;
        $offset = (($limit - 10) > 0) ? (($limit - 10) + 1) : ($limit - 10) ;
        $tittle = "";
        if (array_key_exists("tittle", $data)) {
            $tittle = $data['tittle'];
        }
        if ($tittle != "" && $tittle != null) {
            $beacons = $container->get('db')->table('beacons')->offset($offset)->limit($limit)->where('beacon_title', 'like', '%' . $tittle . '%')->get()->toArray();
        } else {
            $beacons = $container->get('db')->table('beacons')->offset($offset)->limit($limit)->get()->toArray();
        }
        $args = array();
        $args["beacons"] = $beacons;
        return $container->get('renderer')->render($response, 'ibeacons.phtml', $args);
    });

    $app->get('/ibeacons/findall', function (Request $request, Response $response) use ($container) {
        $beacons = array();
        $beacons = $container->get('db')->table('beacons')->get()->toArray();
        $args = array();
        $args["beacons"] = $beacons;
        return $container->get('renderer')->render($response, 'ibeacons.phtml', $args);
    });

    $app->post('/login', function (Request $request, Response $response) use ($container) { 
        $data = $request->getParsedBody();
        $user = $data["user"];
        $password = md5($data["password"]);

        $usuario = $container->get('db')->table('login')->limit(1)->where('login_username', $user)->where('login_password', $password)->get()->toArray();
        if (count($usuario) == 1) {
            $autorizado = 1;
        } else {
            $autorizado = 0;
        }
        $args = array();
        $args["autorizado"] = $autorizado;
        return $container->get('renderer')->render($response, 'login.phtml', $args);
    });
};
