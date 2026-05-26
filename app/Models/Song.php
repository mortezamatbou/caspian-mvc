<?php

namespace App\Models;

use Caspian\Core\Model;

class Song extends Model
{

    private int $id;
    private array $song;

    function __construct(int $id) {
        parent::__construct();
        $this->id = $id;
    }
    
    public function get_song(): array {
        $result = $this->db->where('id', $this->id)->from('songs')->get();
        $this->song = $result->row_array();
        $this->song['seo_metadata'] = json_decode($this->song['seo_metadata'], TRUE);
        $this->song['files'] = json_decode($this->song['files'], TRUE);
        return $this->song;
    }
}
