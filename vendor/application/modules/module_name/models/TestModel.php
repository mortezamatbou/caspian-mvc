<?php

class TestModel extends Model {

    var $prefixes;

    function __construct() {
        parent::__construct();
    }

    function update_category($data) {
        $this->db->update('words', ['category' => $data['category']], ['id' => $data['id']]);
    }

    function get_words($page = 1) {
        $result = $this->db
                        ->from('words')
                        ->order_by('id', 'ASC')
                        ->page($page, PER_PAGE)->get();
        if ($result) {
            return $result;
        }
        return NULL;
    }

    function get_pages_count() {
        $result = $this->db->select('id')->get('words');
        if ($result) {
            return ceil($result->rowCount() / PER_PAGE);
        }
        return 0;
    }

    function get_categorys() {
        $result = $this->db->get("category");
        if ($result) {
            return $result;
        }
        return 0;
    }

    function get_word($id, $prefix_id = 1) {
        $result = $this->db->where('id', $id)->get('words');
        if ($result) {
            return $result->fetch(PDO::FETCH_OBJ);
        }
        return 0;
    }

    function add_word($word) {
        return $this->db->insert('gilfa_words', $word);
    }

    function update_word($word, $id) {
        return $this->db->update('gilfa_words', $word, ['id' => $id]);
    }


    function get_words_count_like($column, $like) {
        $r = $this->db->like($column, $like, 'right')->get('gilfa_words');
        if ($r) {
            return $r->rowCount();
        }
        return 0;
    }

    function get_words_like($prefix_id, $column, $like) {
        $pre_fix = $this->prefixes[$prefix_id]['prefix'];
        $r = $this->db->where($like)->order_by($column, 'ASC')->get('gilfa_words');
        if ($r) {
            return $r->fetchAll(PDO::FETCH_OBJ);
        }
        return 0;
    }


}
