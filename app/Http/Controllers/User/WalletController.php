<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserTransaction as UT;
use Auth;
use App\Employer;

class WalletController extends Controller
{
    public function index(){
        $transactions = UT::where("user_id",Auth::guard("employer")->id())->latest()->paginate(15);
        $user = Auth::user();
        return view("user.wallet.index")->with([
            "transactions"=>$transactions,
            "user"=>$user
        ]);
    }
}
