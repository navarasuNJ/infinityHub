@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if(Session::has('error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    
                    <div class="row">
                        <p>Api Token : <b>{{ $token }}</b></p>
                        <div class="offset-md-9 col-md-3">
                            <a href="{{ route('import') }}" class="form-control btn btn-primary">Import Data</a>
                        </div>
                        <br/>
                        <br/>
                        <div class="col-md-4 card">
                            <h2 class="p-4"> {{ $countries }} Countries</h2>
                        </div>
                        <div class="col-md-4 card">
                            <h2 class="p-4"> {{ $states }} States</h2>
                        </div>
                        <div class="col-md-4 card">
                            <h2 class="p-4"> {{ $cities }} Cities</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
