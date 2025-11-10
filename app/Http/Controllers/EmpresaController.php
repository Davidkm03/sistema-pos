<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // El super-admin puede gestionar todas las empresas
        // No aplicamos ningún scope aquí porque Empresa no tiene EmpresaScope
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::withCount(['users', 'products', 'customers', 'sales'])
            ->orderBy('nombre')
            ->paginate(10);

        return view('empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'rfc' => 'nullable|string|max:50',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'sitio_web' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
            'moneda' => 'required|string|max:10',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
            'activo' => 'boolean',
        ]);

        // Manejar la subida del logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $empresa = Empresa::create($validated);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        $empresa->loadCount(['users', 'products', 'customers', 'sales', 'quotes']);
        
        return view('empresas.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'rfc' => 'nullable|string|max:50',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'sitio_web' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
            'moneda' => 'required|string|max:10',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
            'activo' => 'boolean',
        ]);

        // Manejar la subida del logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $empresa->update($validated);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        // Verificar que la empresa no tenga datos relacionados
        if ($empresa->users()->count() > 0 || 
            $empresa->products()->count() > 0 || 
            $empresa->sales()->count() > 0) {
            return redirect()->route('admin.empresas.index')
                ->with('error', 'No se puede eliminar la empresa porque tiene datos relacionados.');
        }

        // Eliminar logo si existe
        if ($empresa->logo) {
            Storage::disk('public')->delete($empresa->logo);
        }

        $empresa->delete();

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa eliminada exitosamente.');
    }
}
