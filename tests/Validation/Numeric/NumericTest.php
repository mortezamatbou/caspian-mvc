<?php

declare(strict_types=1);

namespace Tests\Validation\Numeric;

class NumericTest extends \PHPUnit\Framework\TestCase {

    public function get_data(): array
    {
        return [
            ['item 1' => 10.0, 'expected' => FALSE],
            ['item 2' => 12.5, 'expected' => FALSE],
            ['item 3' => 421, 'expected' => TRUE],
            ['item 3' => -10, 'expected' => TRUE],
            ['item 3' => -10.5, 'expected' => FALSE],
        ];
    }

    /**
     * @dataProvider get_data
     * @param $item
     * @param bool $expected
     */
    public function testCheckIsInt($item, bool $expected): void
    {
        $check = is_int($item);

        if ($expected) {
            $this->assertTrue($check);
        } else {
            $this->assertFalse($check);
        }
    }

    public function get_data_preg_match(): array
    {
        return [
            ['0' => "10.00101", 'expected' => FALSE],
            ['1' => "10.0", 'expected' => TRUE],
            ['2' => 10.0000, 'expected' => TRUE],
            ['3' => 10.0, 'expected' => TRUE],
            ['4' => -10.0, 'expected' => TRUE],
            ['5' => "-10.0", 'expected' => TRUE],
            ['6' => 12.5, 'expected' => FALSE],
            ['7' => -12.5, 'expected' => FALSE],
            ['8' => "-12.5", 'expected' => FALSE],
            ['9' => 421, 'expected' => TRUE],
            ['10' => -421, 'expected' => TRUE],
            ['11' => "-421", 'expected' => TRUE],
            ['12' => 10.0005, 'expected' => FALSE],
        ];
    }

    /**
     * @dataProvider get_data_preg_match
     * @param $item
     * @param bool $expected
     */
    public function testCheckPregMatch($item, bool $expected): void
    {
        $check = (bool) preg_match('/^-?[0-9]+(\.0+)?$/', (string) $item);

        if ($expected) {
            $this->assertTrue($check);
        } else {
            $this->assertFalse($check);
        }
    }
}
