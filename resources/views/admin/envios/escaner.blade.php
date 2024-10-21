@extends('admin.master')

@section('title', 'Detalles del Envío')

@section('content')
<div class="container-fluid">
    <div class="panel shadow">
        <div class="header">
            <h2 class="title"><i class="fas fa-box"></i> Detalles del Envío</h2>
        </div>
        <div class="inside">
            <p>Código: {{ $envio->codigo }}</p>
            <p>Estado actual: {{ $envio->estado }}</p>
            <!-- Agrega más detalles del envío aquí -->

            <form action="{{ route('envio.actualizarEstado', $envio->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                
            </form>
        </div>
    </div>
</div>
@endsection