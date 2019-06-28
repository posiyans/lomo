@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Получить квитанцию на оплату</div>

                <div class="card-body">
                    <form method="post" action="{{ route('receipt') }}">
                        @csrf
                        <div class="form-group">
                            <label for="number">Введите номер участка</label>
                            <input type="text" class="form-control" name="stead" id="number" placeholder="" value="{{ $stead ? $stead->number:'' }}">
                        </div>
                        <div class="form-group">
                            <label for="receipt">Выберите тип квитанции</label>
                            <select class="form-control" id="receipt" name="receipt">
                                @foreach ($receipts as $receipt)
                                    <option value="{{ $receipt->id }}">{{ $receipt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-info btn-block" type="submit">Далее</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
