{{-- a view foi customizada para utilizar o componente Vue --}}

@extends('layouts.app')

@section('content')

    {{-- criando o componente de login --}}
    {{-- envia como parâmetros o csrf token --}}
    <login-component csrf_token="{{ @csrf_token() }}"></login-component>
@endsection
