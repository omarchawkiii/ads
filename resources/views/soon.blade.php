@extends('layouts.app')
@section('title')
    connexion
@endsection
@section('content')
    <div class="page-header user-shadow">
        <h3 class="page-title ">Coming Soon </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Coming Soon</li>
            </ol>
        </nav>
    </div>
    <div class="row  ">
        <div class="col-xl-4">

        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <div class="row multiplex">
              <h1 class="text-center comming_soon">Coming Soon</h1>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        let content_height = document.querySelector('.content-wrapper').offsetHeight;
        let navbar_height = document.querySelector('.navbar').offsetHeight;
        //let footer_height = document.querySelector('.footer').offsetHeight;
        let page_header_height = document.querySelector('.page-header ').offsetHeight;
        let content_max_height = content_height - navbar_height - page_header_height - 50;

        $(".multiplex").height(content_max_height);


    </script>
@endsection

@section('custom_css')
    <style>
        .comming_soon
        {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 250px;
            margin-left: -100px;
            margin-top: -50px;
            text-align: center;
            padding: 1em 0;
        }
        .comming_soon_block
        {

        }
    </style>
@endsection
