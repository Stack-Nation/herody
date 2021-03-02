@extends('layouts.app')
@section('title',config('app.name').' | Job Applications')
@section('heads')
<style>
	#shortl{
		background: orange;
		color: white;
		border-color: orange;
	}
	#shortl:hover{
		background: white;
		color: #8B91DD;
		border-color: #8B91DD;
	}
	#reject{
		background: red;
		color: white;
		border-color: red;
	}
	#reject:hover{
		background: white;
		color: #8B91DD;
		border-color: #8B91DD;
	}
	#ans{
		background: gray;
		color: white;
		border-color: gray;
	}
	#ans:hover{
		background: white;
		color: #8B91DD;
		border-color: #8B91DD;
	}
</style>
@endsection
@section('content')
<div class="theme-layout" id="scrollup">
	<section class="overlape">
		<div class="block no-padding"><script src="https://kit.fontawesome.com/f3e18a60ac.js" crossorigin="anonymous"></script>
			<div data-velocity="-.1" style="background: url(images/resource/mslider1.jpg) repeat scroll 50% 422.28px transparent;" class="parallax scrolly-invisible no-parallax"></div><!-- PARALLAX BACKGROUND IMAGE -->
			<div class="container fluid">
				<div class="row">
					<div class="col-lg-12">
						<div class="inner-header">
							<h3>Welcome {{$employer->cname}}</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>
		<div class="block remove-bottom">
			<div class="container">
				 <div class="row no-gape">
           @include('includes.emp-sidebar')
				 	<div class="col-sm-12 col-lg-8 col-xl-9">
           @if($jas->count()==0)
            <div><h1>No data found</h1></div>
            @else
            @foreach($jas as $ja)
            <?php
                $user = DB::table('users')->find($ja->uid);
            ?>
                    @if($user)
				 		<div class="emply-resume-sec">
				 			<div class="emply-resume-list square">
				 				<div class="emply-resume-thumb">
				 					<img src="@if($user->profile_photo!=NULL){{asset('assets/user/images/user_profile/'.$user->profile_photo)}} @else {{asset('assets/user/images/frontEnd/demo.png')}} @endif" alt="" />
				 				</div>
				 				<div class="emply-resume-info">
				 					<h3><a href="{{route('applicant.view',$ja->uid)}}" title="">{{$user->name}}</a></h3>
                  					<p><i class="la la-map-marker"></i>{{$user->state}}</p>
									<p><i></i>{{$user->email}}</p>
									<p><i></i>{{$user->phone}}</p>
				 				</div>
				 				<div class="shortlists">
								 	@if(\App\Shortlisted::where(['jid'=>$ja->jid,'uid'=>$ja->uid])->exists())
				 					<a href="#shortlisted" class="mr-2" title="" id="shortl">Shortlisted <i class="fa fa-plus"></i></a>
									@else
				 					<a href="{{route('employer.job.shortlist',[$ja->jid,$ja->uid])}}" class="mr-2" title="" id="shortl">Shortlist <i class="fa fa-plus"></i></a>
									@endif
				 					<a href="{{route('applicant.view',$ja->uid)}}" target="_blank" class="mr-2" title="" id="viewp">View Profile <i class="fa fa-eye"></i></a>
									 @if(\App\Reject::where(['uid' => $ja->uid,'jid'=>$ja->jid])->exists())
									 <a href="#" class="mr-2" id="reject" title="">Rejected <i class="fa fa-minus"></i></a>
									 @else
									 <a href="{{route('employer.job.reject',[$ja->jid,$ja->uid])}}" class="mr-2" id="reject" title="">Reject <i class="fa fa-minus"></i></a>
									 @endif
				 					<a href="{{route('employer.job.answers',[$ja->jid,$ja->uid])}}" target="_blank" class="mr-2 mt-2" title="" id="ans">View Answers <i class="fa fa-question"></i></a>
				 				</div>
				 			</div>
			 </div>
			 @endif
			 @endforeach
			 <div class="col-md-12">
				 <form action="{{route('employer.job.shortlistall')}}" method="post" class="mr-2">
					@csrf
				 	<input type="hidden" name="id" value="{{$ja->jid}}">
					<button type="submit" class="btn btn-success">Shortlist all</button>
				</form>
				 <form action="{{route('employer.job.selectall')}}" method="post" class="mr-2">
					@csrf
					<input type="hidden" name="id" value="{{$ja->jid}}">
					<button type="submit" class="btn btn-success">Select all</button>
				</form>
				<form action="{{route('employer.job.rejectall')}}" method="post" class="mr-2">
					@csrf
					<input type="hidden" name="id" value="{{$ja->jid}}">
					<button type="submit" class="btn btn-danger">Reject all</button>
				</form>
			 </div>
             {{$jas->links()}}
             @endif
			 <a href="{{route('employer.job.shortlisteds',$ja->jid)}}" class="btn btn-primary btn-sm mb-2">Shortlisted Users</a>
			 <a href="{{route('employer.job.selecteds',$ja->jid)}}" class="btn btn-primary btn-sm mb-2">Selected Users</a>
			 <a href="{{route('employer.job.exportapps',$ja->jid)}}" class="btn btn-primary btn-sm mb-2">Export Applications</a>
					</div>
				 </div>
			</div>
		</div>
	</section>
</div>
@endsection