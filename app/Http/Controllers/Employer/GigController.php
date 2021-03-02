<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Task;
use App\PendingTask;
use App\GigCategory;
use App\Gig;
use App\PendingGig;
use App\CompletedGig as CJ;
use Illuminate\Support\Facades\Session;
use App\User;
use App\GigApp as GA;
use App\Employer;
use App\Mail\GlobalMail;
use Illuminate\Support\Facades\Mail;
use App\Transition;
use App\Exports\GigProofs;
use App\Exports\EmGigsApps;
use Excel;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GigController extends Controller
{
    public function manage()
    {
        $campaigns = Gig::where('user_id',Auth::guard('employer')->id())->orderBy('created_at','desc')->paginate(15);
        return view('employer.gigs.manage',compact('campaigns'));
    }
    public function creater(){
        $emp = Employer::find(Auth::guard('employer')->id());
        $campaignCategory = GigCategory::get();
        return view('employer.gigs.create')->with([
            'employer' => $emp,
            'campaignCategory' => $campaignCategory,
        ]);
    }
    public function create(Request $request){
        $this->validate($request, [
            'cat' => 'required',
            'per_cost' => 'required|numeric',
            'description' => 'required',
            'campaign_title' => 'required',
            'filess'=>'required',
            'tasks' => 'required',
        ]);
        $emp = Employer::find(Auth::guard('employer')->id());
        $campaign = new PendingGig;
        $campaign->per_cost = $request->per_cost;
        $campaign->campaign_title = $request->campaign_title;
        $campaign->description = $request->description;
        $cat = "";
        foreach($request->cat as $cate){
            $cat = $cate.", ".$cat;
        }
        $campaign->cats = $cat;
        $campaign->brand = $emp->cname;
        $campaign->logo = $emp->profile_photo;

        $campaign->user_id = Auth::guard('employer')->id();
        $campaign->save();
        $i=0;

        foreach($request->tasks as $taske){
            $files[$i]= "<a href=\"".$request->filess[$i]."\" class=\"btn btn-link\">Click here to download the file(s)</a>";
            $taske = $taske."<br/>".$files[$i];
            $task = new PendingTask;
            $task->cid = $campaign->id;
            $task->task = $taske;
            $task->save();
            $i++;
        }

        // Mail
        $sub = "Your Gig is Created";
        $message="<p>Dear {$emp->name},</p><p>Your Gig, {$request->campaign_title}, has been created successfully, and is waiting for the approval from the admin.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($emp->email)->send(new GlobalMail($data));

        //redirect
        Session()->flash('success', 'Your gig is successfully created. Wait for the admin to approve it.');
        return redirect()->back();
    }

    public function applications($id){
        $apps = GA::where('cid',$id)->orderBy('created_at','desc')->paginate(15);
        return view('employer.gigs.applications')->with([
            'campaigns' => $apps,
        ]);
    }
    public function delete(Request $request){
        $this->validate($request,[
            'id' => 'required'
        ]);
        $emp = Employer::find(Auth::guard('employer')->id());
        $gig = Gig::find($request->id);
        Gig::find($request->id)->delete();

        // Mail
        $sub = "Your Gig is deleted";
        $message="<p>Dear {$emp->name},</p><p>Your Gig, {$gig->campaign_title}, has been deleted successfully. If you did not delete it, kindly change your password as soon as possible.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($emp->email)->send(new GlobalMail($data));
        $request->session()->flash('success', "Gig Deleted");
        return redirect()->back();
    }

    public function approveApp($jid,$uid){
        $app = GA::where(['cid'=>$jid,'uid'=>$uid])->first();
        $app->status=1;
        $app->save();
        $gig = Gig::find($jid);
        $user = User::find($uid);

        // Mail
        $sub = "Your application is approved";
        $message="<p>Dear {$user->name},</p><p>Your application for the gig,{$gig->campaign_title}, has been approved.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($user->email)->send(new GlobalMail($data));

        Session()->flash('success','Application Approved');
        return redirect()->back();
    }
    public function rejectApp($jid,$uid){
        $app = GA::where(['cid'=>$jid,'uid'=>$uid])->first();
        $app->status=2;
        $app->save();
        $gig = Gig::find($jid);
        $user = User::find($uid);

        // Mail
        $sub = "Your application is rejected";
        $message="<p>Dear {$user->name},</p><p>Your application for the gig,{$gig->campaign_title}, has been rejected.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($user->email)->send(new GlobalMail($data));
        Session()->flash('success','Application Rejected');
        return redirect()->back();
    }
    public function viewproof($jid,$uid){
        $ga = GA::where(['cid'=>$jid,'uid'=>$uid])->first();
        $apps = CJ::where(['job_id'=>$jid,'user_id'=>$uid])->get();
        return view('employer.gigs.proofs')->with([
            'campaigns' => $apps,
            'ga' => $ga,
        ]);
    }
    public function acceptproof($jid,$uid){
        $ga = GA::where(['cid'=>$jid,'uid'=>$uid])->first();
        $job = Gig::find($jid);
        $ga->status = 4;
        $ga->save();
        $user = User::find($uid);
        $user->balance = $user->balance + $job->per_cost;
        $user->save();
        $tr = new Transition;
        $tr->uid = $user->id;
        $tr->reason = "For completing Gig ".$job->campaign_title;
        $tr->transition = "+".$job->per_cost;
        $tr->addm = $job->per_cost;
        $tr->save();
        if($user->ref_by!=NULL){
            $userr = User::find($user->ref_by);
            $c = 0.05*$job->per_cost;
            $userr->balance = $userr->balance + $c;
            $userr->save();
            $tr = new Transition;
            $tr->uid = $userr->id;
            $tr->reason = "Referral Bonus";
            $tr->transition = "+".$c;
            $tr->addm = $c;
            $tr->save();
        }

        // Mail
        $sub = "Your proof is accepted";
        $message="<p>Dear {$user->name},</p><p>Your proof for the gig,{$job->campaign_title}, has been accepted and the amount has been credited to your wallet.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($user->email)->send(new GlobalMail($data));
        Session()->flash('success', "Accepted");
        return redirect()->back();
    }
    public function rejectproof($jid,$uid){
        $ga = GA::where(['cid'=>$jid,'uid'=>$uid])->first();
        $job = Gig::find($jid);
        $ga->status = 5;
        $ga->save();
        $jobs = CJ::where(['job_id'=>$jid,'user_id'=>$uid])->get();
        $path = "assets/user/images/proof_file/";
        foreach($jobs as $job){
            if($job->proof_file!=NULL){
                unlink($path.$job->proof_file);
            }
            $job->delete();
        }
        $user = User::find($uid);

        // Mail
        $sub = "Your proof is rejected";
        $message="<p>Dear {$user->name},</p><p>Your proof for the gig,{$job->campaign_title}, has been rejected.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($user->email)->send(new GlobalMail($data));
        Session()->flash('success', "Rejected");
        return redirect()->back();
    }
    public function export_excel($id){
        $em = Employer::find(Auth::guard('employer')->id());
        $job = Gig::find($id);
        
        if($job==NULL){
            Session()->flash('warning','You cannot perform this action');
            return redirect()->back();
        }
        if($job->user_id == $em->id){
            $proofs = CJ::where(['job_id' => $id])->get();
            if($proofs->count()==0){
                Session()->flash('warning','No proof found');
                return redirect()->back();
            }
            else{
                return Excel::download(new GigProofs($id), 'proofs.xlsx');
            }
        }
        else{
            Session()->flash('warning','You cannot perform this action');
            return redirect()->back();
        }
    }
    function edit($id){
        $emp = Employer::find(Auth::guard('employer')->id());
        $campaignCategory = GigCategory::get();
        $gig = Gig::find($id);
        if($gig->user_id == Auth::guard('employer')->id()){
            return view('employer.gigs.edit')->with([
                'employer' => $emp,
                'campaignCategory' => $campaignCategory,
                'gig' => $gig
            ]);
        }
        else{
            Session::flash('error',"You are not allowed to edit this gig");
            return redirect()->back();
        }
    }
    function editp(Request $request,$id){
        $this->validate($request,[
            'per_cost' => 'required|numeric',
            'description' => 'required',
            'campaign_title' => 'required',
        ]);
        $emp = Employer::find(Auth::guard('employer')->id());
        $gig = Gig::find($id);
        if($gig->user_id == Auth::guard('employer')->id()){
            $gig->campaign_title = $request->campaign_title;
            $gig->description = $request->description;
            $gig->per_cost = $request->per_cost;
            $gig->save();
            $request->session()->flash('success', "Gig edited successfully");
            return redirect()->back();
        }
        else{
            Session::flash('error',"You are not allowed to edit this gig");
            return redirect()->back();
        }
    }
    public function exportapps($id){
        $em = Employer::find(Auth::guard('employer')->id());
        $job = Gig::find($id);
        
        if($job==NULL){
            Session()->flash('warning','You cannot perform this action');
            return redirect()->back();
        }
        if($job->user_id == $em->id){
            $proofs = GA::where(['cid' => $id])->get();
            if($proofs->count()==0){
                Session()->flash('warning','No applications found');
                return redirect()->back();
            }
            else{
                return Excel::download(new EmGigsApps($id), 'gigs_apps.xlsx');
            }
        }
        else{
            Session()->flash('warning','You cannot perform this action');
            return redirect()->back();
        }
    }
}
