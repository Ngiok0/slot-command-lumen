<?php

use App\Console\Commands\SlotCommand;
use App\Http\Controllers\SlotController;

class SlotCommandTest extends TestCase
{
    public const TEST_CLASS = SlotController::class;

    public function test_command_exists()
    {
        $this->assertTrue(class_exists(self::TEST_CLASS));
    }

    /** @test */
    public function test_slot_with_not_matched_paylines()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "J", 3 => "K", 6 => "Q", 9 => "9", 12 => "cat",
                1 => "Q", 4 => "9", 7 => "K", 10 => "Q", 13 => "cat",
                2 => "K", 5 => "Q", 8 => "J", 11 => "K", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([], $payLines);
        $this->assertEquals(0, $totalWin);
    }

    /** @test */
    public function test_slot_with_three_matches_on_one_payline()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "J", 3 => "J", 6 => "J", 9 => "9", 12 => "cat",
                1 => "Q", 4 => "9", 7 => "K", 10 => "Q", 13 => "cat",
                2 => "K", 5 => "Q", 8 => "J", 11 => "K", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([0 => ["0 3 6 9 12" => 3]], $payLines);
        $this->assertEquals(20, $totalWin);
    }

    /** @test */
    public function test_slot_with_four_matches_on_one_payline()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "J", 3 => "J", 6 => "J", 9 => "J", 12 => "cat",
                1 => "Q", 4 => "9", 7 => "K", 10 => "Q", 13 => "cat",
                2 => "K", 5 => "Q", 8 => "J", 11 => "K", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([0 => ["0 3 6 9 12" => 4]], $payLines);
        $this->assertEquals(200, $totalWin);
    }

    /** @test */
    public function test_slot_with_five_matches_on_one_payline()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "J", 3 => "J", 6 => "J", 9 => "J", 12 => "J",
                1 => "Q", 4 => "9", 7 => "K", 10 => "Q", 13 => "cat",
                2 => "K", 5 => "Q", 8 => "cat", 11 => "K", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([0 => ["0 3 6 9 12" => 5]], $payLines);
        $this->assertEquals(1000, $totalWin);
    }

    /** @test */
    public function test_slot_with_three_matches_on_two_paylines()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "J", 3 => "J", 6 => "J", 9 => "9", 12 => "cat",
                1 => "Q", 4 => "J", 7 => "K", 10 => "Q", 13 => "cat",
                2 => "K", 5 => "Q", 8 => "J", 11 => "K", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([
            0 => ["0 3 6 9 12" => 3],
            1 => ["0 4 8 10 12" => 3],
        ], $payLines);
        $this->assertEquals(40, $totalWin);
    }

    /** @test */
    public function test_slot_with_four_matches_on_two_paylines()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "J", 3 => "J", 6 => "J", 9 => "J", 12 => "cat",
                1 => "Q", 4 => "J", 7 => "K", 10 => "K", 13 => "cat",
                2 => "K", 5 => "Q", 8 => "Q", 11 => "Q", 14 => "Q"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([
            0 => ["0 3 6 9 12" => 4],
            1 => ["2 5 8 11 14" => 4],
        ], $payLines);
        $this->assertEquals(400, $totalWin);
    }

    /** @test */
    public function test_slot_with_five_matches_on_two_paylines()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "dog", 3 => "dog", 6 => "dog", 9 => "dog", 12 => "dog",
                1 => "Q", 4 => "K", 7 => "K", 10 => "J", 13 => "Q",
                2 => "cat", 5 => "cat", 8 => "cat", 11 => "cat", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([
            0 => ["0 3 6 9 12" => 5],
            1 => ["2 5 8 11 14" => 5],
        ], $payLines);
        $this->assertEquals(2000, $totalWin);
    }

    /** @test */
    public function test_slot_with_mixed_matches_on_two_paylines()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "monkey", 3 => "dog", 6 => "cat", 9 => "10", 12 => "9",
                1 => "Q", 4 => "J", 7 => "K", 10 => "cat", 13 => "Q",
                2 => "cat", 5 => "cat", 8 => "cat", 11 => "cat", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([
            0 => ["2 5 8 11 14" => 5],
            1 => ["2 4 6 10 14" => 3],
        ], $payLines);
        $this->assertEquals(1020, $totalWin);
    }

    /** @test */
    public function test_slot_with_mixed_matches_on_three_paylines()
    {
        $payLines = $this->invokeMethod(self::TEST_CLASS, 'getMatchedPayLines', [
            'board' => [
                0 => "monkey", 3 => "monkey", 6 => "monkey", 9 => "monkey", 12 => "J",
                1 => "dog", 4 => "Q", 7 => "Q", 10 => "Q", 13 => "J",
                2 => "cat", 5 => "cat", 8 => "cat", 11 => "cat", 14 => "cat"
            ]
        ]);

        $totalWin = $this->invokeMethod(self::TEST_CLASS, 'getTotalWinAmount', [
            'payLines' => $payLines
        ]);

        $this->assertEquals([
            0 => ["0 3 6 9 12" => 4],
            1 => ["1 4 7 10 13" => 3],
            2 => ["2 5 8 11 14" => 5],
        ], $payLines);
        $this->assertEquals(1220, $totalWin);
    }

    protected function invokeMethod(string $className, string $nameMethod, array $params = [])
    {
        $reflection = new ReflectionClass($className);

        $method = $reflection->getMethod($nameMethod);
        $method->setAccessible(true);

        return $method->invokeArgs(new $className, $params);
    }
}
