<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página mostrar los registros de un producto existente en el sistema.">
    <title>Resultados de la Búsqueda</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl text-center font-bold mb-5">Resultados de la Búsqueda</h1>

        @if ($message)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ $message }}
        </div>
        @endif

        @if (count($products) === 0 && !$message)
        <p class="text-center text-gray-700">No se encontraron productos que coincidan con los criterios de búsqueda.</p>
        @else
        <table class="min-w-full bg-white text-center">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Código</th>
                    <th class="py-2 px-4 border-b">Nombre</th>
                    <th class="py-2 px-4 border-b">Categoría</th>
                    @if(!empty($searchParams['branch']))
                    <th class="py-2 px-4 border-b">Sucursal</th>
                    @endif
                    <th class="py-2 px-4 border-b">Descripción</th>
                    <th class="py-2 px-4 border-b">Cantidad</th>
                    <th class="py-2 px-4 border-b">Precio de venta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $product['id'] }}</td>
                    <td class="py-2 px-4 border-b">{{ $product['code'] }}</td>
                    <td class="py-2 px-4 border-b">{{ $product['name'] }}</td>
                    <td class="py-2 px-4 border-b">{{ $product['category'] }}</td>
                    @if(!empty($searchParams['branch']))
                    <td class="py-2 px-4 border-b">{{ $product['branch'] }}</td>
                    @endif
                    <td class="py-2 px-4 border-b">{{ $product['description'] }}</td>
                    <td class="py-2 px-4 border-b">{{ $product['quantity'] }}</td>
                    <td class="py-2 px-4 border-b">{{ $product['sale_price'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</body>

</html>
