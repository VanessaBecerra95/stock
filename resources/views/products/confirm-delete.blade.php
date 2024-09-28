<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página para eliminar un producto existente en el sistema.">
    <title>Confirmar Eliminación de Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-5">Confirmar Eliminación de Producto</h1>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <p class="mb-4">¿Está seguro que desea eliminar el siguiente producto?</p>

            <div class="mb-4">
                <strong>ID:</strong> {{ $product['id'] }}
            </div>
            <div class="mb-4">
                <strong>Código:</strong> {{ $product['code'] }}
            </div>
            <div class="mb-4">
                <strong>Nombre:</strong> {{ $product['name'] }}
            </div>

            <form action="{{ route('products.destroy', $product['id']) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Eliminar Producto
                </button>
            </form>

            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                Cancelar
            </a>
        </div>
    </div>
</body>

</html>
