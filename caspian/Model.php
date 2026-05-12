<?php

namespace Caspian;

class Model
{

    protected \Caspian\CI\DatabaseQueryBuilder $db;

    function __construct()
    {
        $this->db = Registry::get('db');
    }
}
