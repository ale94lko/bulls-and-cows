<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/game/setup', function() {
    $credentials = [
        'email' => 'admin@example.com',
        'password' => 'password',
    ];

    if (!Auth::attempt($credentials)) {
        $user = new User();
        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);

        $user->save();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
            $updateToken = $user->createToken('update-token', ['create', 'update']);
            $basicToken = $user->createToken('basic-token', []);

            return [
                'code' => 200,
                'message' => 'Game setup successfully created.',
                'data' => [
                    'adminToken' => $adminToken->plainTextToken,
                    'updateToken' => $updateToken->plainTextToken,
                    'basicToken' => $basicToken->plainTextToken,
                ],
            ];
        }
    } else {
        return [
            'code' => 200,
            'message' => 'Game already has been configured.',
        ];
    }
});

Route::group(
    [
        'namespace' => 'App\Http\Controllers\Api\V1',
        'middleware' => 'auth:sanctum',
    ],
    function() {
        Route::apiResource('game', GameController::class);
        Route::post('game/tryCombination', 'GameController@tryCombination');
        Route::post(
            'game/getPreviousResponse',
            'GameController@getPreviousResponse'
        );
    }
);
