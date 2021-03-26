@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="font-family: 'Raleway',sans-serif;">
        
            <a href="{{url('history')}}" class="btn btn-default">
              <span class="glyphicon glyphicon-arrow-left" style="color: gray;"></span> Back
            </a>
        
        <h2>Detail History</h2>
        <h4>id-History : {{$hst->id}}</h4>
        <h4>id-Posting : {{$hst->Posting->id}}</h4>
        <h4>Posting : {{$hst->Posting->posting}}</h4>
        <h4>Posting Clean : {{$hst->Posting->posting_clean}}</h4>
        @if($hst->Posting->melanggar === 1)
            <h4>Postingan ini melanggar UU ITE, postingan ini terancam akan terkena hukuman maksimal selama {{$hukum}} tahun dan\atau denda maksimal sebanyak {{$denda}}</h4>
            <h4>Pasal yang dilanggar yaitu: </h4>
            <ol>
            @foreach($hst->Posting->Pelanggaran as $langgar)
                <li>{{$langgar->pasal.'('.$langgar->keterangan.')'}}</li>
            @endforeach
            </ol>
        @else
            <h4>Postingan ini tidak melanggar UU ITE</h4>
        @endif
        <h4>Waktu Dimasukkan : {{$hst->created_at}}</h4>
        <h4>Postingan lain yang berdekatan dengan postingan ini:</h4>
        <table  class="table table-striped">
            <thead>
                <tr>
                    <td>id</td>
                    <td>Posting</td>
                </tr>
            </thead>
            <tbody>
                @foreach($arPosting as $posting)
                <tr>
                    <td>{{$posting->id}}</td>
                    <td>{{$posting->posting}}</td>
                </tr>
                @endforeach
            </tbody>    
        </table>
    </div>
</div>
@endsection