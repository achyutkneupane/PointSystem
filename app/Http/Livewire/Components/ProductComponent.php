<?php

namespace App\Http\Livewire\Components;

use App\Models\Product;
use Livewire\Component;

class ProductComponent extends Component
{
    public $product;
    public function mount(Product $product)
    {
        $this->product = $product;
    }
    public function addToOrder()
    {
        if(!auth()->user()->active_order)
        {
            $order = auth()->user()->orders()->create([
                'type' => 'normal'
            ]);
            $order->products()->attach($this->product->id);
        }
        else
        auth()->user()->active_order->products()->attach($this->product->id);
        $this->emitUp('itemAdded');
    }
    public function render()
    {
        return view('livewire.components.product-component');
    }
}
