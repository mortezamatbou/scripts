<?php

class Lobdownium
{
    private int $days = 30; // period of calculate
    private array $trace = []; // detail of day and day trades in each position

    private array $trace_template = [
        'capital' => 0,
        'position_size' => 0,
        'position_status' => TRUE,
        'position_profit_loss' => 0,
        'day' => 1,
        'day_try' => 1
    ];

    private array $day_winrate = [
        'profit' => 0,
        'loss' => 0
    ];

    private $config = [
        'capital' => 100, // fund of account
        'per_position_capital' => 10, // 10% of capital
        'winrate' => 60, // win 60% of day tades
        'winrate_type' => self::WINRATE_INTRADAY, // days: calculate from all days -- intraday: calculate in each day
        'average_position_day' => 10, // open 10 position every day
        'average_position_profit' => 2, // +2% profit on success position
        'average_position_loss' => 2, // -2% profit on failed position
        'position_size' => 0, // current position size
    ];

    const WINRATE_DAY = 'day';
    const WINRATE_INTRADAY = 'intraday';

    function __construct(int $days, array $config = [])
    {
        $this->days = $days;
        if ($config) {
            $this->config = $config;
        }
    }

    public function get_val($name)
    {
        return $this->config[$name];
    }

    public function percent($size, $percent): float
    {
        return $percent * $size / 100;
    }

    public function capital_position_dec()
    {
        $this->config['capital'] -= $this->config['position_size'];
    }

    public function open_position()
    {
        $capital = $this->config['capital'];
        $per = $this->config['per_position_capital'];
        $pos = $capital / $per;
        $this->config['position_size'] = $pos;
        return $this;
    }

    private function decision_position()
    {
        $this->check_winrate(rand(1, 2) == 1 ?? FALSE);
    }

    private function check_winrate($position_status)
    {
        $profit = $this->day_winrate['profit'];
        $loss = $this->day_winrate['loss'];
    }

    public function start()
    {
        for ($i = 0; $i < $this->days; $i++) {
            for ($j = 0; $j < $this->config['average_position_day']; $j++) {
                $this->open_position()->capital_position_dec();
                $this->decision_position();
            }
            $this->reset_day_winrate();
        }
    }

    private function reset_day_winrate()
    {
        $this->day_winrate = [
            'profit' => 0,
            'loss' => 0
        ];
        return $this;
    }

}

$config = [
    'capital' => 100, // fund of account
    'per_position_capital' => 10, // 10% of capital
    'winrate' => 60, // win 60% of day trades
    'winrate_type' => Lobdownium::WINRATE_INTRADAY, // days: calculate from all days -- intraday: calculate in each day
    'average_position_day' => 10, // open 10 position every day
    'average_position_profit' => 2, // +2% profit on success position
    'average_position_loss' => 2, // -2% profit on failed position
    'position_size' => 0,
];

$lobdownium = new Lobdownium(30, $config);
$lobdownium->start();
