<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>

  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <style type="text/css">
        .colorgraph {
          height: 5px;
          border-top: 0;
          background: #c4e17f;
          border-radius: 5px;
          background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
          background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
          background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
          background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        }

        .loader {
          border: 16px solid #f3f3f3;
          border-radius: 50%;
          border-top: 16px solid blue;
          border-right: 16px solid green;
          border-bottom: 16px solid red;
          border-left: 16px solid pink;
          width: 100px;
          height: 100px;
          -webkit-animation: spin 2s linear infinite;
          animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
          0% { -webkit-transform: rotate(0deg); }
          100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        /* Add animation to "page content" */
        .animate-bottom {
          position: relative;
          -webkit-animation-name: animatebottom;
          -webkit-animation-duration: 1s;
          animation-name: animatebottom;
          animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
          from { bottom:-100px; opacity:0 } 
          to { bottom:0px; opacity:1 }
        }

        @keyframes animatebottom { 
          from{ bottom:-100px; opacity:0 } 
          to{ bottom:0; opacity:1 }
        }

        #dhasil {
          display: none;
          text-align: center;
        }
    </style>
<body>


    <!------ Include the above in your HEAD tag ---------->

    <div class="container">

    <div class="row">

        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <form method ="Get" action='{{url("cekposting")}}'>
                            <input type="hidden" name="_token" value="{!! csrf_token()!!}"/> 

                <h2>Silahkan Input Postingan anda <small>Terpercaya dan benar sekali</small></h2>
                <hr class="colorgraph">
                <div class="form-group">
                    <textarea type="area" name="txtinput" id="txtinput" rows=6 class="form-control input-lg" placeholder="Input Posting" tabindex="3"></textarea>
                </div>
                
                

                <div class="row">
                    
                    <div class="col-xs-12 col-md-12">
                        <input type="button" name="btncrawl" onclick="Cek();" class="btn btn-success btn-block btn-lg" value="Submit">
                    </div>
                </div>

                @isset($kata)
                  <h4>{{$kata}}</h4>
                @endisset

               
            </form>
        </div>
        <br><br>
        <div class="col-md-offset-5 col-md-8 loader" style="margin-top: 40px; display: none;"></div>
         <div id="dhasil" style="display:none;text-align: left;"   class="col-md-offset-2 col-md-8 animate-bottom">
              test
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
  var myVar;

  function Cek()
  {
    myVar = startLoading();
    var ipt =$("#txtinput").val();
    $.ajax({
        type : 'GET',
        url : '{{url("cekposting")}}',
        data :  {'txtinput' : ipt},
            success: function (data) {
                 // $("#mposting").text(data);
                 $("#dhasil").html(data);
                 myVar = setTimeout(endLoading, 1000);
        }
    });
  }

  function startLoading()
  {
    $(".loader").show('100');
    //$(".loader").css('display','inline-block');
    $("#dhasil").css('display','none');
  }
  function endLoading()
  {
    $(".loader").css('display','none');
    $("#dhasil").css('display','inline-block');
  }

</script>