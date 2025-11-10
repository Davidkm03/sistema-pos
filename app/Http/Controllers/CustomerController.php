<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Store a newly created customer (for AJAX requests)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'tax_id_type' => 'nullable|string|in:CC,NIT,CE,PAS',
            'tax_id' => 'nullable|string|max:50',
        ]);

        try {
            // Agregar empresa_id del usuario autenticado
            $validated['empresa_id'] = Auth::user()->empresa_id;
            
            $customer = Customer::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'customer' => $customer,
                    'message' => 'Cliente creado exitosamente'
                ]);
            }

            return redirect()->back()->with('success', 'Cliente creado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear cliente: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear cliente');
        }
    }
}
