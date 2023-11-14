<?php

namespace app\modules\api;

require_once "./module/types/api/test/DbDiffManager.php";

use modules\types\api\output;
use \DbDiffManager;

class DbDb extends \logicCore implements \ModuleInterface
{

    private $main = 'main_db';
    private $second = 'second_db';

    function __construct($getMethodData = [], $requestBodyInfo = [], $postInfo = [])
    {
        $this->getMethodData = $getMethodData;
        $this->requestBodyInfo = $requestBodyInfo;
        $this->postInfo = $postInfo;
        $this->output = new output();
        $this->main = 'main_db';
        $this->second = 'second_db';

    }

    public function tbl_structures()
    {
        $db = new DbDiffManager($this->doDatabase());
        try {
            $db->db($this->main, DbDiffManager::TABLE_MAIN)->db($this->second, DbDiffManager::TABLE_SECOND);
            $diff = $db->diff_tables_structures();
        } catch (\Exception $ex) {
            pre_print($ex->getMessage(), '');
        }
        $this->view('table-diff', ['title' => 'Difference of table structure', 'diff' => $diff['diff']]);
    }

    public function index()
    {

        $db = new DbDiffManager($this->doDatabase());
        $tbl_name = ['main' => $this->main, 'second' => $this->second];
        try {
            $db->db($this->main, DbDiffManager::TABLE_MAIN)->db($this->second, DbDiffManager::TABLE_SECOND);
            $databases = ['main' => DbDiffManager::TABLE_MAIN, 'second' => DbDiffManager::TABLE_SECOND];
            $main = $db->get_tables(DbDiffManager::TABLE_MAIN);
            $second = $db->get_tables(DbDiffManager::TABLE_SECOND);


            if ($main) {
                sort($main);
            }

            if ($second) {
                sort($second);
            }

        } catch (\Exception $ex) {
            pre_print($ex->getMessage(), '');
        }
        $this->view('table-list', ['title' => 'List Of Tables', 'main' => $main, 'second' => $second, 'databases' => $databases, 'tbl_name' => $tbl_name]);
    }

    public function table_diff($tbl)
    {
        $tbl_name = htmlspecialchars(strip_tags($tbl));
        if (!$tbl_name) {
            $this->output->server_error(404, ['error' => 'Bad request']);
        }

        $db = new DbDiffManager($this->doDatabase());
        try {
            $db->db_($this->main, DbDiffManager::TABLE_MAIN, $tbl_name)
                ->db_($this->second, DbDiffManager::TABLE_SECOND, $tbl_name);
            $diff = $db->diff_tables_structures();
        } catch (\Exception $ex) {
            pre_print($ex->getMessage(), '');
        }
        $this->view('table-diff', ['title' => 'Difference of table structure', 'diff' => $diff['diff']]);
    }

    private function view($view_name, $params = [])
    {
        foreach ($params as $k => $v) {
            ${$k} = $v;
        }
        require_once "./module/types/api/test/views/db.header.php";
        require_once "./module/types/api/test/views/$view_name.php";
        require_once "./module/types/api/test/views/db.footer.php";
        exit;
    }

}


/**
 * Router::get('db', 'test.DbDb/index', 'api', []);
 * Router::get('db/structures', 'test.DbDb/tbl_structures', 'api', []);
 * Router::get('db/{tbl:any}', 'test.DbDb/table_diff', 'api', []);
 */