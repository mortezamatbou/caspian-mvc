<?php

declare(strict_types=1);

namespace Tests\Validation\Numeric;

class GreaterThanTest extends \PHPUnit\Framework\TestCase {

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
}
