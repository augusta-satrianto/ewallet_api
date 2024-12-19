<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OperatorCard;
use Illuminate\Http\Request;

class OperatorCardController extends Controller
{
    public function index()
    {
        $operatorCards = OperatorCard::with('dataPlans')->get();

        return response([
            'data' => $operatorCards
        ], 200);
    }
}
