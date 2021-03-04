@extends('layouts.app')
@section('title',config('app.name').' | ' .$employer->name)
@section('content')
@include('includes.emp-sidebar')
<div class="page-content p-5" id="content">
@include('includes.col-btn')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-12">
					<h4 class="mb30">Dashboard</h4>
				</div>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="ff_one">
						<div class="icon"><span class="flaticon-paper-plane"></span></div>
						<div class="detais">
							<div class="timer">{{$employer->projects->count()}}</div>
							<p>Projects</p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="ff_one style2">
						<div class="icon"><span class="flaticon-favorites"></span></div>
						<div class="detais">
							<div class="timer">{{$employer->gigs->count()}}</div>
							<p>Gigs</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
</div>
@endsection