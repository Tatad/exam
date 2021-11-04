@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}


                        </div>
                    @endif

                    @if(Auth::user()->is_verified != 1)
                        <p>Please verify your account using the <b>PIN code</b> that was sent to your email</p>
                        @if($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                              {{$errors->first()}}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                        @endif


                        <form class="form-inline" method="POST" action="{{ route('verify') }}">
                            @csrf
                            <input maxlength="6" type="text" class="form-control mb-2 mr-sm-2" name="pin" id="pinCOde" placeholder="Pin Code">
                            <button type="submit" class="btn btn-primary mb-2">Submit</button>
                        </form>
                    @else
                        @if (\Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                              {{ session()->get('success') }}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                              {{$errors->first()}}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                        @endif

                        @if(Auth::user()->avatar != '')
                            <div class="row">
                                <div class="col-12">
                                    <img class="mx-auto d-block" src="{{Auth::user()->avatar}}" alt="avatar image">  
                                </div>
                            </div>
                            <br>
                        @endif
                        <form method="POST" action="{{ route('update-user') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row col-sm-7">
                                <input type="text" class="form-control mb-2 mr-sm-2" name="name" value="{{Auth::user()->name}}" id="name" placeholder="Name">
                            </div>
                            <div class="row col-sm-7">
                                <input type="text" class="form-control mb-2 mr-sm-2" name="user_name" value="{{Auth::user()->user_name}}" id="user_name" placeholder="Username">
                            </div>
                            <div class="row col-sm-7">
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="customFile" name="avatar">
                                  <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                            <br/>
                            <button type="submit" class="btn btn-primary mb-2">Submit</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
