<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\SlotController;
use Illuminate\Http\JsonResponse;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    $maxGames = (int) request()->input('max_games') ?: 1;

    $result = $maxGames > 1
        ? (new SlotController())->tryToWin($maxGames)
        : (new SlotController())->play();

    return new JsonResponse($result, 200, [], JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
});
