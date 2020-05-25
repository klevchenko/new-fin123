<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PartnersController extends Controller
{

    public function getTravelpayouts(){

        $travelpayouts_total = Cache::get('travelpayouts');

        if(!empty(floatval($travelpayouts_total)) and floatval($travelpayouts_total) > 0){
            return $travelpayouts_total;
        }

        $url = 'http://api.travelpayouts.com/v2/statistics/balance?token=53de571fdd707b23aa45baa2bdc925c8';
        $travelpayouts_total = json_decode(file_get_contents($url))->data->balance;
        $travelpayouts_total = (!empty($travelpayouts_total) and isset($travelpayouts_total)) ? $travelpayouts_total : ' Жду оновлення ';

        if(!empty(floatval($travelpayouts_total)) and floatval($travelpayouts_total) > 0){
            Cache::put('travelpayouts', $travelpayouts_total, 10800);
        }

        return $travelpayouts_total;

    }

    // RSY
    public function getYandexPartner(){

        $token='AQAAAAAY1x_JAAVD6TL2s9nWo0UNpQRKYbpJmPI'; //ваш токен

        $today_total = $this->get_from_yandex_partner_api($token, 'today');
        $today_total = ( (!empty($today_total->data->data[0]->partner_wo_nds)) ? $today_total->data->data[0]->partner_wo_nds : 'Оновлення' );

        $yesterday_total = $this->get_from_yandex_partner_api($token, 'yesterday');
        $yesterday_total = ( isset($yesterday_total->data->data[0]->partner_wo_nds) and !empty($yesterday_total->data->data[0]->partner_wo_nds)) ? $yesterday_total->data->data[0]->partner_wo_nds : 'Оновлення';

        $thismonth_total = $this->get_from_yandex_partner_api($token, 'thismonth');
        $thismonth_total = ( isset($thismonth_total->data->data[0]->partner_wo_nds) and !empty($thismonth_total->data->data[0]->partner_wo_nds)) ? $thismonth_total->data->data[0]->partner_wo_nds : 'Оновлення';

        $total = $this->direct__GetReports($token, 'levchenko.kostiantin@yandex.ru');

        $total = ( isset($total) and !empty($total)) ? $total : 'Оновлення';

        return response()->json(
            [
                'today_total' => $today_total,
                'yesterday_total' => $yesterday_total,
                'thismonth_total' => $thismonth_total,
                'total' => $total,
            ]
        );
    }

    public function get_from_yandex_partner_api($token, $date = 'thisyear'){

        $partner_url = "https://partner2.yandex.ru/api/statistics/get.json?oauth_token=$token&lang=ru&pretty=1&level=advnet_context_on_site&field=direct_context_clicks&field=direct_context_shows&field=rtb_block_all_hits&field=partner_wo_nds&period=$date";

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL,$partner_url);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $partner = curl_exec ($ch);
        curl_close($ch);
        $partner_o = json_decode($partner);

        return $partner_o;
    }

    public function direct__GetReports($token, $login) {
        $params = array(
            'method' => "AccountManagement",
            'param'  => array(
                'Action'            => "Get",
                'SelectionCriteria' => array(
                    'Logins' => array($login),
                ),
            ),
            'locale' => "ru",
            'token'  => $token,
        );
        $headers = array(
            'POST /json/v5/ads/ HTTP/1.1',
            'Host: api.direct.yandex.com',
            'Authorization: Bearer ' . $token,
            'Accept-Language: ru',
            'Client-Login:  ' . $login,
            'Content-Type: application/json; charset=utf-8',
        );
        $url = 'https://api.direct.yandex.ru/live/v4/json/';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output, true);
    }

}
