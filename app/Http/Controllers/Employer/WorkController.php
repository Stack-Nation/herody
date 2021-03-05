<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employer;
use App\Application;
use App\Work;
use App\Certificate;
use App\Mail\certificate_mail;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Mail\GlobalMail;
use App\Transition;
use App\Exports\EmPrApps;
use App\Exports\EmPrSls;
use App\Exports\EmPrSl;
use Excel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkController extends Controller
{
    public function index(){
        $em = Employer::find(Auth::guard('employer')->id());
        $works = Work::where('user_id',$em->id)->latest()->paginate(10);
        return view('employer.works.manage')->with([
            'employer' => $em,
            'works' => $works,
        ]);
    }
    public function create(){
        $employer = Employer::find(Auth::guard('employer')->id());
        $cats = [
            "SALES & BUSINESS DEVELOPMENT",
            "PRODUCTION",
            "MAINTENANCE",
            "ACCOUNTING AND FINANCE",
            "ADMIN & HUMAN RESAURCES (HR) MANAGEMENT",
            "PROCUREMENT & PLANNING",
            "TESTING & QUALITY",
            "RESEARCH & DEVELOPMENT (R & D)",
            "DESIGN",
            "MARKETING",
            "TRAINING & DEVELOPMENT",
            "PURCHASING",
            "SUPPLY CHAIN MANAGEMENT",
            "INVENTORY & STORE",
            "IT & ITES",
            "ENVIRONMENTAL HEALTH AND SAFETY",
            "CORPORATE SUPPORT",
            "ENGINEERING",
            "ELECTRICAL",
            "MECHANICAL",
            "FACILITY MANAGEMENT",
            "CUSTOMER SERVICE SUPPORT",
            "CONSULTANT",
            "EXPERT",
            "CONTRACTOR",
            "OTHER",
        ];
        return view('employer.works.post')->with([
            'employer' => $employer,
            'cats' => $cats,
        ]);
    }
    public function store(Request $request){
        $this->validate($request,[
            "name"=>"required",
            "type"=>"required",
            "category"=>"required",
            "duration"=>"required",
            "comment"=>"required",
            "description"=>"required",
            "about"=>"required",
            "pricing"=>"required",
            "price"=>"required",
            "last_apply"=>"required",
            "last_complete"=>"required",
            "candidates"=>"required",
            "responsibilities"=>"required",
            "obj_description"=>"required",
            "obj_file"=>"required",
            "obj_price"=>"nullable",
            "obj_duration"=>"required",
        ]);
        $work = new Work;
        $work->name = $request->name;
        $work->type = $request->type;
        $work->category = $request->category;
        $work->duration = $request->duration;
        $work->comment = $request->comment;
        $work->description = $request->description;
        $work->about = $request->about;
        $work->pricing = $request->pricing;
        $work->price = $request->price;
        $work->last_apply = $request->last_apply;
        $work->last_complete = $request->last_complete;
        $work->candidates = $request->candidates;
        $work->responsibilities = json_encode($request->responsibilities,true);
        $objectives = [];
        foreach($request->obj_description as $key=>$description){
            $path = "assets/work/files/";
            $file = $_FILES["obj_file"]["name"][$key];
            $filetmp = $_FILES["obj_file"]["tmp_name"][$key];
            $file = idate("U").$file;
            if(!isset($request->obj_price[$key])){
                $price = NULL;
            }
            else{
                $price = $request->obj_price[$key];
            }
            if(\move_uploaded_file($filetmp,$path.$file)){
                $objectives[] = [
                    "description"=>$description,
                    "file"=>$file,
                    "price"=>$price,
                    "duration"=>$request->obj_duration[$key],
                ];
            }
            else{
                $request->session()->flash('error', "There is some problem in uploading objective file");
            }
        }
        $work->objectives = \json_encode($objectives);
        $work->user_id = Auth::guard('employer')->id();
        $work->save();
        $request->session()->flash('success', "Work submitted successfully");
        return redirect()->back();
    }
    public function delete(Request $request){
        $this->validate($request,[
            "id"=>"required",
        ]);
        $work = Work::find($request->id);
        if($work===NULL){
            abort(404);
        }
        else{
            if($work->user_id===Auth::guard('employer')->id()){
                $work->objectives = json_decode($work->objectives);
                foreach($work->objectives as $obj){
                    $path = "assets/work/files/";
                    unlink($path.$obj->file);
                }
                $work->delete();
                $request->session()->flash('success', "Work deleted");
                return redirect()->back();
            }
            else{
                abort(404);
            }
        }
    }
    public function edit($id){
        $work = Work::find($id);
        if($work===NULL){
            abort(404);
        }
        else{
            if($work->user_id===Auth::guard('employer')->id()){
                $cats = [
                    "SALES & BUSINESS DEVELOPMENT",
                    "PRODUCTION",
                    "MAINTENANCE",
                    "ACCOUNTING AND FINANCE",
                    "ADMIN & HUMAN RESAURCES (HR) MANAGEMENT",
                    "PROCUREMENT & PLANNING",
                    "TESTING & QUALITY",
                    "RESEARCH & DEVELOPMENT (R & D)",
                    "DESIGN",
                    "MARKETING",
                    "TRAINING & DEVELOPMENT",
                    "PURCHASING",
                    "SUPPLY CHAIN MANAGEMENT",
                    "INVENTORY & STORE",
                    "IT & ITES",
                    "ENVIRONMENTAL HEALTH AND SAFETY",
                    "CORPORATE SUPPORT",
                    "ENGINEERING",
                    "ELECTRICAL",
                    "MECHANICAL",
                    "FACILITY MANAGEMENT",
                    "CUSTOMER SERVICE SUPPORT",
                    "CONSULTANT",
                    "EXPERT",
                    "CONTRACTOR",
                    "OTHER",
                ];
                return view('employer.works.edit')->with([
                    'work' => $work,
                    'cats' => $cats,
                ]);
            }
            else{
                abort(404);
            }
        }
    }
    public function update($id,Request $request){
        $work = Work::find($id);
        if($work===NULL){
            abort(404);
        }
        else{
            if($work->user_id===Auth::guard('employer')->id()){
                $this->validate($request,[
                    "name"=>"required",
                    "type"=>"required",
                    "category"=>"required",
                    "duration"=>"required",
                    "comment"=>"required",
                    "description"=>"required",
                    "about"=>"required",
                    "pricing"=>"required",
                    "price"=>"required",
                    "last_apply"=>"required",
                    "last_complete"=>"required",
                    "candidates"=>"required",
                ]);
                $work->name = $request->name;
                $work->type = $request->type;
                $work->category = $request->category;
                $work->duration = $request->duration;
                $work->comment = $request->comment;
                $work->description = $request->description;
                $work->about = $request->about;
                $work->pricing = $request->pricing;
                $work->price = $request->price;
                $work->last_apply = $request->last_apply;
                $work->last_complete = $request->last_complete;
                $work->candidates = $request->candidates;
                $work->save();
                $request->session()->flash('success', "Work edited successfully");
                return redirect()->back();
            }
            else{
                abort(404);
            }
        }
    }
    public function applications($id){
        $work = Work::find($id);
        if($work->user_id === Auth::guard('employer')->id()){
            $applications = Application::where('work_id',$id)->latest()->paginate(15);
            return view('employer.works.applications')->with([
                'applications' => $applications,
                'id' => $id,
            ]);
        }
        else{
            abort(404);
        }
    }
    public function shortlist($id,Request $request){
        $work = Work::find($id);
        if($work->user_id === Auth::guard('employer')->id()){
            $this->validate($request,[
                "id"=>"required"
            ]);
            $application = Application::where(["work_id"=>$id,"user_id"=>$request->id])->first();
            $application->status = 1;
            $application->save();
            $request->session()->flash('success', "User has been shortlisted");
            return redirect()->back();
        }
        else{
            abort(404);
        }
    }
    public function select($id,Request $request){
        $work = Work::find($id);
        if($work->user_id === Auth::guard('employer')->id()){
            $this->validate($request,[
                "id"=>"required"
            ]);
            $application = Application::where(["work_id"=>$id,"user_id"=>$request->id])->first();
            $application->status = 2;
            $application->save();
            $request->session()->flash('success', "User has been selected");
            return redirect()->back();
        }
        else{
            abort(404);
        }
    }
    public function reject($id,Request $request){
        $work = Work::find($id);
        if($work->user_id === Auth::guard('employer')->id()){
            $this->validate($request,[
                "id"=>"required"
            ]);
            $application = Application::where(["work_id"=>$id,"user_id"=>$request->id])->first();
            $application->status = 3;
            $application->save();
            $request->session()->flash('success', "User has been rejected");
            return redirect()->back();
        }
        else{
            abort(404);
        }
    }
}
