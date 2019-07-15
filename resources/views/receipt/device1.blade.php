@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Получить квитанцию на оплату</div>

                <div class="card-body">
                    <form method="post" action="{{ route('ticket', $receipt->id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="receipt">Квитанция на  </label>
                            <div>
                                <ui>
                                    @foreach ($devices as $device)
                                        <li>{{ $device->discription }} {{ $device->rate->discription }}.</li>
                                    @endforeach
                                </ui>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number">Введите номер участка</label>
                            <input type="text" class="form-control" name="stead" id="number" readonly value="{{ $stead ? $stead->number:'' }}">
                        </div>
                        @if ($receipt->depends == 1)
                            <div class="form-group">
                                <label for="size">Размер участка в м<sup>2</sup></label>
                                <input type="text" class="form-control" name="size" id="size" placeholder="" value="{{ $stead->size }}">
                            </div>
                        @endif
                        @if ($receipt->depends == 2)
                            @foreach ($devices as $device)
                                <div class="form-group">
                                    <label for="number">Показание {{ $device->discription }}</label>
                                    <input type="text" class="form-control" name="device[{{ $device->id }}]" id="number" value="">
                                    @if ($device->LastIndication)
                                        <small  class="form-text text-muted">
                                            Последние показания {{ $device->LastIndication->value }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        @if ($stead)
                            <div class="form-group">
                                <label for="surname">Фамилия</label>
                                <input type="text" class="form-control" name="surname" id="surname" placeholder="" value="{{ $stead->surname }}">
                            </div>
                            <div class="form-group">
                                <label for="name">Имя</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{ $stead->name }}">
                            </div>
                            <div class="form-group">
                                <label for="patronymic">Отчество</label>
                                <input type="text" class="form-control" name="patronymic" id="patronymic" placeholder="" value="{{ $stead->patronymic }}">
                            </div>
                            <div class="form-group">
                                <label for="email">e-mail</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="" value="{{ $stead->email }}">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" checked="checked" name="save"> Запомнить мои данные
                                </label>
                            </div>
                        @endif
                        <button class="btn btn-info btn-block" type="submit">Далее</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
