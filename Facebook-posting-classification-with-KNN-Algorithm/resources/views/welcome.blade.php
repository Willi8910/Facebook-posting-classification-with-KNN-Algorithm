<!-- <!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tugas Akhir</title> -->

        <!-- Fonts -->

        <!-- Styles -->
        <style>
            /* latin */
            @font-face {
              font-family: 'Raleway';
              font-style: normal;
              font-weight: 600;
              src: local('Raleway SemiBold'), local('Raleway-SemiBold'), url(https://fonts.gstatic.com/s/raleway/v12/1Ptrg8zYS_SKggPNwPIsWqZPAA.woff2) format('woff2');
              unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 70vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    <!--< /head>
    <body>  -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="flex-center position-ref full-height" style="font-family: 'Raleway',sans-serif;">
           

            <div class="content">
                <div class="title m-b-md" >
                    WELCOME
                   
                </div>

                <div class="links">
                    <a href="{{ url('cek')}}">Cek Hukuman</a>
                    <a href="{{ url('listPosting')}}">List Posting</a>
                    <a href="{{ url('history')}}">History Posting</a>
                    <a href="{{ url('tag')}}">Edit Stopword</a>
                </div>
            </div>
        <!-- </div> -->
        </div>
</div>
@endsection
  <!--   </body>
</html> -->
