<?php

class Post extends Object {
    public function getUrl() {
        return date("Y/m")."/".$this->url;
    }
}

class Posts extends Table {
    protected $order_by = '`created` DESC';
    protected $meta = array(
        'columns' => array(
            'user_id' => array(
                'type' => 'foreign_key',
                'model' => 'Users', // @todo verify!
            ),
            'title' => array(
                'type' => 'text',
                'required' => true,
            ),
            'url' => array(
                'type' => 'text',
                'required' => true,
            ),
            'content' => array(
                'type' => 'textarea',
                'required' => true,
            ),
            'published' => array(
                'type' => 'datetime',
                'required' => true,
            ),
            // not implemented yet
            'status' => array(
                'type' => 'select',
                'options' => array(
                    'DRAFT',
                    'PUBLISHED',
                    'DELETED',
                ),
            ),
        ),
    );

    /**
     * because so much of these functions are front-end specific, a status of PUBLISH
     * is implicitly assumed unless specifically EXCLUDED in the method name
     */
    public function findRecent($x = 10) {
        return $this->findAll(array(
            "status" => "PUBLISHED",
        ), null, $x);
    }

    public function findByMonthAndUrl($month, $url) {
        $month = str_replace("/", "-", $month);
        return $this->find("`published` LIKE ? AND `url` = ? AND `status` = ?", array(
            "{$month}%",
            $url,
            "PUBLISHED",
        ));
    }
}