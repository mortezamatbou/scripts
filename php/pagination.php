<?php


class pagination
{

    public $page = 0;
    public $pages = 0;
    public $rows = 0;
    public $per_page = 0;
    public $error = '';

    private $result = [
        'page' => 0,
        'pages' => 0,
        'rows' => 0,
        'per_page' => 10
    ];
    private $sql_limit = [0, 0];

    private $table_name = '';
    private $join = [];

    function init($page, $rows, $per_page = 10)
    {
        $this->page = $page;
        $this->rows = $rows;
        $this->per_page = $per_page;
        $this->result = [
            'rows' => $rows,
            'page' => $page,
            'pages' => 0,
            'per_page' => $per_page
        ];
        $this->calc();
        return $this;
    }

    private function calc()
    {
        if (!$this->rows) {
            return;
        }

        if ($this->rows && $this->rows <= $this->per_page) {
            $this->result['rows'] = $this->rows;
            $this->result['page'] = $this->page;
            $this->result['pages'] = 1;
            $this->pages = 1;
            return;
        }

        $pages = ceil($this->rows / $this->per_page);
        $this->result['pages'] = !$pages ? 1 : $pages;
        $this->pages = $this->result['pages'];

        // calculate sql limit info
        $limit_from = $this->page == 1 ? 0 : (($this->page - 1) * $this->per_page);

        $this->sql_limit = [$limit_from, $this->per_page];
    }

    public function result()
    {
        return $this->result;
    }

    public function sql($limit = true)
    {
        if ($this->pages == 1) {
            return $limit ? '0,' . $this->per_page : $this->sql_limit;
        }

        return $limit ? implode(',', $this->sql_limit) : $this->sql_limit;
    }

}