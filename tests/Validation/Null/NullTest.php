<?php

declare(strict_types=1);

namespace Tests\Validation\Null;

class NullTest extends \PHPUnit\Framework\TestCase {

    public function get_data(): array
    {
        return [
            ['0' => '  ', 'expected' => TRUE],
            ['1' => '', 'expected' => TRUE],
            ['2' => '  a', 'expected' => FALSE],
            ['3' => NULL, 'expected' => TRUE],
            ['4' => 0, 'expected' => TRUE],
            ['5' => ' 0', 'expected' => TRUE],
            ['6' => '0', 'expected' => TRUE],
            ['7' => -1, 'expected' => FALSE],
            ['8' => 10, 'expected' => FALSE],
            ['9' => '10', 'expected' => FALSE],
            ['10' => TRUE, 'expected' => FALSE],
            ['11' => FALSE, 'expected' => TRUE],
            ['12' => [], 'expected' => TRUE],
            ['13' => [1], 'expected' => FALSE],
        ];
    }

    /**
     * @dataProvider get_data
     * @param $item
     * @param bool $expected
     */
    public function testIsNull($item, bool $expected): void
    {
        $is_null = TRUE;

        if (is_null($item)) {
            $is_null = NULL;
        } else if (is_string($item) && !trim($item)) {
            $is_null = NULL;
        } else if (is_numeric($item) && 0 == (int) trim((string) $item)) {
            $is_null = NULL;
        } else if (is_array($item) && !$item) {
            $is_null = NULL;
        } else if (is_bool($item) && $item == FALSE) {
            $is_null = NULL;
        }

        $expected ? $this->assertNull($is_null) : $this->assertNotEmpty($is_null);
    }
}
