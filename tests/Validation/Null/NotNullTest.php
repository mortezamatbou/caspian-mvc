<?php

declare(strict_types=1);

namespace Tests\Validation\Null;

class NotNullTest extends \PHPUnit\Framework\TestCase {

    public function get_data(): array
    {
        return [
            ['item' => '  ', 'expected' => FALSE],
            ['item' => '  a', 'expected' => TRUE],
            ['item' => 1, 'expected' => TRUE],
            ['item' => "1 2 ", 'expected' => TRUE],
        ];
    }

    /**
     * @dataProvider get_data
     * @param $item
     * @param bool $expected
     */
    public function testNotNull($item, bool $expected): void
    {
        $check = (bool) trim((string) $item);

        if ($expected) {
            $this->assertNotEmpty($check);
        } else {
            $this->assertEmpty($check);
        }
    }
}
