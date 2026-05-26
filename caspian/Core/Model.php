<?php

namespace Caspian\Core;

class Model
{

    protected \Caspian\River\DatabaseQueryBuilder $db;

    function __construct()
    {
        $this->db = Registry::get('db');
    }
}
