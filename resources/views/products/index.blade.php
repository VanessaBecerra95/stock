@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
<div class="container mx-auto mt-10">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold">Lista de Productos</h1>
        <div class="flex items-center">
            <span class="mr-4">¡Bienvenido! 👋: {{ session('user_email') }}</span>
            <span class="mr-4"> Contraseña: {{ session('password') }}</span>
            <a href="{{ route('logout') }}" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                Cerrar Sesión
            </a>
        </div>
    </div>

    @if (session('success'))
    <div x-data="{ show: true }" x-show="show" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-md mb-4" role="alert">
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Cerrar</title>
                <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 101.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
            </svg>
        </button>
    </div>
    @endif

    @if (session('error'))
    <div x-data="{ show: true }" x-show="show" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-md mb-4" role="alert">
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Cerrar</title>
                <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 101.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
            </svg>
        </button>
    </div>
    @endif




    <table class="min-w-full bg-white text-center">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Código</th>
                <th class="py-2 px-4 border-b">Nombre</th>
                <th class="py-2 px-4 border-b">Categoría</th>
                <th class="py-2 px-4 border-b">Sucursal</th>
                <th class="py-2 px-4 border-b">Descripción</th>
                <th class="py-2 px-4 border-b">Cantidad</th>
                <th class="py-2 px-4 border-b">Precio de Venta</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td class="py-2 px-4 border-b">{{ $product['id'] }}</td>
                <td class="py-2 px-4 border-b">{{ $product['code'] }}</td>
                <td class="py-2 px-4 border-b">{{ $product['name'] }}</td>
                <td class="py-2 px-4 border-b">{{ $product['category'] }}</td>
                <td class="py-2 px-4 border-b">{{ $product['branch'] }}</td>
                <td class="py-2 px-4 border-b">{{ $product['description'] }}</td>
                <td class="py-2 px-4 border-b">{{ $product['quantity'] }}</td>
                <td class="py-2 px-4 border-b">{{ $product['sale_price'] }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('products.edit', $product['id']) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs mr-1">
                        Editar
                    </a>

                    <a href="{{ route('products.confirm-delete', $product['id']) }}" class="bg-red-700 hover:bg-red-800 text-white font-bold py-1 px-2 rounded text-xs">
                        Eliminar
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection