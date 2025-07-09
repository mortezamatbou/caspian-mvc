<?php

declare(strict_types=1);

namespace Tests\Validation\String;

class StringStartWithTest extends \PHPUnit\Framework\TestCase {

    public function get_data(): array
    {
        return [
            ['item 1' => 'my_word', 'startWith' => 'my_', 'expected' => TRUE],
            ['item 2' => 'my_word', 'startWith' => 'My_', 'expected' => FALSE],
            ['item 3' => 'my_word', 'startWith' => 'my-', 'expected' => FALSE]
        ];
    }

    public function get_data_2(): array
    {
        return [
            ['item 1' => 'my_word', 'startWith' => 'my_', 'expected' => TRUE],
            ['item 2' => 'my_word', 'startWith' => 'My_', 'expected' => TRUE],
            ['item 3' => 'my_word', 'startWith' => 'my-', 'expected' => FALSE],
            ['item 4' => '09358094771', 'startWith' => '0935', 'expected' => TRUE]
        ];
    }

    /**
     * @dataProvider get_data
     * @param $item
     * @param string $startWith
     * @param bool $expected
     */
    public function testCheck($item, string $startWith, bool $expected): void
    {
        $preg_check = (bool) preg_match("/^{$startWith}[a-zA-Z0-9-]*$/", $item);

        if ($expected) {
            $this->assertTrue($preg_check);
        } else {
            $this->assertFalse($preg_check);
        }
    }

    /**
     * @dataProvider get_data_2
     * @param $item
     * @param string $startWith
     * @param bool $expected
     */
    public function testCheckInsenstive($item, string $startWith, bool $expected): void
    {
        $preg_check = (bool) preg_match("/^{$startWith}[a-zA-Z0-9-]*$/i", $item);

        if ($expected) {
            $this->assertTrue($preg_check);
        } else {
            $this->assertFalse($preg_check);
        }
    }
}
