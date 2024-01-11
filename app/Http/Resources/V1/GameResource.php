<?php

namespace App\Http\Resources\V1;

use DateTime;
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

    /**
     * Get the combination information
     *
     * @param Request $request
     * @return array[]
     * @throws \Exception
     */
    public function getCombinationInformation(Request $request): array
    {
        $attributes = [];
        if (empty($this)) {
            $response = [
                'statusCode' => 400,
                'errorMsg' => 'Game can\'t be found.',
            ];
        } else {
            $combination = $request['combination'];
            $combinationHistory = json_decode($this->combinations, true) ?? [];

            switch ($this->status) {
                case 'L':
                    $response = [
                        'statusCode' => 200,
                        'msg' => 'Game already finished. You lost.',
                    ];
                    break;

                case 'W':
                    $response = [
                        'statusCode' => 200,
                        'msg' => 'Game already finished. You won.',
                    ];
                    break;

                default:
                    $now = date('Y-m-d H:i:s');
                    $createdAtStr = strtotime(
                        '+' . $this->max_time. ' second',
                        strtotime($this->created_at)
                    );
                    $gameMaxTime = date('Y-m-d H:i:s', $createdAtStr);

                    if (strtotime($now) > strtotime($gameMaxTime)) {
                        $combinationHistory[] = $combination;
                        $attributes['combinations'] =
                            json_encode($combinationHistory);
                        $attributes['status'] = 'L';

                        $response = [
                            'statusCode' => 200,
                            'secretNumber' => $this->secret_number,
                            'msg' => 'Game over.',
                        ];
                    } elseif (in_array($combination, $combinationHistory)) {
                        $response = [
                            'statusCode' => 409,
                            'errorMsg' => 'Duplicated combination.',
                        ];
                    } elseif ($combination === $this->secret_number) {
                        $combinationHistory[] = $combination;
                        $attributes['combinations'] =
                            json_encode($combinationHistory);
                        $attributes['status'] = 'W';

                        $response = [
                            'statusCode' => 200,
                            'msg' => 'You win.',
                        ];
                    } else {
                        $combinationHistory[] = $combination;
                        $attributes['combinations'] =
                            json_encode($combinationHistory);
                        $response = [
                            'statusCode' => 200,
                            'tries' => count($combinationHistory),
                            'number' => $combination,
                            'result' => $this->getResult(
                                $combination,
                                $this->secret_number
                            ),
                            'remainingTime' => $this->getRemainingTime(
                                $now,
                                $gameMaxTime
                            ),
                            'evaluation' => $this->getEvaluation(
                                $now,
                                count($combinationHistory)
                            ),
                        ];
                    }
                    break;
            }
        }
        return [
            'attributes' => $attributes,
            'response' => $response,
        ];
    }

    /**
     * Get the result of the combination
     *
     * @param int $combination
     * @param int $secretNumber
     * @return string
     */
    private function getResult(int $combination, int $secretNumber): string
    {
        $combinationArr = preg_split(
            "//",
            $combination,
            -1,
            PREG_SPLIT_NO_EMPTY
        );
        $secretNumberArr = preg_split(
            "//",
            $secretNumber,
            -1,
            PREG_SPLIT_NO_EMPTY
        );
        $bulls = 0;
        $cows = 0;

        foreach ($combinationArr as $pos => $digit) {
            if ($digit === $secretNumberArr[$pos]) {
                $bulls++;
            } elseif (in_array($digit, $secretNumberArr)) {
                $cows++;
            }
        }

        return $bulls . 'T' . $cows . 'V';
    }

    /**
     * Calculate the game remaining time
     *
     * @param string $now
     * @param string $gameMaxTime
     * @return string
     * @throws \Exception
     */
    private function getRemainingTime(string $now, string $gameMaxTime): string
    {
        $diff = $this->getTimeDiff($now, $gameMaxTime);

        if ($diff->h > 0) {
            $labelH = $diff->h == 1 ? ' hour ' : ' hours ';
            $labelI = $diff->i == 1 ? ' minute ' : ' minutes ';
            $labelS = $diff->s == 1 ? ' second' : ' seconds';
            return $diff->h . $labelH . $diff->i . $labelI . $diff->s . $labelS;
        } elseif ($diff->i > 0) {
            $labelI = $diff->i == 1 ? ' minute ' : ' minutes ';
            $labelS = $diff->s == 1 ? ' second' : ' seconds';
            return $diff->i . $labelI . $diff->s . $labelS;
        } elseif ($diff->s > 0) {
            $labelS = $diff->s == 1 ? ' second' : ' seconds';
            return $diff->s . $labelS;
        }

        return 'No remaining time';
    }

    /**
     * Get combination evaluation
     *
     * @param string $now
     * @param int $tries
     * @return float|int
     * @throws \Exception
     */
    private function getEvaluation(string $now, int $tries): float|int
    {
        $diff = $this->getTimeDiff($now, $this->created_at);
        $totalTime = 0;

        if ($diff->h > 0) {
            $totalTime += $diff->h * 3600;
        } elseif ($diff->i > 0) {
            $totalTime += $diff->i * 60;
        } elseif ($diff->s > 0) {
            $totalTime += $diff->s;
        }

        return $totalTime / 2 + $tries;
    }

    /**
     * Get the difference between two times
     *
     * @throws \Exception
     */
    private function getTimeDiff(
        string $now,
        string $gameMaxTime
    ): \DateInterval|bool {
        $startAt = new DateTime($gameMaxTime);
        $endAt = new DateTime($now);

        return $endAt->diff($startAt);
    }
}
