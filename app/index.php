<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require __DIR__ . '/../vendor/autoload.php';

require_once './controllers/VentaController.php';
require_once './MiLibreria.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Eloquent
$container=$app->getContainer(); // ?

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['MYSQL_HOST'],
    'database'  => $_ENV['MYSQL_DB'],
    'username'  => $_ENV['MYSQL_USER'],
    'password'  => $_ENV['MYSQL_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Middlewares propios
$mwFotos = function (Request $request, RequestHandler $handler) {

    $handledRequest = $handler->handle($request);

    $cuerpo = $handledRequest->getBody();

    $vector = json_decode($cuerpo, true);
    
    $texto = ListarVectorConFoto($vector);
    
    $response = new Response();

    $response->getBody()->write($texto);

    return $response;
};

$mwFoto = function (Request $request, RequestHandler $handler) {

    $handledRequest = $handler->handle($request);

    $cuerpo = $handledRequest->getBody();

    $objeto = json_decode($cuerpo, true);
    
    $texto = ListarConFoto($objeto);
    
    $response = new Response();

    $response->getBody()->write($texto);

    return $response;
};

$app->get('/ventas', \VentaController::class . ':TraerTodos')->add($mwFotos);
$app->get('/ventas/{id}', \VentaController::class . ':TraerUno')->add($mwFoto);
// $app->get('/ventas/{id}', \VentaController::class . ':TraerUno');
$app->get('/ventas/sabor/{sabor}', \VentaController::class . ':TraerPorSabor');
$app->post('/ventas', \VentaController::class . ':CargarUno');
// $app->put('/ventas/{id}', \VentaController::class . ':ModificarUno');
// $app->delete('/ventas/{id}', \VentaController::class . ':BorrarUno');


// Run app
$app->run();

