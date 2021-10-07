<div>
    <div class='container'>
        <div class='row'>
            <div class='col-sm-9'>
                <strong class='h5'>{{ $product->name }}</strong>
                <br>
                Price: <b>{{ $product->normal_price }}</b>
            </div>
            <div class='col-sm-3'>
                <button class='btn btn-success w-100' wire:click='addToOrder'>Add</button>
            </div>
        </div>
    </div>
</div>
