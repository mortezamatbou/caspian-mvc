<?php

declare(strict_types=1);

namespace Tests\Validation;

class Test extends \PHPUnit\Framework\TestCase {

    public function get_data(): array
    {
        return [
            ['item' => '  ', 'expected' => FALSE],
            ['item' => '  a', 'expected' => TRUE]
        ];
    }

    /**
     * @dataProvider get_data
     * @param $item
     * @param bool $expected
     */
    public function testNotNull($item, bool $expected): void
    {
        $check = (bool) trim($item);

        if ($expected) {
            $this->assertNotEmpty($check);
        } else {
            $this->assertEmpty($check);
        }
    }
}
