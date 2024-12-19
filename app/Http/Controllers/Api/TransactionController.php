<?php

namespace App\Http\Controllers\Api;

use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataPlan;

class TransactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = auth()->id();

        $transactions = Transaction::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('send_to', $user->id);
        })
            ->where('status', 'Paid')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) use ($userId) {
                $transaction->user_login = $userId;
                return $transaction;
            });

        return response([
            'data' => $transactions
        ], 200);
    }


    public function callback(Request $request)
    {
        $orderId = $request->input('order_id');
        $grossAmount = $request->input('gross_amount');
        $transactionStatus = $request->input('transaction_status');
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $user = User::find($transaction->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if ($transactionStatus == 'settlement') {

            $transaction->update([
                'status' => 'Paid',
            ]);

            $grossAmountInt = (int) round(floatval($grossAmount));

            $user->update([
                'balance' => $user->balance + $grossAmountInt,
            ]);

            return response()->json(['message' => 'Transaction processed successfully'], 201);
        }
    }

    public function createTopUp(Request $request)
    {
        Config::$serverKey = 'SB-Mid-server-_hpLDxm6DJ5XEnhuPqnoFY2C';
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'ORDER-' . uniqid();
        $params = [
            'transaction_details' => [
                'order_id' =>  $orderId,
                'gross_amount' => $request->input('amount'),
            ],
            "enabled_payments" => [
                $request->input('payment_method_code')
            ]
        ];

        try {
            Transaction::create([
                'user_id' => auth()->id(),
                'order_id' => $orderId,
                'amount' => $request->input('amount'),
                'transaction_type' => 'Top Up',
                'status' => 'Unpaid'
            ]);

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'token' =>    $snapToken,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/' . $snapToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function createTransfer(Request $request)
    {
        $orderId = 'ORDER-' . uniqid();

        $user1 = User::find(auth()->id());
        $grossAmountInt = (int) round(floatval($request->input('amount')));
        if ($user1->balance < $grossAmountInt) {
            return response([
                'message' =>  'Your balance is not enough'
            ], 400);
        }
        try {
            Transaction::create([
                'user_id' => auth()->id(),
                'send_to' => $request->input('send_to'),
                'order_id' => $orderId,
                'amount' => $request->input('amount'),
                'transaction_type' => 'Transfer',
                'status' => 'Paid'
            ]);

            $user1 = User::find(auth()->id());
            $user2 = User::find($request->input('send_to'));



            $user1->update([
                'balance' => $user1->balance - $grossAmountInt,
            ]);

            $user2->update([
                'balance' => $user2->balance + $grossAmountInt,
            ]);

            return response([
                'message' => 'Sukses'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function createDataPlan(Request $request)
    {
        $orderId = 'ORDER-' . uniqid();
        $dataPlan = DataPlan::find($request->input('data_plan_id'));
        $user = User::find(auth()->id());

        if ($user->balance < $dataPlan->price) {
            return response([
                'message' =>  'Your balance is not enough'
            ], 400);
        }
        try {
            Transaction::create([
                'user_id' => auth()->id(),
                'order_id' => $orderId,
                'amount' =>   $dataPlan->price,
                'transaction_type' => 'Data',
                'status' => 'Paid'
            ]);

            $user->update([
                'balance' => $user->balance - $dataPlan->price,
            ]);

            return response([
                'message' => 'Sukses'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
