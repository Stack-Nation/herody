@extends('admin.master')

@section('title', 'Admin | Gigs log')

@section('body')

    <div class="container-fluid">
        <h2 class="mb-4">Gigs Log</h2>

        <div class="card mb-4">
            <div class="card-header bg-white font-weight-bold">
                Gigs
            </div>
            <div class="card-body">
                @if(count($agigs)==0)
                    <h2 class="text-center">@lang('No Data Available')</h2>
                @else
                    <table class="table  table-striped table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Brand</th>
                            <th scope="col">Per Cost</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($agigs as $agig)
                        <?php $gig = \App\Gig::find($agig->cid);
                        ?>
                            <tr>
                                <td>{{$gig->campaign_title}}</td>
                                <td>{{$gig->brand}}</td>
                                <td>{{$gig->per_cost}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
                {{$agigs->links()}}
            </div>
        </div>
    </div>


    {{--dropdown active--}}
    <script>
        $('#memberSetting li:nth-child(1)').addClass('active');
        $('#memberSetting').addClass('show');
    </script>
@endsection
