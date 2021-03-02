<?php

namespace App\Http\Controllers\Admin;

use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use App\WithdrawRequest;
use Excel;
use App\Exports\AllWithdrawals;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdraws = Withdraw::all();
        return view('admin.withdraw.index',compact('withdraws'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation
        $this->validate($request, [
            'name' => 'required|string',
            'detail' => 'required',
            'image' => 'required|image',
        ]);

        //image operation
        if( $request->hasFile('image') ) {
            try {

                $path = 'assets/user/images/withdraw/';

                $input_image = Image::make($request->image);
                $image = $input_image->resize(160, 80);
                $image_name = $request->file('image')->getClientOriginalName();
                $image_name = Carbon::now()->format('YmdHs').'_'.$image_name;
                $image->save($path.$image_name);

            }catch(\Exception $exp) {
                Session()->flash('warning', 'image upload failed !');
                return redirect()->back();
            }
        }else{
            Session()->flash('warning', 'image not found !');
            return redirect()->back();
        }


        $withdraws = new Withdraw();
        $withdraws->name = $request->name;
        $withdraws->detail = $request->detail;
        $withdraws->image = $image_name;
        

        $withdraws->save();
        //redirect
        Session()->flash('success', 'successfully Created !');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validation
        $this->validate($request, [
            'name' => 'required|string',
            'detail' => 'required',
        ]);

        $withdraws = Withdraw::find($request->id);
        $withdraws->name = $request->name;
        
        $withdraws->detail = $request->detail;

        //image update
        if( $request->hasFile('image') ) {

            //delete old image
            $path = 'assets/user/images/withdraw/';
            $location = $path.$withdraws->image;
            if (file_exists($location)){
                unlink($location);
            }

            //upload new image
            $input_image = Image::make($request->image);
            $image = $input_image->resize(160, 80);
            $image_name = $request->file('image')->getClientOriginalName();
            $image_name = Carbon::now()->format('YmdHs').'_'.$image_name;
            $image->save($path.$image_name);

            //image update
            $withdraws->image = $image_name;
        }

        $withdraws->save();
        //redirect
        Session()->flash('success', 'successfully updated !');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $withdraws = Withdraw::find($request->id);
        $path = 'assets/user/images/withdraw/';

        $location = $path.$withdraws->image;
        if (file_exists($location)){
            unlink($location);
        }

        Withdraw::find($request->id)->delete();
        //redirect
        Session()->flash('success', 'successfully deleted !');
        return redirect()->back();
    }
    public function export_excel(){
        $wrs = WithdrawRequest::get();
        if($wrs->count()==0){
            Session()->flash('warning','No withdrawal request found');
            return redirect()->back();
        }
        else{
            return Excel::download(new AllWithdrawals(), 'withdrawals.xlsx');
        }
    }
}
