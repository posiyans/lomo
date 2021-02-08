@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">QR код на оплату</div>
                <img class="card-img-top" src="/qrcode/ticket/{{ $devices->id }}/1.png" alt="QrCode">
                    <div class="card-body">
                        <h5 class="card-title">QR код на оплату</h5>
                        <p class="card-text">Номер участка: <b>{{ $stead->number }}</b></p>
                        <p class="card-text">ФИО собственника: <b>{{ $stead->surname }} {{ $stead->name }} {{ $stead->patronymic }}</b></p>
{{--                        @if ( $devices->depends == 1)--}}
                            <ul class="list-group">
                                <li class="list-group-item active">{{ $devices->name }}</li>
                                @foreach ($devices->MeteringDevice as $device)
                                    <li class="list-group-item">Оплата {{ $device->description }} {{ $device->cash_description }} руб.
                                    </li>
                                @endforeach
                                <li class="list-group-item active">Итого: <b>{{$cash}} руб.</b></li>
                            </ul>
{{--                        @endif--}}
{{--                        @if ($devices->depends == 2)--}}
{{--                            <ul class="list-group">--}}
{{--                                <li class="list-group-item active">{{$devices->name}}</li>--}}
{{--                                @foreach ($devices->MeteringDevice as $device)--}}
{{--                                    <li class="list-group-item">--}}
{{--                                        Оплата {{ $device->discription }} {{ $device->cash_description }} руб.--}}
{{--                                    </li>--}}
{{--                                @endforeach--}}
{{--                                <li class="list-group-item active">Итого: <b>{{$cash}} руб.</b></li>--}}
{{--                            </ul>--}}
{{--                        @endif--}}
                        <br>
                        <a href="{{ route('renderPdf', ['id'=>$devices->id, 'stead_id'=>$stead->id]) }}" class="btn btn-primary">Скачать квитанцию для оплаты</a>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
