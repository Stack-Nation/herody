@extends('admin.master')

@section('title', 'Admin | All Internships')

@section('body')

    <div class="container-fluid">
        <h2 class="mb-4">Internships List</h2>

        <div class="card mb-4">
            <div class="card-header bg-white font-weight-bold">
                Internships
            </div>
            <div class="card-body">
                @if(count($jobs)==0)
                    <h2 class="text-center">@lang('No Data Available')</h2>
                @else
                    <table class="table  table-striped table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Deadline</th>
                            <th scope="col">Stipend</th>
                            <th scope="col">Number of candidates Required</th>
                            <th scope="col">Work Place</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($jobs as $job)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$job->title}}</td>
                                <td>{{$job->cat}}</td>
                                <td>{{$job->end}}</td>
                                <td>{{$job->stipend}}</td>
                                <td>{{$job->count}}</td>
                                <td>{{$job->place}}</td>
                                <td>
                                    @if($job->mobile==0)
                                    <form action="{{route('admin.job.make-mobile')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$job->id}}">
                                        <button type="submit" class="btn btn-success btn-sm">Make mobile specific</button>
                                    </form>
                                    @else
                                    <form action="{{route('admin.job.undo-mobile')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$job->id}}">
                                        <button type="submit" class="btn btn-danger btn-sm">Undo mobile specific</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
                {{$jobs->links()}}
            </div>
        </div>
    </div>


    {{--<!-- order Approve Alert Modal -->--}}
    <div class="modal modal-danger fade" id="OrderApprove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Approve !</h4>
                </div>
                <form action="{{route('admin.job.approve')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input class="form-control form-control-lg mb-3" type="hidden" name="id" id="id">
                        <strong>Are you sure you want to Approve ?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    {{--dropdown active--}}
    <script>
        $('#pending li:nth-child(2)').addClass('active');
        $('#pending').addClass('show');
    </script>
@endsection