@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Расписание электричек</div>
                <yandex-rasp-vue></yandex-rasp-vue>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
