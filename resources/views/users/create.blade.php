@extends('layouts.app')
@section('title') connexion  @endsection
@section('content')
    <div class="page-header user-shadow">
        <h3 class="page-title "> User </h3>
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Create</a></li>
            <li class="breadcrumb-item active" aria-current="page">User</li>
        </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
          <div class="row justify-content-md-center">
            <div class="col-lg-6 col-sm-12">
                <form method="POST"  class="needs-validation" novalidate action="{{ route('users.store') }}">
                    @csrf
                    <div class="row">
                    <h3 class="m-4 text-center">Create User </h3>
                    <div class="col-md-12">
                        <div class="form-group  has-validation">
                            <label> Name</label>
                            <input id="name" type="text" class="form-control" placeholder="User Name"  value="{{ old('name') }}"  name="name"  required>
                            @error('name')
                                <div class="text-danger mt-1 ">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input id="Email" type="Email" class="form-control" placeholder="Email"  value="{{ old('email') }}"  name="email" required>
                            @error('email')
                                <div class="text-danger mt-1 ">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Password</label>
                            <input  id="Email" type="password" class="form-control" placeholder="Password"  value="{{ old('password') }}"  name="password" required>
                            @error('password')
                                <div class="text-danger mt-1 ">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label> Confirm Password </label>
                            <input type="password" class="form-control" placeholder=" Confirm Password "    value="{{ old('confirm_password') }}"   name="confirm_password" required >
                            @error('confirm_password')
                                <div class="text-danger mt-1 ">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group mb-2 mr-sm-2">
                            <label> Location </label>
                            <div class="input-group">

                                <select class="form-select  form-control form-select-sm"
                                    aria-label=".form-select-sm example" id="location" name="location[]"
                                    multiple="multiple" required>


                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class=" m-2">
                        <button type="submit" class="btn btn-success me-2">Submit</button>
                        <button class="btn btn-dark">Cancel</button>
                    </div>

                </form>

            </div>
          </div>
        </div>
      </div>

@endsection

@section('custom_script')


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#location').select2({
            placeholder: "Select a location",
            allowClear: true
            });
    });
</script>


    <script>

    // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                }


                form.classList.add('was-validated')
            }, false)
            })
        })()
    </script>

@endsection

@section('custom_css')

<link rel="stylesheet" href="{{asset('/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>

    /* ***** Select 2 **** */

    .select2.select2-container.select2-container--default {
        width: 100% !important;
        background: #2a3038;
    }

    .select2-container--default .select2-selection--multiple {
        border: none;
        background: #2a3038;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice,
    .select2-container--default .select2-selection--multiple .select2-selection__choice:nth-child(5n+1) {
        font-size: 14px;
        background: #2a3038;
    }

    .select2-container--default .select2-results__option--selected {
        background-color: #297eee;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding: 5px;
        padding-left: 21px;

    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        padding: 5px;
    }
    </style>
@endsection
