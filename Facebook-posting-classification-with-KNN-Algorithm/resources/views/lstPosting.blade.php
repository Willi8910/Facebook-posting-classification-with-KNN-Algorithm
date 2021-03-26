<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<style>
/*ini untuk btn close*/
* {
  box-sizing: border-box;
}

.ulist {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.listitem{
  border: 1px solid #ddd;
  margin-top: -1px;  
  background-color: #f6f6f6;
  padding: 12px;
  text-decoration: none;
  font-size: 18px;
  color: black;
  display: block;
  position: relative;
}

.pagination li
{
    border: 0px solid white;
    background-color: white;
}
.pagination li:hover
{
    background-color: white;
}

.listitem:hover {
  background-color: #eee;
}

.close {
  cursor: pointer;
  position: absolute;
  top: 50%;
  right: 0%;
  padding: 12px 16px;
  transform: translate(0%, -50%);
}

.close:hover {background: #bbb;}

/*untuk snack bar*/
#snackbar {
    visibility: hidden;
    min-width: 250px;
    margin-left: -125px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    top: 60px;
    font-size: 17px;
}

#snackbar.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
    from {top: 0; opacity: 0;} 
    to {top: 60px; opacity: 1;}
}

@keyframes fadein {
    from {top: 0; opacity: 0;}
    to {top: 60px; opacity: 1;}
}

@-webkit-keyframes fadeout {
    from {top: 60px; opacity: 1;} 
    to {top: 0; opacity: 0;}
}

@keyframes fadeout {
    from {top: 60px; opacity: 1;}
    to {top: 0; opacity: 0;}
}
</style>
<div id="snackbar">Some text some message..</div>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
           <!--  <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a> -->
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ol class="nav navbar-nav">
                <li>
                  <a href="{{url('admin')}}">
                    <span class="glyphicon glyphicon-home"></span>
                  </a>
                </li>
            </ol>

            <!-- Right Side Of Navbar -->
            <ol class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ol class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ol>
                    </li>
                @endif
            </ol>
        </div>
    </div>
</nav>


<div class="container">
<h2>Filterable Table</h2>
<div class="input-group">
    <span class="input-group-addon">Filter</span>
    <input id="myInput" type="text" class="form-control" name="msg" placeholder="Search">
</div>
<br>
<br>
{{ $posting->links() }}

<h3>Data Posting</h3>  
<!-- <input id="myInput" type="text" placeholder="Search.."> -->
<br>
<table class="table table-striped" id="myTable">
    <thead>
    <tr>
        <td>id</td>
        <td>Posting</td>
        <td width="10%">Pelanggaran</td>
    </tr>
    </thead>
    <tbody>
    @foreach($posting as $row)
    <tr>
      <td>{{$row->id}}</td>
      <td>{{$row->posting}}</td>
      <td>
          <ul id="{{'s'.$row->id}}" class="ulist">
          @foreach($row->Pelanggaran as $langgar)
          <li class="listitem">
          <select class="select" >
          @foreach($pelanggaran as $pel)
            @if($pel->id === $langgar->id)
                <option selected value="{{$row->id.','.$langgar->id.','.$pel->id}}">{{$pel->pasal}}</option>
            @else
                <!-- ini untuk pelanggaran kalo 2 ato lebih seupaya tidak muncul 2x jadi tidak input bersamaan -->
                @php
                  $pellain = $row->Pelanggaran->find($pel->id);
                  if($pellain == null)
                      echo '<option value="'.$row->id.','.$langgar->id.','.$pel->id.'">'.$pel->pasal.'</option>';
                @endphp
            @endif

          @endforeach
          </select><span class="close" id="{{$row->id.','.$langgar->id}}">&times;</span>
          </li>

          @endforeach
           <li class="listitem"><button onclick="alert('aaa'); type="button" value="{{$row->id}}" data-toggle="modal" data-target="#myModal" style="margin-left: 40%" class="btnModal btn btn-default btn-sm"> <span class="glyphicon glyphicon-plus"></span></button></li>
          </ul>            
      </td>
    </tr>
    @endforeach
    </tbody>
</table>
{{ $posting->links() }}
</div>

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Masukkan Hukuman Pelanggaran Posting</h4>
        </div>
        <div class="modal-body" id="modal-body">
          <p id="mposting">Some text in the modal.</p>
          <p>Tentukan hukuman postingan ini:</p>
          <select id="mspelanggaran">
          @foreach($pelanggaran as $pel)
                <option value="{{$pel->id}}">{{$pel->pasal}}</option>
            @endforeach
            </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="btnAdd" value="0" class="btn btn-default" data-dismiss="modal">Save</button>
        </div>
      </div>
      
    </div>
  </div>


<script type="text/javascript">

// $(document).ready(function(){
$('.select').on('change', function() {
    var txt = $("input").val();
    var idpel = this.id;
    $.ajax({
    type : 'GET',
    url : '{{url("/updatePelanggaran")}}',
    data :  {'pel' : this.value},
        success: function (data) {
            //(data);
            if(data == 0)
            {
              $('#snackbar').html("Hukuman pada posting ini sudah ada");
              window.location.reload(true);
            }
            else  
              $('#snackbar').html("Data Telah berhasil diubah");
            showSnackNotif();
    }
    });
      // alert( this.id );
    });

var closebtns = document.getElementsByClassName("close");
var i;

for (i = 0; i < closebtns.length; i++) {
  closebtns[i].addEventListener("click", function() {
  if(confirm("Apakah anda yakin ingin menghapus pelanggaran ini?"))
   {
      this.parentElement.style.display = 'none';
      var val = this.id;
      $.ajax({
        type : 'GET',
        url : '{{url("/deletePelanggaran")}}',
        data :  {'pel' : val},
            success: function (data) {
                //(data);
                $('#snackbar').html("Data telah berhasil dihapus");
                showSnackNotif();
                

        }
        });
    }
  });
}

function showSnackNotif() {
    var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

$(".btnModal").on('click',function(){
    $("#btnAdd").val(this.value);
    var idpost = this.value;
    $.ajax({
        type : 'GET',
        url : '{{url("/getPosting")}}',
        data :  {'post_id' : idpost},
            success: function (data) {
                 $("#mposting").text(data);
        }
    });
});

$("#btnAdd").on('click',function(){
    idpost = (this.value);
    idpel = $("#mspelanggaran").val();
    $.ajax({
      type : 'GET',
      url : '{{url("/addPelanggaran")}}',
      data :  {'post_id' : idpost, 'pel_id' : idpel},
      success: function (data) {
         if(data != 'no')
         {
         
            // $("#s"+idpost).trigger('create');
            
            //$("#s"+idpost).before(data);
            $('#snackbar').html("Data baru berhasil dimasukkan");
            window.location.reload(true);
         }
         else
         {
            $('#snackbar').html("Data sudah ada silahkan masukkan data yang belum ada");

         }
            showSnackNotif();
      }
    });
});



$("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

// });
</script>