<?php

declare(strict_types=1);

namespace Tests\Validation\String;

class StringCharTest extends \PHPUnit\Framework\TestCase {

    public function get_data(): array
    {
        return [
            ['item' => 'Name', 'expected' => TRUE],
            ['item' => 'Morteza', 'expected' => TRUE],
            ['item' => 'Hossein', 'expected' => TRUE],
            ['item' => 41225, 'expected' => TRUE],
            ['item' => 5253, 'expected' => TRUE],
            ['item' => "<>", 'expected' => TRUE],
            ['item' => "\"'!<>+", 'expected' => TRUE],
            ['item' => 25.5, 'expected' => TRUE],
            ['item' => '-', 'expected' => TRUE],
            ['item' => '*', 'expected' => TRUE],
            ['item' => '] / \\', 'expected' => TRUE],
            ['item' => '<', 'expected' => TRUE],
            ['item' => '?', 'expected' => TRUE],
        ];
    }

    /**
     * @dataProvider get_data
     * @param $item
     * @param bool $expected
     */
    public function testCheck($item, bool $expected): void
    {
        $preg_check = (bool) preg_match('/^[a-zA-Z0-9\-._+`{}\"\'?!~@#$%^&*();:\\\<>\/\s\[\]]+$/', (string) $item);

        if ($expected) {
            $this->assertTrue($preg_check);
        } else {
            $this->assertFalse($preg_check);
        }
    }
}
