<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CurrencyConverterController extends Controller
{
    /*
        Наличный курс ПриватБанка (в отделениях):
        GET JSON: https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5

        Безналичный курс ПриватБанка (конвертация по картам, Приват24, пополнение вкладов):
        GET JSON: https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11
    */

    public $all_json_data;

    // TODO Cache this
    public $api_url = 'https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=5';

    public $buy;
    public $sale;

    function __construct()
    {
        $this->formar_all();
    }

    public function get_all()
    {
        $cached_data = Cache::get('exchange_pairs');

        if(is_array($cached_data) and count($cached_data) > 0){
            return $cached_data;
        }

        $tmp = file_get_contents($this->api_url);
        $this->all_json_data = ( !empty($tmp) and json_decode($tmp) ) ? json_decode($tmp, true) : [];

        if(is_array($this->all_json_data) and count($this->all_json_data) > 0){
            Cache::put('exchange_pairs', $this->all_json_data, 10800);
        }

        return $this->all_json_data;
    }

    public function formar_all()
    {
        foreach($this->get_all() as $row)
        {
            if($row['ccy'] == 'RUR'){
                $this->buy['RUB'] =  $row['buy'];
                $this->sale['RUB'] =  $row['sale'];
            } else {
                $this->buy[$row['ccy']] =  $row['buy'];
                $this->sale[$row['ccy']] =  $row['sale'];
            }
        }

        $this->buy['UAH'] = 1;
        $this->sale['UAH'] = 1;

    }

    public function convert($amount, $from = 'USD', $to = 'UAH', $action = 'sale')
    {
        $convert_data = $action == 'sale' ? $this->sale : $this->buy;
        $res = NULL;

        if(
            isset($amount)              and !empty($amount) and
            isset($from)                and !empty($from) and
            isset($convert_data)        and !empty($convert_data) and
            isset($convert_data[$from]) and !empty($convert_data[$from])
        ){
            // TODO convert any to any
            // convert any currency from $from to UAH
            $res = $amount * $convert_data[$from];

        }

        return $res;

    }

    public function getCourses(){
        return response()->json(
            [
                'buy' => $this->buy,
                'sell' => $this->sale,
            ]
        );
    }


}
