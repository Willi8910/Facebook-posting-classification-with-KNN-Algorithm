<table border="1">
  <thead>
    <tr>
      <td>id</td>
      <td>Posting</td>
      <td>pasal</td>
    </tr>
  </thead>
  <tbody>
    @foreach($Posting as $value)
    <tr>
      <td>{{$value->id}}</td>
      <td>{{$value->posting}}</td>
      <td>
         @foreach($value->Pelanggaran as $row)
           {{$row->pasal .','}}
         @endforeach
      </td>
    </tr>
    @endforeach
  </tbody>
</table>