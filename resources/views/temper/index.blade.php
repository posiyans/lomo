@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Температура в Чаще <b>{{ $temper->temp }}°C</b> на {{ $temper->time }} </div>
                <chart-vue></chart-vue>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
