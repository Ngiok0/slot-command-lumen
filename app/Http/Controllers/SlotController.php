<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class SlotController extends Controller
{
    // Size of board
    public const ROWS = 3;
    public const COLUMNS = 5;

    // Amount of bet in cents (1 eur = 100 cents)
    public $bet = 100;

    // Available symbols to display in board
    public $symbols = ['9', '10', 'J', 'Q', 'K', 'A', 'cat', 'dog', 'monkey', 'bird'];

    // Positions of array of winner pay lines
    public $payLines = [
        [0, 3, 6, 9, 12],
        [1, 4, 7, 10, 13],
        [2, 5, 8, 11, 14],
        [0, 4, 8, 10, 12],
        [2, 4, 6, 10, 14],
    ];

    // Amount of returned bet depending on matching symbols
    public $payOut = [
        3 => 0.2,
        4 => 2,
        5 => 10
    ];

    public function play()
    {
        $board    = $this->generateRandomBoard();
        $payLines = $this->getMatchedPayLines($board);
        $totalWin = count($payLines) ? $this->getTotalWinAmount($payLines) : 0;

        $output = collect([
            'board' => collect($board)->values(),
            'paylines' => $payLines,
            'bet_amount' => $this->bet,
            'total_win' => $totalWin
        ]);

        return $output;
    }

    /**
     * It generates a board with randomly picked symbols
     *
     * @return array
     */
    protected function generateRandomBoard(): array
    {
        return collect(range(1, (self::COLUMNS * self::ROWS)))
            ->map(function () {
                return collect($this->symbols)->random(1)->first();
            })->toArray();
    }

    /**
     * It gets the won pay lines of the current board
     *
     * @param array $board
     *
     * @return array
     */
    protected function getMatchedPayLines(array $board): array
    {
        $matches = collect($this->payLines)
            ->map(function ($payLine) use ($board) {
                return $this->checkForWonPayLines($board, $payLine);
            })->toArray();

        $matches = array_filter($matches, 'count'); // Removes not won lines

        return array_values($matches); // Returns resetting key values
    }

    /**
     * It checks if lines of board matches a pay lines
     *
     * @param $board
     * @param $payLine
     *
     * @return array
     */
    protected function checkForWonPayLines(array $board, array $payLine): array
    {
        // It filters the board to get only the items (symbols) of current line
        $line = collect($board)->filter(function ($key) use ($payLine) {
            return in_array($key, $payLine);
        })->toArray();

        // Filters the repeated items to get only the ones that are payable
        $won = collect(array_count_values($line))->filter(function ($times) {
            return isset($this->payOut[$times]) ? $this->payOut[$times] : 0;
        })->first();

        return $won ? [implode(' ', $payLine) => $won] : [];
    }

    /**
     * It returns the total price amount of won pay lines
     *
     * @param array $payLines
     *
     * @return int
     */
    protected function getTotalWinAmount(array $payLines): int
    {
        return (int) collect($payLines)->sum(function ($payLine) {
            $amount = collect($payLine)->first();

            return isset($this->payOut[$amount]) ? $this->payOut[$amount] * $this->bet : 0;
        });
    }
}
