@extends('admin.master')

@section('title', 'Admin | withdraw log')

@section('body')

    <div class="container-fluid">
        <h2 class="mb-4">Withdraw Log</h2>

        <div class="card mb-4">
            <div class="card-header bg-white font-weight-bold">
                withdraw
            </div>
            <div class="card-body">
                <table class="table  table-striped table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Withdraw method</th>
                        <th scope="col">User</th>
                        <th scope="col">Payment Detail</th>
                        <th scope="col">Payable Amount</th>
                        <th scope="col">Requested At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Status</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($withdrawRequest as $withdrawRe)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>

                            <td>{{$withdrawRe->withdraw_method->name}}</td>
                            <td>{{$withdrawRe->user->name}}</td>
                            <td>{{$withdrawRe->payment_details}}</td>
                            <td>{{$withdrawRe->payable_amount}}</td>
                            <td>{{$withdrawRe->created_at->format('d M, Y')}}</td>
                            <td>{{$withdrawRe->updated_at->format('d M, Y')}}</td>
                            <td>@if($withdrawRe->status==0) Pending @else Approved @endif</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>



    {{--<!-- order Approve Alert Modal -->--}}
    <div class="modal modal-danger fade" id="withdrawApprove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Approve !</h4>
                </div>
                <form action="{{route('admin.withdraw.approve','Approved')}}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <input class="form-control form-control-lg mb-3" type="hidden" name="id" id="id">
                        <strong>Are you sure you want to Approved ?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--<!-- order reject Alert Modal -->--}}
    <div class="modal modal-danger fade" id="withdrawReject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Reject !</h4>
                </div>
                <form action="{{route('admin.withdraw.reject','delete')}}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <input class="form-control form-control-lg mb-3" type="hidden" name="id" id="id">
                        <strong>Are you sure you want to Delete ?</strong>
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
        $('#Withdraw li:nth-child(3)').addClass('active');
        $('#Withdraw').addClass('show');
    </script>
@endsection


@section('scripts')

    {{--Approve script--}}
    <script>
        $('#withdrawReject').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
        })
    </script>

    {{--Reject script--}}
    <script>
        $('#withdrawApprove').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
        })
    </script>

@endsection
