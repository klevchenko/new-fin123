<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function index()
    {
        $conv = new CurrencyConverterController();
        $transactions = DB::table('transactions')
            ->latest()
            ->get();

        $usd = 0;
        $uah = 0;
        $total_sum_uah = 0;


        foreach ($transactions as $tr){
            if($tr->currency == 'usd'){
                $usd = floatval($usd) + floatval($tr->amount);
                $tr->uah_amount = round($conv->convert($tr->amount, 'USD'), 1);
            }

            if($tr->currency == 'uah'){
                $total_sum_uah = floatval($total_sum_uah) + floatval($tr->amount);
            }
        }

        $uah = $total_sum_uah;
        $total_sum_uah = $total_sum_uah + $conv->convert($usd, 'USD');

        return response()->json([
            'total_sum_uah' => round($total_sum_uah, 1),
            'uah_total' => round($uah, 1),
            'usd_total' => round($usd, 1),
            'transactions' => $transactions
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required',
        ]);

        $curs = ['usd', 'uah'];

        $transaction = new Transaction();

        $amount = request('amount');
        $amount = round($amount, 2);

        $currency = request('currency');

        if( empty($currency) ){
            $currency = 'uah';
        }

        if( !in_array($currency, $curs) ){
            $currency = 'uah';
        }

        $transaction->amount = $amount;
        $transaction->currency = $currency;

        $transaction->save();

        die;
    }

    public function deleteAll(Request $request){

        $id = intval($request->id);

        if($id > 0){
            $transactions = DB::table('transactions')->delete($id);
            return response()->json(['status' => $transactions]);
        } else {
            DB::table('transactions')->truncate();
            return response()->json(['status' => 'del_all']);
        }
    }

    public function test(){

        $tt = new CurrencyConverterController();

        var_dump($tt->get_all());

        var_dump(
            $tt->convert(100, 'USD')
        );


    }

}
