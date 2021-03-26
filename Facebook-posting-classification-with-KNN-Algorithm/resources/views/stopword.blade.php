
    <meta name="robots" content="index, follow" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::to('plug/tag/bootstrap-tagsinput.css')}}">
   <style type="text/css">
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
    
   
    <div id="snackbar">Data StopWord telah diupdate</div>
    <div id="fb-root"></div>
@extends('layouts.app')

@section('content')
    <div class="container">
      <section id="examples">
        <div class="page-header">
          <h2>Stopword</h2>
        </div>

<div class="example example_markup">
  <h3>Edit Stopword</h3>
  <p>Stopword merupakan kata sambung yang kurang berguna dalam pencarian, masukkan kata-kata yang dianggap kurang berguna dalam pencarian untuk mempermudah pengecekan</p>
  <form > <!-- action="{{url('savetag')}}" method="GET" -->
    <div class="bs-example">
      <input type="text" id="txt" name="word" value="{{$stopword}}" data-role="tagsinput"  />
    </div>
    <br>
    <input class="btn btn-default" type="submit" name="btnrest"  value="Reset"> &nbsp
    <input class="btn btn-default" type="button" value="Save" name="Save" onclick="saved();">
  </form>
</div>
          
          

        </section>
      </div>
@endsection
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular.min.js"></script>
    <script src="{{URL::to('plug/tag/bootstrap-tagsinput.min.js')}}"></script>

  <script type="text/javascript">
    $("#txt").on('change',function(){
      //alert("{{URL::to('a')}}");
    });
    function saved() {
      var ipt = $("#txt").val();
      $.ajax({
          type : 'GET',
          url : '{{url("savetag")}}',
          data :  {'word' : ipt},
              success: function (data) {
                var x = document.getElementById("snackbar");
              x.className = "show";
              setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
          }
      });
    }
    function RestStopword()
    {
      alert("{{$stopword}}");
      $("#txt").val("");
      alert($("#txt").val());
    }
  </script>


