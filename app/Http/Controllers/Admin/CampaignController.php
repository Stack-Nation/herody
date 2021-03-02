<?php

namespace App\Http\Controllers\Admin;

use App\Gig;
use App\GigCategory;
use App\CompletedGig as CJ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Employer;
use App\PendingGig;
use App\GigApp as GA;
use App\Task;
use App\PendingTask;
use App\Mail\GlobalMail;
use Illuminate\Support\Facades\Mail;
use App\Transition;
use Excel;
use App\Exports\AllGigs;
use App\Exports\GigApps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignController extends Controller
{
    public function ShowAllCampaign()
    {
        $campaigns = Gig::orderBy('created_at','desc')->paginate(15);
        return view('admin.campaign.all_campaign',compact('campaigns'));
    }

    public function ShowCampaignLog()
    {
        $campaigns = Gig::paginate(15);
        return view('admin.campaign.campaign_log',compact('campaigns'));
    }

    public function CreateCampaign(){
        $campaignCategory = GigCategory::get();
        return view('admin.campaign.create_campaign',compact('campaignCategory'));
    }

    public function StoreCampaign(Request $request)
    {

        //validation
        $this->validate($request, [
            'cat' => 'required',
            'per_cost' => 'required|numeric',
            'description' => 'required',
            'campaign_title' => 'required',
            'brand' => 'required',
            'tasks' => 'required',
            'filess' => 'required',
            'logo' => 'required|image',
        ]);
        $campaign = new Gig();
        $campaign->per_cost = $request->per_cost;
        $campaign->campaign_title = $request->campaign_title;
        $campaign->description = $request->description;
        $cat = "";
        foreach($request->cat as $cate){
            $cat = $cate.", ".$cat;
        }
        $campaign->cats = $cat;
        $campaign->brand = $request->brand;
        if($request->hasFile('logo')){
            $name = $_FILES['logo']['name'];
            $tmp = $_FILES['logo']['tmp_name'];
            $path = "assets/admin/img/gig-brand-logo/";
            $name = "Gig_Brand_".$name;
            if(move_uploaded_file($tmp,$path.$name)){
                $campaign->logo = $name;
            }
            else{
                $request->session()->flash('error', 'There is some problem in uploading the image');
                return redirect()->back();
            }
        }
        $campaign->user_id = "Admin";
        $campaign->save();
        $i=0;

        foreach($request->tasks as $taske){
            $files[$i]= "<a href=\"".$request->filess[$i]."\" class=\"btn btn-link\">Click here to download the file(s)</a>";
            $taske = $taske."<br/>".$files[$i];
            $task = new Task;
            $task->cid = $campaign->id;
            $task->task = $taske;
            $task->save();
            $i++;
        }

        //redirect
        Session()->flash('success', 'Your gig successfully created');
        return redirect()->back();

    }
    public function Campaignapp($id){
        $apps = GA::where('cid',$id)->orderBy('created_at','desc')->paginate(15);
        return view('admin.campaign.campaign_app')->with([
            'campaigns' => $apps,
        ]);
    }
    public function pendings(){
        $campaigns = PendingGig::orderBy('created_at','asc')->paginate(15);
        return view('admin.campaign.pendings',compact('campaigns'));
    }
    public function approveCampaign($id){
        $pending = PendingGig::find($id);
        $campaign = new Gig;
        $cat = GigCategory::find($pending->campaign_category);
        $campaign->cats = $pending->cats;
        $campaign->per_cost = $pending->per_cost;
        $campaign->campaign_title = $pending->campaign_title;
        $campaign->description = $pending->description;
        $campaign->brand = $pending->brand;
        $campaign->logo = $pending->logo;

        $campaign->user_id = $pending->user_id;
        $campaign->save();

        $tasks = PendingTask::where('cid',$pending->id)->get();
        foreach($tasks as $taske){
            $task = new Task;
            $task->cid = $campaign->id;
            $task->task = $taske->task;
            $task->save();
            $taske->delete();
        }

        $pending->delete();
        $emp = Employer::find($campaign->user_id);

        // Mail
        $sub = "Your gig has been approved";
        $message="<p>Dear {$emp->name},</p><p>Your gig, {$campaign->campaign_title}, has been approved by admin.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($emp->email)->send(new GlobalMail($data));
        Session()->flash('success','Gig Approved');
        return redirect()->back();
    }
    public function rejectCampaign($id){
        PendingTask::where('cid',$id)->delete();
        $campaign = PendingGig::find($id);
        PendingGig::find($id)->delete();
        $emp = Employer::find($campaign->user_id);

        // Mail
        $sub = "Your gig has been rejected";
        $message="<p>Dear {$emp->name},</p><p>Your gig, {$campaign->campaign_title}, has been rejected by admin.</p>";
        $data = array('sub'=>$sub,'message'=>$message);
        Mail::to($emp->email)->send(new GlobalMail($data));
        Session()->flash('success','Gig Deleted');
        return redirect()->back();
    }
    public function Campaignapprove($jid,$uid){
        $app = GA::where(['cid'=>$jid,'uid'=>$uid])->first();
        $app->status=1;
        $app->save();
        Session()->flash('success','Application Approved');
        return redirect()->back();
    }
    public function Campaignreject($jid,$uid){
        $app = GA::where(['cid'=>$jid,'uid'=>$uid])->first();
        $app->status=2;
        $app->save();
        Session()->flash('success','Application Rejected');
        return redirect()->back();
    }
    public function viewproof($jid,$uid){
        $apps = CJ::where(['job_id'=>$jid,'user_id'=>$uid])->get();
        return view('admin.campaign.view_proofs')->with([
            'campaigns' => $apps,
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
            $user = User::find($user->ref_by);
            $c = 0.05*$job->per_cost;
            $user->balance = $user->balance + $c;
            $user->save();
            $tr = new Transition;
            $tr->uid = $user->id;
            $tr->reason = "Referral Bonus";
            $tr->transition = "+".$c;
            $tr->addm = $c;
            $tr->save();
        }
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
        Session()->flash('success', "Rejected");
        return redirect()->back();
    }
    public function export_excel(){
        $gigs = Gig::get();
        if($gigs->count()==0){
            Session()->flash('warning','No gig found');
            return redirect()->back();
        }
        else{
            return Excel::download(new AllGigs(), 'gigs.xlsx');
        }
    }
    public function export_apps($id){
        $gigs = Gig::find($id);
        if($gigs==NULL){
            Session()->flash('warning','No gig found');
            return redirect()->back();
        }
        else{
            return Excel::download(new GigApps($id), 'gigsapps.xlsx');
        }
    }
    public function makeMobile(Request $request){
        $this->validate($request,[
            'id' => 'required'
        ]);
        $gig = Gig::find($request->id);
        $gig->mobile = 1;
        $gig->save();
        $request->session()->flash('success', "The gig has been made mobile specific");
        return redirect()->back();
    }
    public function undoMobile(Request $request){
        $this->validate($request,[
            'id' => 'required'
        ]);
        $gig = Gig::find($request->id);
        $gig->mobile = 0;
        $gig->save();
        $request->session()->flash('success', "The gig has been removed from mobile specific");
        return redirect()->back();
    }
}
