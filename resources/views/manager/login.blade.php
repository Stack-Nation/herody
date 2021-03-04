@extends('layouts.app')
@section('title', config('app.name').' | Manager Login')
@section('content')
<!-- Hero Start -->
<section>

    <div class="home-center">
        <div class="home-desc-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="login-page bg-white shadow rounded p-4">
                            <div class="text-center">
                                <h4 class="mb-4">Manager Login</h4>  
                            </div>
                            <form class="login-form" action="{{route('manager.login')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group position-relative">
                                            <label>Your Username <span class="text-danger">*</span></label>
                                            <input name="username" type="text" placeholder="Username" class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group position-relative">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <input name="password" type="password" placeholder="Password" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-0">
                                        <button class="btn btn-primary w-100">Sign in</button>
                                    </div>
                                </div>
                            </form>
                        </div><!---->
                    </div> <!--end col-->
                </div><!--end row-->
            </div> <!--end container-->
        </div>
    </div>
</section><!--end section-->
<!-- Hero End -->
@endsection