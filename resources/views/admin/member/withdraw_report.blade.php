@extends('admin.master')

@section('title', 'Admin | member withdraw log')

@section('body')

    <div class="container-fluid">
        <h2 class="mb-4">Member Withdraw Log</h2>

        <div class="card mb-4">
            <div class="card-header bg-white font-weight-bold">
                Withdraw
            </div>
            <div class="card-body">
                @if(count($withdrawLogs)==0)
                    <h2 class="text-center">@lang('No Data Available')</h2>
                @else
                    <table class="table  table-striped table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Withdraw method</th>
                            <th scope="col">User</th>
                            <th scope="col">Payment Detail</th>
                            <th scope="col">Payable Amount</th>
                            <th scope="col">Requested At</th>
                            <th scope="col">Status</th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($withdrawLogs as $withdrawLog)
                            <tr>
                                <td>{{$withdrawLog->withdraw_method->name}}</td>
                                <td>{{$withdrawLog->user->name}}</td>
                                <td>{{$withdrawLog->payment_details}}</td>
                                <td>{{$withdrawLog->payable_amount}}</td>
                                <td>{{$withdrawLog->created_at->format('d M, Y')}}</td>

                                <td>
                                    @if ($withdrawLog->status==1)
                                        <span class="badge  badge-pill  badge-success">@lang('Approved')</span>
                                    @elseif($withdrawLog->status==2)
                                        <span class="badge  badge-pill  badge-danger">@lang('Rejected')</span>
                                    @else
                                        <span class="badge  badge-pill  badge-warning">@lang('Pending')</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
                    {{$withdrawLogs->links()}}
            </div>
        </div>
    </div>


    {{--dropdown active--}}
    <script>
        $('#memberSetting li:nth-child(1)').addClass('active');
        $('#memberSetting').addClass('show');
    </script>
@endsection

