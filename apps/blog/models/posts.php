<?php

class Post extends Object {
    public function getUrl() {
        return date("Y/m", strtotime($this->published))."/".$this->url;
    }

    public function getAuthorDisplayName() {
        $user = Table::factory('Users')->read($this->user_id);
        if ($user) {
            return $user->getDisplayName();
        }
        return "Unknown User";
    }

    public function getApprovedComments() {
        return Table::factory('Comments')->findAll(array(
            'post_id' => $this->getId(),
            'approved' => true,
        ));
    }

    public function getTags() {
        $tags = explode("|", $this->tags);
        if ($tags === false || count($tags) === 0) {
            return array();
        }
        if (current($tags) == "") {
            array_shift($tags);
        }
        if (end($tags) == "") {
            array_pop($tags);
        }
        return $tags;
    }

    public function getTagsAsString() {
        return implode(", ", $this->getTags());
    }
}

class Posts extends Table {
    protected $order_by = '`created` DESC';
    protected $meta = array(
        'columns' => array(
            'user_id' => array(
                'type' => 'foreign_key',
                'table' => 'Users',
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
            'tags' => array(
                'type' => 'text',
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
        ), null, null, $x);
    }

    public function findByMonthAndUrl($month, $url) {
        $month = str_replace("/", "-", $month);
        return $this->find("`published` LIKE ? AND `url` = ? AND `status` = ?", array(
            "{$month}%",
            $url,
            "PUBLISHED",
        ));
    }

    public function findAllForTag($tag) {
        $sql = "`tags` LIKE ? AND `status` = ?";
        $params = array("%|".$tag."|%", "PUBLISHED");

        return $this->findAll($sql, $params);
    }

    public function findAllForTagOrTitle($q, $user_id = null) {
        $sql = "(`tags` LIKE ? OR `title` LIKE ?) AND `status` = ?";
        $params = array("%|".$q."|%", "%".$q."%", "PUBLISHED");

        return $this->findAll($sql, $params);
    }
}
