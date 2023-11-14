<?php

namespace app\modules\api;

use core\Helper\Validation;
use lib\citybank\CityBankTransaction;
use lib\fundsLib\FundsRegister;
use lib\fundsLib\FundsRegisterV2;
use lib\fundsLib\RayanApi;
use lib\profiling\SejamProfilingManager;
use lib\sms\HookMessage;
use module\input\inputLoader\inputLoader;
use modules\types\api\output;

/**
 * Class Tickets
 * @package app\modules\api
 */
class Test extends \logicCore implements \ModuleInterface
{

    private $database;

    function __construct($getMethodData = [], $requestBodyInfo = [], $postInfo = [])
    {
        $this->requestBodyInfo = $requestBodyInfo;
        $this->output = new output();
        $this->database = $this->doDatabase();
    }

    public function aa()
    {
        require_once './lib/citybank/CityBankTransaction.php';
        $city = new CityBankTransaction($this->database, '0101010203');

        $issuance_id = 7;
        $invoke_id = 5;

        $customer_id = 4;
        $client_ip = '127.0.0.1';

//        try {
//            $city->set_invoke($invoke_id, $customer_id);
//            if ($city->is_etebar_afarin('invoke')) {
//                // send invoke to city
//                $city->send_invoke($client_ip);
//            }
//                pre_print('aaa');
//        } catch (\Exception $ex) {
//            // log failed request
//            // dont need to do anything
//        }

        try {
            $city->set_issuance($issuance_id, $customer_id);
            if ($city->is_etebar_afarin('issuance')) {
                # send issuance to city
                pre_print('is etebar afarin');
                $city->send_issuance_online($client_ip);
            }

        } catch (\Exception $ex) {
            // log failed request
            // no need to do anything
        }
    }

    public function debug()
    {
        pre_print('aaaa');
        // 5 0070000050
        // 6 2640104144
        // 7 1630281956
        #                                  in - not
        $this->customer_id = 6;
        $this->national_id = 2640104144; # 2  -  0
        $this->customer_id = 7;
        $this->national_id = 1630281956; # 1  -  1
        $this->customer_id = 5;
        $this->national_id = 0070000050; # 0  -  2
        // $sql = 'SELECT * FROM tbl_users_customers WHERE id=?';
        // $customer = $this->doDatabase()->doSelect($sql, [$this->customer_id], 1);

        // check for profiling
        # # # # register in rayan # # # #
        $rayan_result = $this->rayanFundsRegister();
    }

    private function rayanFundsRegister()
    {
        require './lib/fundsLib/FundsRegister.php';
        $lib = new FundsRegister($this->doDatabase(), $this->national_id, $this->customer_id);
        $customer_register_info = $lib->get_customer_status();
        $overview = $lib->init()->overview();

        $new_register = array_map(function ($row) {
            $res = ['fund_id' => $row['fund_id'], 'new' => TRUE, 'need_update' => 0];
            if ($row['register']) {
                ## SET tbl_funds_update.need_update=1
                $res['new'] = FALSE;
                $res['need_update'] = 1;
            }

            return $res;
        }, $overview['overview']);

        ## register in all funds successfully
//        $overview['register_ok'] = [
//            [
//                'fund_id' => 1,
//                'title' => 'Afarin',
//                'register' => TRUE,
//                'status' => TRUE,
//                'fund_symbol' => 'etebar-afarin',
//                'active' => TRUE,
//            ],
//            [
//                'fund_id' => 2,
//                'title' => 'Saham',
//                'register' => FALSE,
//                'status' => FALSE,
//                'fund_symbol' => 'etebar-afarin',
//                'active' => FALSE,
//            ],
//        ];
//
//        $new_register = [
//            [
//                'fund_id' => 1,
//                'new' => FALSE,
//                'need_update' => 1,
//            ],
//            [
//                'fund_id' => 2,
//                'new' => TRUE,
//                'need_update' => 0,
//            ]
//        ];

        $lib->check_customer_fund($overview['register_ok'], $new_register);
        pre_print(['a', 'b']);

        $customer_register_info = $lib->get_customer_status();
        pre_print($customer_register_info, 'json');

        $body = [
            'funds' => $submit_register['result'],
            'profiling' => $customer_register_info['profiling_status'],
            'status' => $customer_register_info['status'],
            'can_register' => $customer_register_info['can_register']
        ];

        return [
            'status' => $submit_register['status'],
            'body' => $body
        ];

    }

    public function rayanBankAccountId()
    {
        require './lib/fundsLib/RayanApi.php';
        $rayan = new RayanApi($this->doDatabase(), 1);
        $result = $rayan->set_api_version('v1')->get__();
        pre_print($result, 'json');
    }

    public function check()
    {
//        require './lib/fundsLib/RayanApi.php';
//        $rayan = new RayanApi($this->doDatabase(), 1);
//        $result = $rayan->set_api_version('v1')->get_banks();
//        pre_print($result, 'json');


//        $com = new inputLoader('SejamProfilingHandler');
//        $result = $com->run('getBanks', []);
//        pre_print($result, 'json');

        require './lib/fundsLib/RayanApi.php';
        $rayan = new RayanApi($this->doDatabase(), 1);
        // $result = $rayan->set_api_version('v1')->financial_history('0311455344', ['startDate' => '1402/01/20', 'endDate' => '1402/02/17']);
        $result = $rayan->set_api_version('v1')->check_user_in_fund_by_national_id('5769917441'); // 5769917441

        pre_print($result, 'json');

    }


    public function fixTest()
    {
        require './lib/fundsLib/RayanApi.php';
        $rayan = new RayanApi($this->doDatabase(), 1);
        $rayan->set_api_version('v1');
        $r = $rayan->get_customer_info('3770284925');
        // $result = $rayan->set_api_version('v1')->get_branches();
        // $result = $rayan->set_api_version('v1')->get_customers();
        // $result = $rayan->set_api_version('v1')->get_appuser_info();
        // $result = $rayan->set_api_version('v1')->get_customer_gages_info(2640104145);
        pre_print($r, 'json');
    }

    public function reg()
    {
        $a = (int)'12352';
        pre_print($a, 'json');
    }

    public function bb()
    {
        require './lib/fundsLib/RayanApi.php';
        $rayan = new RayanApi($this->doDatabase(), 2);
        $rayan->get_customers();


        exit;

        date_default_timezone_set('Asia/Tehran');
        echo date("Y-m-d H:i:s");

        $time = \globalAcc::getInstance()->time_start();
        $date = date('Y-m-d H:i:s', $time);
        echo $date;
        exit;

        echo jdate('Y-m-d H:i:s', \globalAcc::getInstance()->time_start(), '', 'Asia/Tehran', 'en');
        exit;
        $rayan = new RayanApi($this->doDatabase(), 1);
        $result = $rayan->set_api_version('v1')->get_banks();
        pre_print($result, 'json');
    }

    public function tete()
    {
        require './lib/fundsLib/RayanApi.php';
        $lib = new RayanApi($this->doDatabase(), 1);

        $filters = [
            'startDate' => '1402/05/10',
            'endDate' => '1402/05/10',
            'size' => -1,
            'page' => 1
        ];

        pre_print($lib->get_nav(), 'json');

    }


    public function otpNew()
    {
        require './lib/fundsLib/RayanApi.php';
        require './lib/fundsLib/FundsRegisterV2.php';

        $database = $this->doDatabase();

        $register = new FundsRegisterV2($database, 2640104144, 1265, 1);
        $result = $register->get_kyc_otp();

        pre_print($result, 'json');
    }

    public function otpNewActivate()
    {
        require './lib/fundsLib/RayanApi.php';
        require './lib/fundsLib/FundsRegisterV2.php';

        $otp = isset($this->requestBodyInfo['otp']) && is_numeric($this->requestBodyInfo['otp']) ? $this->requestBodyInfo['otp'] : '';

        if (!$otp) {
            pre_print('Bad Request');
        }

        $database = $this->doDatabase();
        $register = new FundsRegisterV2($database, 2640104144, 1265, 1);
        $result = $register->kyc_activation_request($otp);

        pre_print($result, 'json');
    }


    public function a() {
        require './lib/fundsLib/RayanApi.php';
        $rayan = new RayanApi($this->doDatabase(), 1);
        pre_print($rayan->check_user_in_fund_by_national_id('0015887782'));

    }


}
