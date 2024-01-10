<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'age' => $this->age,
            'maxTime' => $this->max_time,
            'secretNumber' => $this->secret_number,
            'status' => $this->status,
        ];
    }

    /**
     * Transform the resource into an array with only the identifier.
     *
     * @return array<string, mixed>
     */
    public function getIdentifier(): array
    {
        return [
            'identifier' => $this->id,
        ];
    }
}
