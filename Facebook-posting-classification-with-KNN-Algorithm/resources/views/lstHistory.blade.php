<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h2>Daftar Posting yang Dicek</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>id</td>
                    <td>Posting</td>
                    <td>Melanggar</td>
                    <td>Detail</td>
                </tr>
            </thead>
            @foreach($hst as $row)
            <tr>
                <td>{{$urut++}}</td>
                <td>{{$row->Posting->posting}}</td>
                <td>
                    @if($row->Posting->melanggar === 1)
                        @foreach($row->Posting->Pelanggaran as $langgar)
                            {{$langgar->pasal.','}}
                        @endforeach
                    @else
                            'Tidak Melanggar'
                    @endif
                </td>
                <td align="center"><a href="{{url('detailhistory/'.$row->id)}}"><span class="glyphicon glyphicon-list-alt"></span></a></td>
            </tr>
            @endforeach
        </table>
</div>
</div>
@endsection