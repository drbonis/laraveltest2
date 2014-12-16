


@extends('layouts.master')

@section('container')
    <h1>
      Usuarios
    </h1>

    <ul>
      @foreach($usuarios as $usuario)
        <li>
          {{$usuario->nombre.' '.$usuario->apellido}}
        </li>
      @endforeach
    </ul>
    <p>{{}}</p>
@stop
