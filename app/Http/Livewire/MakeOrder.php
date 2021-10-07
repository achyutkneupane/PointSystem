<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class MakeOrder extends Component
{
    public $products,$active,$total,$totalWithDiscount,$order,$reward,$pointToUse;
    public $listeners = [
        'itemAdded' => 'render',
        'renderSelf' => 'render'
    ];
    public function completeOrder()
    {
        $this->resetErrorBag();
        if($this->pointToUse < 0)
        {
            $this->addError('pointToUse','Use point greater than 0.');
        }
        elseif((int)$this->pointToUse > $this->reward)
        {
            $this->addError('pointToUse','You can\'t use reward point greater than available points.');
        }
        else {
            if($this->pointToUse)
            {
                $this->point = $this->pointToUse;
                auth()->user()->active_points->each(function($active) {
                    if($active->available < $this->point)
                    {
                        $this->point-=$active->available;
                        $active->available = 0;
                        $active->save();
                    }
                    else
                    {
                        $active->available-=$this->point;
                        $active->save();
                        $this->point = 0;
                    }
                });
            }
            $point = floor($this->totalWithDiscount);
            auth()->user()->points()->create([
                'point' => $point,
                'expires_at' => now()->addYear(),
                'available' => $point,
            ]);
            $this->order->completed = true;
            $this->order->received = $this->totalWithDiscount;
            $this->order->save();
        }
        $this->emitSelf('renderSelf');
    }
    public function render()
    {
        $this->products = Product::get();
        $this->active = collect();
        $this->total = 0;
        $this->reward = auth()->user()->reward;
        if(auth()->user()->active_order)
        {
            $this->order = Order::with('products')->find(auth()->user()->active_order->id);
            foreach($this->order->products->groupBy('id') as $item)
            {
                $this->active->push((object)[
                    'product' => $item->first(),
                    'count' => $item->count(),
                ]);
                $this->total+=$item->first()->normal_price*$item->count();
                $this->totalWithDiscount = $this->total-(($this->pointToUse && ($this->pointToUse >= 0 && $this->pointToUse <= $this->reward) ? $this->pointToUse : 0)*0.01);
            }
        }
        return view('livewire.make-order');
    }
}
