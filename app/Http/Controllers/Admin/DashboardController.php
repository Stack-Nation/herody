<?php

namespace App\Http\Controllers\Admin;
use App\Admin;
use App\Work;
use App\User;
use App\Employer;
use App\Withdraw;
use App\WithdrawRequest;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\GlobalMail;
use Illuminate\Support\Facades\Mail;
use App\Transition;
class DashboardController extends Controller
{
    public function dashboard()
    {
        $works = Work::where("approved",1)->get();
        $pendingWorks = Work::where("approved",0)->get();
        $employers = Employer::get();
        $withdraws = Withdraw::all();
        $withdrawRequest = WithdrawRequest::where('status',0)->get();
        $withdrawLogs = WithdrawRequest::where('status','!=',0)->get();

        $allUsers = User::all();
        $acUsers = User::where('account_status',1)->get();
        $InAcUsers = User::where('account_status',0)->get();

        return view('admin.pages.dashboard', compact('InAcUsers','acUsers','allUsers','works','employers','pendingWorks', 'withdraws','withdrawLogs','withdrawRequest'));

    }


    //show withdraw request
    public function ShowWithdrawRequest()
    {
        $withdrawRequest = WithdrawRequest::where('status',0)->get();
        return view('admin.withdraw.withdraw_request',compact('withdrawRequest'));
    }

    public function ShowWithdrawLog()
    {
        $withdrawRequest = WithdrawRequest::where('status','!=',0)->get();
        return view('admin.withdraw.withdraw_log',compact('withdrawRequest'));
    }

    public function WithdrawApproved(Request $request)
    {
        WithdrawRequest::where('id',$request->id)->update(['status' => 1]);

        //send mail to user
        $withdrawRequest = WithdrawRequest::find($request->id);
        $to = $withdrawRequest->user->email;
        $subject = 'Approved withdraw request';
        $message = 'Your withdraw request is Approved, Thanks for having us';
        $data = array('sub'=>$subject,'message'=>$message);
        Mail::to($to)->send(new GlobalMail($data));
        //redirect
        Session()->flash('success', 'Approved!');
        return redirect()->back();
    }

    public function WithdrawReject(Request $request)
    {
        //return amount of user account
          $withdrawRequest = WithdrawRequest::find($request->id);

          $return_balance = $withdrawRequest->payable_amount;

          $user_balance = $withdrawRequest->user->balance;
          $user_balance += $return_balance;

          User::where('id', $withdrawRequest->user->id)->update(['balance' => $user_balance]);
          
          $tr = new Transition;
          $tr->uid = $withdrawRequest->user->id;
          $tr->reason = "Withdrawal Rejected";
          $tr->transition = "+".$withdrawRequest->payable_amount;
          $tr->save();



        //change status
        WithdrawRequest::where('id',$request->id)->update(['status' => 2]);

        //Send mail to user
        $to = $withdrawRequest->user->email;
        $subject = 'Reject withdraw request';
        $message = 'Sorry Your withdraw request is rejected, Please try again later';

        $data = array('sub'=>$subject,'message'=>$message);
        Mail::to($to)->send(new GlobalMail($data));

        //redirect
        Session()->flash('success', 'Rejected!');
        return redirect()->back();
    }
}
