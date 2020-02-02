@extends('layouts.app',[
  'class'=>'',
  'elementActive'=>'harga'
])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header ">
                        <h5 class="card-title">{{__("Tambah Harga Sawit")}}</h5>
                        <div class="text-right">
                            <a href="{{ route('harga.index') }}" class="btn btn-sm btn-primary">{{ __('Kembali') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('harga.update',$harga)}}" method="post">
                            @method('put')
                            @csrf
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('tanggal') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-tanggal">{{ __('Tanggal') }}</label>
                                    <input type="text" name="tanggal" id="input-tanggal" class="form-control datepicker form-control-alternative{{ $errors->has('tanggal') ? ' is-invalid' : '' }}" placeholder="{{ __('Masukkan Tanggal') }}" value="{{ old('tanggal',$harga->tanggal) }}" required autofocus>
                                    @if ($errors->has('tanggal'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('tanggal') }}</strong>
                                            </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('harga') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('Harga') }}</label>
                                    <input type="text" name="harga" id="input-harga" class="form-control form-control-alternative{{ $errors->has('harga') ? ' is-invalid' : '' }}" placeholder="{{ __('Masukkan Harga') }}" value="{{ old('harga',$harga->harga) }}" required>

                                    @if ($errors->has('harga'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('harga') }}</strong>
                                            </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#harga-table').dataTable();
        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            language: "id"
        });
    </script>
@endpush
