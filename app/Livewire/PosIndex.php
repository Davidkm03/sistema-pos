<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;

class PosIndex extends Component
{
    public $selectedCategory = null;

    public function selectCategory($categoryId = null)
    {
        $this->selectedCategory = $categoryId;
    }

    public function addToCart($productData)
    {
        $this->dispatch('productSelected', $productData);
    }

    public function render()
    {
        $categories = Category::withCount(['products' => function($query) { 
            $query->where('stock', '>', 0); 
        }])->get();

        $products = Product::where('stock', '>', 0)
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->with('category')
            ->orderBy('name')
            ->take(12)
            ->get();

        $totalProducts = Product::where('stock', '>', 0)
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->count();

        return view('livewire.pos-index', compact('categories', 'products', 'totalProducts'));
    }
}
