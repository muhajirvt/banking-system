<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('home');
    }

    public function accounts(Request $request){
        $authUser = Auth::user();
        $role = $authUser->role;
        $accounts = Account::query();
        $searchVal = $request->search??"";
        if($role == 0){
            $accounts->where('user_id', $authUser->id);
        }
        if($searchVal){
            $accounts->where('name', 'LIKE', "%$searchVal%")
            ->orwhere('account_number', 'LIKE', "%$searchVal%")
            ->orwhere('balance', 'LIKE', "%$searchVal%");
        }
        $accounts = $accounts->get(['id', 'name', 'dob', 'address', 'account_number', 'currency', 'balance', 'status']);
        return view('accounts', compact('accounts'));
    }

    public function addUpdatePopupAccount(Request $request){
        $type = $request->type;
        $account = [];
        if($type == 2){
            $account = Account::find($request->id);
        }
        $authUser = Auth::user();
        $role = $authUser->role;
        $users = User::where('role', 0);
        if($role == 0){
            $users->where('id', $authUser->id);
        }
        $users = $users->get(['id', 'name']);
        return view('add-update-popup-account', compact('users','type','account'));
    }

    public function addUpdateAccount(Request $request){
        $status = "";
        $message = "";
        $trigger = "";
        $callFn = "";
        $type = $request->type;
        $accountData = [];
        foreach($request->user_id as $key => $userId) {
            if($type == 1){
                $message = "Account created";
                if(empty($request->name[$key]) || empty($request->dob[$key]) || empty($request->address[$key]) || empty($request->currency[$key])){
                    continue;
                }
                $account['account_number'] = random_int(100000000000000, 999999999999999);
                $account['balance']        = 10000;
                $account['user_id']        = $userId;
                $account['name']           = $request->name[$key];
                $account['dob']            = $request->dob[$key];
                $account['address']        = $request->address[$key];
                $account['currency']       = $request->currency[$key];
                $account['created_at']     = now();
                $account['updated_at']     = now();
                $accountData[]             = $account;
            } else {
                $message = "Account updated";
                $account = Account::find($request->id);
                $account->user_id  = $request->user_id[0];
                $account->name     = $request->name[0];
                $account->dob      = $request->dob[0];
                $account->address  = $request->address[0];
                $account->currency = $request->currency[0];
                $account->save();
            }   
        }

        if(!empty($accountData)) {
            Account::insert($accountData);
        }
        
        $status  = 1;
        $trigger = "accountBtn";
        $callFn  = "closePopup('commonModal');";

        return response()->json([
            'status'  => $status,
            'message' => $message,
            'trigger' => $trigger,
            'callFn'  => $callFn
        ]);
    }

    public function transactions(){
        $userRole = Auth::user()->role;
        $accountIds = [];
        if($userRole == 0){
            $accountIds = Account::where('user_id', Auth::user()->id)->pluck('id')->toArray();
        }
        $transactions = Transaction::join('accounts as sender', 'transactions.sender_id', 'sender.id')
        ->join('accounts as receiver', 'transactions.receiver_id', 'receiver.id');
        if(!empty($accountIds)){
            $transactions->whereIn('transactions.sender_id', $accountIds)
            ->orWhereIn('transactions.receiver_id', $accountIds);
        }
        $transactions = $transactions->get(['transactions.*','sender.name as sender_name', 'receiver.name as receiver_name']);
        return view('transactions', compact('transactions'));
    }

    public function fundTransferForm(){
        $accounts = Account::get(['id','user_id','name']);
        $fromAccounts = (auth()->user()->role == 0) ? $accounts->where('user_id', auth()->user()->id) : $accounts;
        return view('fund-transfer',compact('accounts','fromAccounts'));
    }

    public function fundTransfer(Request $request){
        try {
            $validator = Validator::make($request->input(),[
                'sender_id'      => 'required|integer',
                'account_number' => 'required|integer',
                'currency'       => 'required|string',
                'amount'         => 'required|integer'
            ]);
            $status = "";
            $message = "";
            $callFn = "";
            $trigger = "";
            if($validator->fails()){
                $status = 0;
                $message =  $validator->errors()->first();
                return response()->json([
                    'status'  => $status,
                    'message' => $message,
                ]);
            } else {
                $sender = Account::where('id', $request->sender_id)->first();
                $receiver = Account::where('account_number', $request->account_number)->first();
                if(!$receiver) {
                    $status  = 0;
                    $message = "No account found";
                    return response()->json([
                        'status'  => $status,
                        'message' => $message,
                    ]);
                } else {
                    $senderBalance = $sender->balance;
                    $amount = $request->amount;
                    if($amount > $senderBalance) {
                        $status  = 0;
                        $message = "Insufficiend fund";
                        return response()->json([
                            'status'  => $status,
                            'message' => $message,
                        ]);
                    } else {
                        $currency = $request->currency;
                        $exchangeRate = 1.0;
                        $convertedAmount = $amount;
                        if ($sender->currency !== $currency) {
                            $apiKey = env('EXCHANGE_RATE_API_KEY');
                            $response = Http::withoutVerifying()->get("https://api.exchangeratesapi.io/latest?base={$sender->currency}&symbols={$currency}&access_key={$apiKey}");       
                            if ($response->failed()) {
                                $status  = 0;
                                $message = "Exchange rate service unavailable";
                                return response()->json([
                                    'status'  => $status,
                                    'message' => $message,
                                ]);
                            } else {
                                $rates = $response->json()['rates'] ?? [];
                                if (!isset($rates[$currency])) {
                                    $status  = 0;
                                    $message = "Currency conversion not supported";
                                    return response()->json([
                                        'status'  => $status,
                                        'message' => $message,
                                    ]);
                                } else {
                                    $exchangeRate = $rates[$currency] * 1.01;
                                    $convertedAmount = round($amount * $exchangeRate, 2);
                                }
                            }
                        }

                        DB::beginTransaction();

                        $sender->balance -= $amount;
                        $sender->save();

                        $receiver->balance += $convertedAmount;
                        $receiver->save();

                        Transaction::create([
                            'sender_id'        => $sender->id,
                            'receiver_id'      => $receiver->id,
                            'amount'           => $amount,
                            'currency'         => $currency,
                            'exchange_rate'    => $exchangeRate,
                            'convert_amount'   => $convertedAmount,
                        ]);
            
                        DB::commit();
    
                        $status  = 1;
                        $message = "success";
                        $trigger = "transactionBtn";
                        $callFn  = "closePopup('commonModal');";
                    }   
                }
            }
    
            return response()->json([
                'status'  => $status,
                'message' => $message,
                'callFn'  => $callFn,
                'trigger' => $trigger
            ]);
        } catch (\Exception $e){
            report($e);
            return response()->json([
                'status'  => 0,
                'message' => "Something went wrong, Please contact admin!",
            ]);
        }
    }
}
