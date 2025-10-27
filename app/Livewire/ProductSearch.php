<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductSearch extends Component
{
    public string $search = '';
    public array $results = [];

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->results = Product::where(function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('sku', 'like', '%' . $this->search . '%');
                })
                ->with('category')
                ->where('stock', '>', 0)
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->results = [];
        }
    }

    public function selectProduct($productId)
    {
        $product = Product::find($productId);
        
        if ($product) {
            $this->dispatch('productSelected', [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'stock' => $product->stock,
                'sku' => $product->sku,
            ]);
            $this->search = '';
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.product-search');
    }
}
