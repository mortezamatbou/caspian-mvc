<?php

namespace App\Models;

class User {

    private int $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return 'Morteza';
    }
}
