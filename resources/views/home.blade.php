@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Home</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                 Account Id:  <strong>{{ auth()->user()->id }}</strong><br>
                 Account Name:  <strong>{{ auth()->user()->name }}</strong><br>
                 Account Email: <strong>{{ auth()->user()->email }}</strong><br>
                 Created At: <strong>{{ auth()->user()->created_at }}</strong><br>
                 Updated At: <strong>{{ auth()->user()->updated_at }}</strong><br>

                </div>
            </div>
           
        </div>
        
    </div>
</div>
@endsection
