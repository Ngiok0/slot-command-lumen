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
     * @param array $board
     * @param array $payLine
     *
     * @return array
     */
    protected function checkForWonPayLines(array $board, array $payLine): array
    {
        // It filters the board to get only the items (symbols) of current line
        $line = collect($board)
            ->filter(fn ($_, $key) => in_array($key, $payLine))
            ->sortKeys()
            ->toArray();

        // Checks if the quantity of symbols in line matches a payout
        if (!array_key_exists(count($line), $this->payOut)) {
            return [];
        }

        // Get first symbol of line to start comparing to the next one
        $firstSymbol = reset($line);

        // Accumulator of matches
        $matches = 0;

        // Check if the first symbol equals the one stored in $firstSymbol
        foreach ($line as $_ => $symbol) {
            if ($symbol === $firstSymbol) {
                $matches++;
            } else {
                if (isset($this->payOut[$matches])) {
                    return [implode(' ', $payLine) => $matches]; // We got enough symbols
                }
                $firstSymbol = $symbol;
                $matches = 1;
            }
        }

        return isset($this->payOut[$matches]) ? [implode(' ', $payLine) => $matches] : [];
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
