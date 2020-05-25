<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{

    public function all()
    {
        return [
            [
                "id"     => 1,
                "sumbol" => 'USD',
                "code"   => 'USD',
                "name"   => 'дол'
            ],
            [
                "id"     => 2,
                "sumbol" => 'UAH',
                "code"   => 'UAH',
                "name"   => 'грн'
            ],
            [
                "id"     => 3,
                "sumbol" => 'EUR',
                "code"   => 'EUR',
                "name"   => 'EUR'
            ],
            [
                "id"     => 4,
                "sumbol" => 'RUB',
                "code"   => 'RUR',
                "name"   => 'руб'
            ],
        ];
    }
}
