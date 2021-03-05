<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Application;
use Auth;

class WorkController extends Controller
{
    public function works(){
        $works = Application::where(["user_id"=>Auth::user()->id])->latest()->paginate(10);
        return view("user.pages.works")->with([
            "works"=>$works,
        ]);
    }
}
