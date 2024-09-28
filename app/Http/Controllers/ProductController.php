<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $branchOptions = ['Sucursal A', 'Sucursal B', 'Sucursal C'];
    const NOT_FOUND = "el producto no ha sido encontrado";
    const VALIDATION_RULE_REQUIRED_FILLED = 'required|filled';
    const VALIDATION_RULL_NULLABLE_STRING = 'nullable|string';

    //Retorno de vista del form del producto
    public function index()
    {
        $products = session('products', []);
        return view('products.index', compact('products'));
    }

    //Procesar los datos de entregada y validaciones
    public function create(Request $request)
    {
        return view('products.create', ['branchOptions' => $this->branchOptions]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $products = session('products', []);
                    $idExists = collect($products)->pluck('id')->contains($value);
                    if ($idExists) {
                        $fail('El ID ya está en uso.');
                    }
                },
            ],
            'code' => [
                'required',
                'regex:/\S+/', // No acepta solo espacios
                function ($attribute, $value, $fail) {
                    $products = session('products', []);
                    $codeExists = collect($products)->pluck('code')->contains($value);
                    if ($codeExists) {
                        $fail('El código ya está en uso.');
                    }
                },
            ],
            'name' => self::VALIDATION_RULE_REQUIRED_FILLED,
            'category' => self::VALIDATION_RULE_REQUIRED_FILLED,
            'branch' => self::VALIDATION_RULE_REQUIRED_FILLED,
            'quantity' => 'required|numeric|min:1',
            'sale_price' => 'required|numeric|min:1',
        ]);

        $products = session('products', []);
        $newProduct = $request->all();
        $products[] = $newProduct;

        session(['products' => $products]);

        return redirect()->route('products.index')->with('success', 'Producto ingresado correctamente.');
    }

    // Mostrar formulario de cambio de sucursal
    public function formBranch()
    {
        return view('products.update-branch', ['branchOptions' => $this->branchOptions]);
    }

    // Procesar el cambio de sucursal
    public function updateBranch(Request $request)
    {
        $products = session('products', []);

        $request->validate([
            'name' => [
                'required',
                'filled',
                function ($attribute, $value, $fail) use ($products) {
                    $productExists = collect($products)->pluck('name')->map('strtolower')->contains(strtolower($value));
                    if (!$productExists) {
                        $fail('El producto no existe, como primer paso deberá registrarlo');
                    }
                }
            ],
            'branch' => 'required'
        ]);


        foreach ($products as &$product) {
            if (strtolower($product['name']) === strtolower($request->input('name'))) {
                $product['branch'] = $request->input('branch');
                break;
            }
        }

        session(['products' => $products]);

        return redirect()->route('products.index')->with('success', 'Sucursal actualizada correctamente.');
    }
    //búsqueda
    public function search(Request $request)
    {

        $request->validate([
            'code' => self::VALIDATION_RULL_NULLABLE_STRING,
            'name' => self::VALIDATION_RULL_NULLABLE_STRING,
            'branch' => self::VALIDATION_RULL_NULLABLE_STRING,
        ]);


        $code = $request->input('code');
        $name = $request->input('name');
        $branch = $request->input('branch');


        if (empty($code) && empty($name)) {
            return view('products.search-results', [
                'products' => [],
                'searchParams' => $request->all(),
                'message' => 'Falta añadir los campos requeridos: código y nombre.',
            ]);
        }

        $missingFields = [];
        if (empty($code)) {
            $missingFields[] = 'código';
        }
        if (empty($name)) {
            $missingFields[] = 'nombre';
        }

        if (!empty($missingFields)) {
            return view('products.search-results', [
                'products' => [],
                'searchParams' => $request->all(),
                'message' => 'Falta añadir el campo requerido: ' . implode(' y ', $missingFields) . '.',
            ]);
        }

        $products = session('products', []);

        $filteredProducts = collect($products)->filter(function ($product) use ($code, $name, $branch) {
            $matchesCode = stripos($product['code'], $code) !== false;
            $matchesName = stripos($product['name'], $name) !== false;
            $matchesBranch = !$branch || $product['branch'] === $branch;

            return $matchesCode && $matchesName && $matchesBranch;
        });

        if ($filteredProducts->isEmpty()) {
            return view('products.search-results', [
                'products' => [],
                'searchParams' => $request->all(),
                'message' => 'El producto no se encuentra, primero regístrelo.',
            ]);
        }

        return view('products.search-results', [
            'products' => $filteredProducts,
            'searchParams' => $request->all(),
            'message' => null,
        ]);
    }
    //confirmar eliminación de producto
    public function confirmDelete($id)
    {
        $products = session('products', []);
        $product = collect($products)->firstWhere('id', $id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', self::NOT_FOUND);
        }

        return view('products.confirm-delete', compact('product'));
    }
    //eliminar producto
    public function destroy($id)
    {
        $products = session('products', []);
        $updatedProducts = collect($products)->reject(function ($product) use ($id) {
            return $product['id'] == $id;
        })->values()->all();

        if (count($products) == count($updatedProducts)) {
            return redirect()->route('products.index')->with('error',  self::NOT_FOUND);
        }

        session(['products' => $updatedProducts]);

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function edit($id)
    {
        $products = session('products', []);
        $product = collect($products)->firstWhere('id', $id);

        if (!$product) {
            return redirect()->route('products.index')->with('error',  self::NOT_FOUND);
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|filled',
            'description' => 'required|filled',
            'sale_price' => 'required|numeric|min:0',
        ]);

        $products = session('products', []);
        $productIndex = collect($products)->search(function ($product) use ($id) {
            return $product['id'] == $id;
        });

        if ($productIndex === false) {
            return redirect()->route('products.index')->with('error',  self::NOT_FOUND);
        }

        $products[$productIndex]['name'] = $request->input('name');
        $products[$productIndex]['description'] = $request->input('description');
        $products[$productIndex]['sale_price'] = $request->input('sale_price');

        session(['products' => $products]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }
}
