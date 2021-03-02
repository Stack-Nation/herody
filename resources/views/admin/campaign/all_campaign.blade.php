@extends('admin.master')

@section('title', 'Admin | All Gigs')

@section('body')

    <div class="container-fluid">
        <h2 class="mb-4">Gigs List</h2>

        <div class="card mb-4">
            <div class="card-header bg-white font-weight-bold">
                Gigs
            <div class="float-right">
                <a href="{{route('admin.campaign.create')}}" class="btn btn-primary btn-sm">Create Gig</a>
            </div>
            </div>
            <div class="card-body">
                <table class="table  table-striped table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Per job cost</th>
                        <th scope="col">User</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($campaigns as $campaign)
                    <?php
                        if($campaign->user_id=="Admin"){
                            $user = "Admin";
                        }
                        else{
                            $user = DB::table('employers')->find($campaign->user_id);
                            $user = $user->name;
                        }
                    ?>
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$campaign->campaign_title}}</td>
                            <td>{{$campaign->per_cost}}</td>
                            <td>{{$user}}</td>
                            @if($user=="Admin")<td><a href="{{route('admin.campaign.app',$campaign->id)}}">View Applications</a></td>@endif
                            <td>
                                @if($campaign->mobile==0)
                                <form action="{{route('admin.campaign.make-mobile')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$campaign->id}}">
                                    <button type="submit" class="btn btn-success btn-sm">Make mobile specific</button>
                                </form>
                                @else
                                <form action="{{route('admin.campaign.undo-mobile')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$campaign->id}}">
                                    <button type="submit" class="btn btn-danger btn-sm">Undo mobile specific</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                {{$campaigns->links()}}
            </div>
        </div>
    </div>


    {{--dropdown active--}}
    <script>
        $('#Campaigns li:nth-child(3)').addClass('active');
        $('#Campaigns').addClass('show');
    </script>
@endsection
