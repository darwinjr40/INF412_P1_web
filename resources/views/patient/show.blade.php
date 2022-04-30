@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>{{ $patient['nombre'] }}</h1>
    <strong>{{$patient['edad']. ' años'}}</strong> --}}
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <h1>{{ $patient['nombre'] }}</h1>
        <strong>{{ $patient['edad'] . ' años' }}</strong>
    </div>
</div>
    <div class="card">
        <div class="card-body">
            <h2>Consultas</h2>
            <table class="table table-striped" id="inquiries" border="5">
                <thead>
                    <tr>
                        <th scope="col" width="1%">Id</th>
                        <th scope="col" width="15%">Doctor</th>
                        {{-- <th scope="col" width="15%">Paciente</th> --}}
                        <th scope="col" width="15%">Especialidad</th>
                        <th scope="col" width="15%">fecha</th>
                        {{-- <th scope="col" width="10%">Estado</th> --}}
                        <th scope="col" width="15%">Acciones</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($inquiries as $inquiry)
                        <tr>
                            <td>{{ $inquiry->id }}</td>
                            <td>{{ $inquiry->doctor_nombre }}</td>
                            {{-- <td>{{ $inquiry->patient_nombre }}</td> --}}

                            <td>{{ $inquiry->specialty_nombre }}</td>
                            <td>{{ date('d-m-Y', strtotime($inquiry->fecha)) }}</td>
                            {{-- <td>{{ $inquiry->tipo }}</td> --}}
                            <td>
                                <form action="{{ route('inquiries.destroy', $inquiry->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <a class="btn btn-primary mb1 bg-green btn-sm" href="{{ route('inquiries.show2', $inquiry->id) }}">Detalle</a>
                                    <a class="btn btn-secondary mb1 bg-navy btn-sm" href="{{ route('show', $inquiry->id) }}">ver</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('guardar', $inquiry->id) }}">save</a>
                                    {{-- <a class="btn btn-primary mb1 bg-black btn-sm" href="{{ route('imprimir', $inquiry->id) }}">print</a> --}}
                                    
                                    {{-- <a class="btn btn-info btn-sm" href="{{route('persons.edit',$persona)}}">Editar</a> --}}
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿ESTA SEGURO DE  BORRAR?')" value="Borrar">X</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css"> --}}

    {{-- responsive --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    {{-- responsive --}}
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#inquiries').DataTable({
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@stop
