<?php

namespace App\Models;

use Caspian\Model;

class Market extends Model
{

    function __construct() {}

    public function name(): string
    {
        return 'crypto';
    }
}
