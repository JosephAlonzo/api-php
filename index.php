<?php
require_once __DIR__ . '/vendor/autoload.php';
include  __DIR__.'/src/Repository/User.php';
include  __DIR__.'/src/Repository/Advisor.php';
include  __DIR__.'/src/Repository/Comptes.php';
include  __DIR__.'/src/Repository/Favorites.php';
include  __DIR__.'/src/Repository/Offers.php';
include  __DIR__.'/src/Repository/Notifications.php';
include  __DIR__.'/src/Repository/Sent.php';
include_once '/home2/josephal/public_html/src/Base/RepositoryBase.php';
include  __DIR__. '/src/Config/DataBase.php';


use App\User\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Psr\Http\Server\RequestHandlerInterface;



$container = (new Routes())->createInstance();
$container->run();

final class Routes
{
    private $user = null;

    public function createInstance()
    {
        $app = AppFactory::create();

        $app->addBodyParsingMiddleware();
        
        
        $app->add(function (Request $request, RequestHandlerInterface $handler): Response {
            $routeContext = RouteContext::fromRequest($request);
            $routingResults = $routeContext->getRoutingResults();
            $methods = $routingResults->getAllowedMethods();
            $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
        
            $response = $handler->handle($request);
        
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
            $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
        
            return $response;
        });
        
        // The RoutingMiddleware should be added after our CORS middleware so routing is performed first
        $app->addRoutingMiddleware();

        $app->group( '/api/logout', function (RouteCollectorProxy $group) {
            $group->get('', App\Repository\User::class);
        });

        $app->group( '/api/user', function (RouteCollectorProxy $group) {
            $group->any('', App\Repository\User::class);
            $group->get('/{id:[0-9]+}', App\Repository\User::class);
            $group->any('/login', App\Repository\User::class);
        });

        $app->group( '/api/advisor', function (RouteCollectorProxy $group) {
            $group->any('', App\Repository\Advisor::class);
            $group->get('/userId/{id:[0-9]+}/byUser', App\Repository\Advisor::class);
            $group->get('/{id:[0-9]+}', App\Repository\Advisor::class);
        });

        $app->group( '/api/comptes', function (RouteCollectorProxy $group) {
            $group->any('', App\Repository\Comptes::class);
            $group->get('/{id:[0-9]+}', App\Repository\Comptes::class);
            $group->get('/userId/{id:[0-9]+}/byUser', App\Repository\Comptes::class); 
        });

        $app->group( '/api/favorites', function (RouteCollectorProxy $group) {
            $group->any('', App\Repository\Favorites::class);
            $group->get('/{id:[0-9]+}', App\Repository\Favorites::class);
            $group->get('/userId/{id:[0-9]+}/byUser', App\Repository\Favorites::class); 
        });

        $app->group( '/api/notifications', function (RouteCollectorProxy $group) {
            $group->any('', App\Repository\Notifications::class);
            $group->get('/{id:[0-9]+}/byUser', App\Repository\Notifications::class);
            $group->get('/{id:[0-9]+}', App\Repository\Notifications::class);
        });

        $app->group( '/api/offers', function (RouteCollectorProxy $group) {
            $group->any('', App\Repository\Offers::class);
            $group->get('/userId/{id:[0-9]+}/byUser', App\Repository\Offers::class);
            $group->get('/{id:[0-9]+}', App\Repository\Offers::class);
        });

        $app->group( '/api/sent', function (RouteCollectorProxy $group) {
            $group->get('', App\Repository\Sent::class);
            $group->post('', App\Repository\Sent::class);
            $group->put('/{id:[0-9]+}', App\Repository\Sent::class);
            $group->delete('', App\Repository\Sent::class);
            $group->options('', App\Repository\Sent::class);
            $group->get('/userId/{id:[0-9]+}/byUser', App\Repository\Sent::class);
            $group->get('/offerId/{id:[0-9]+}/byOffer', App\Repository\Sent::class);
            $group->put('/{id:[0-9]+}/{status:[0-9]+}/{offerId:[0-9]+}/updateStatus', App\Repository\Sent::class);
            $group->options('/{id:[0-9]+}/{status:[0-9]+}/{offerId:[0-9]+}/updateStatus', App\Repository\Sent::class);
            $group->get('/{id:[0-9]+}', App\Repository\Sent::class);
        });

        return $app;
    }
}
