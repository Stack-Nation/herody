@extends('layouts.app')
@section('title',config('app.name').' | Work Applications')
@section('content')
@include('includes.emp-sidebar')
<div class="page-content" id="content">
@include('includes.col-btn')
<!-- JOB LIST START -->
<section class="section pt-0">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12">
				<div class="section-title text-center mb-4 pb-2">
					<h4 class="title title-line pb-5">Manage Work Applications</h4>
				</div>
			</div>
		</div>
		<div class="row">
			@if($applications->count()===0)
			<div class="col-md-12">
				<p>No data Found</p>
			</div>
			@else
			@foreach ($applications as $application)
			<div class="col-lg-4 col-md-6 mt-4 pt-2">
				<div class="list-grid-item rounded">
					<div class="grid-item-content p-3">
						<ul class="list-inline mb-0">
							<li class="list-inline-item f-15"><span class="badge badge-{{$application->status===0?"info":($application->status===1?"primary":($application->status===2?"success":($application->status===3?"danger":"")))}}">{{$application->status===0?"Applied":($application->status===1?"Shortlisted":($application->status===2?"Selected":($application->status===3?"Rejected":"")))}}</span></li>
						</ul>
						<div class="grid-list-img mt-3">
							<img src="@if($application->worker->profile_photo==NULL) {{asset('assets/user/images/frontEnd/demo.png')}} @else {{asset('assets/user/images/user_profile/'.$application->worker->profile_photo)}} @endif" alt="" class="img-fluid d-block avatar avatar-small mr-3 rounded-pill">
						</div>
						<div class="grid-list-desc mt-3">
							<h5 class="mb-1"><a href="{{route("applicant.view",$application->worker->id)}}" class="text-dark">{{$application->worker->name}}</a></h5>
							<p class="text-muted f-14 mb-1">{{$application->worker->email}}</p>
						</div>
					</div>
					
					<div class="apply-button p-3 border-top">
						<div class="row">
							@if($application->status===0)
							<div class="col-md-4">
								<form action="{{route("employer.work.shortlist",$application->work->id)}}" method="post">
									@csrf
									<input type="hidden" name="id" value="{{$application->worker->id}}">
									<button class="btn btn-info btn-sm">Shortlist</button>
								</form>
							</div>
							@endif
							@if($application->status===1)
							<div class="col-md-4">
								<form action="{{route("employer.work.select",$application->work->id)}}" method="post">
									@csrf
									<input type="hidden" name="id" value="{{$application->worker->id}}">
									<button class="btn btn-success btn-sm">Select</button>
								</form>
							</div>
							@endif
							@if($application->status===0 or $application->status===1)
							<div class="col-md-4">
								<form action="{{route("employer.work.reject",$application->work->id)}}" method="post">
									@csrf
									<input type="hidden" name="id" value="{{$application->worker->id}}">
									<button class="btn btn-danger btn-sm">Reject</button>
								</form>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
			@endforeach
			@endif
			{{$applications->links()}}
		</div>
	</div>
</section>
<!-- JOB LIST START -->
</div>
@endsection