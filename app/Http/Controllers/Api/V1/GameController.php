<?php

namespace app\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreGameRequest;
use App\Http\Requests\V1\UpdateGameRequest;
use App\Http\Resources\V1\GameCollection;
use App\Http\Resources\V1\GameResource;
use App\Models\Game;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new GameCollection(Game::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request)
    {
        return (new GameResource(Game::create($request->all())))->getIdentifier();
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        return new GameResource($game);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        $game->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        $game->delete();
    }

    /**
     * Try a combination to guess the secret number
     *
     * @param UpdateGameRequest $request
     * @return array
     * @throws \Exception
     */
    public function tryCombination(UpdateGameRequest $request): array
    {
        $id = $request['id'];
        if (empty($id)) {
            return [
                'code' => 400,
                'message' => 'Missing id attribute',
            ];
        }
        if (empty($request['combination'])) {
            return [
                'code' => 400,
                'message' => 'Missing combination number',
            ];
        }

        $game = Game::find($id);
        $response = (new GameResource($game))
            ->getCombinationInformation($request);

        $game->update($response['attributes']);

        return $response['response'];
    }

    /**
     * Get a previously tried combination
     *
     * @param UpdateGameRequest $request
     * @return array
     * @throws \Exception
     */
    public function getPreviousResponse(UpdateGameRequest $request): array
    {
        $id = $request['id'];
        if (empty($id)) {
            return [
                'code' => 400,
                'message' => 'Missing id attribute',
            ];
        }
        if (empty($request['tryNumber'])) {
            return [
                'code' => 400,
                'message' => 'Missing try number',
            ];
        }

        $game = Game::find($id);
        $response = (new GameResource($game))
            ->getCombinationInformation($request, true);

        return $response['response'];
    }
}
