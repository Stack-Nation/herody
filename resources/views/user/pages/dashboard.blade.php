@extends('layouts.authApp')
@section('title',config('app.name').' | Dashboard')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-12">
					<div class="page-head">
						<h4 class="mb30">Dashboard</h4>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">
							Works Applied
						</h4>
					</div>
					<div class="card-body">
						<div class="timer">{{$user->works->count()}}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection