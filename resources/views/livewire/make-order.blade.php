<div>
    <div class="card">
        <div class="card-header">
          Make Order
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($products as $product)
                <li class="list-group-item">
                    @livewire('components.product-component', ['product' => $product], key($product->id))
                </li>
                @endforeach
            </ul>
            @if(auth()->user()->active_order)
                <div class='pt-3'>
                    <h3>
                        Active Order
                    </h3>
                    <div class="list-group">
                        @foreach($active as $item)
                            <div class='d-flex flex-row justify-content-between list-group-item'>
                                <div>
                                    <strong class='h5'>{{ $item->product->name }}</strong>
                                    <span class='text-muted'>x{{ $item->count }}</span>
                                </div>
                                <div>
                                    USD <b>{{ $item->product->normal_price*$item->count }}</b>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class='d-flex flex-row justify-content-between mt-3'>
                <div>
                    <h5 class='text-muted'>
                        Available Points: {{ auth()->user()->reward }}
                    </h5>
                    @if(auth()->user()->active_order)
                    <h5 class='text-muted'>
                        <div class='form-group'>
                            <label for='pointToUse'>Use Points:</label>
                            <input type='number' id='pointToUse' class='form-control' placeholder='Enter Points to use' wire:model='pointToUse' value='{{ $reward }}'>
                            @error('pointToUse')
                            <div class='text-danger'>{{ $message }}</div>
                            @enderror
                        </div>
                    </h5>
                    @endif
                </div>
                @if(auth()->user()->active_order)
                <div>
                    <div class='h5 text-right'>
                        @if(!$pointToUse)
                        USD <b>{{ $total }}</b>
                        @else
                        USD <del class='text-danger text-decoration-line-through'>{{ $total }}</del><br>
                        <span class='text-muted'>After Point discount:</span> USD {{ $totalWithDiscount }}
                        @endif
                    </div>
                    <div class='text-right'>
                        <button class='btn btn-warning' wire:click='completeOrder'>
                            Complete Order
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
      </div>
</div>