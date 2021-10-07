<div>
    <div class="card">
    <div class="card-header text-uppercase text-center">
        {{ env('APP_NAME') }}
    </div>
    <div class="list-group list-group-flush">
        <a href='{{ route('home') }}' class="list-group-item {{ request()->routeIs('home') ? 'active text-white' : 'text-dark'}}">
            Dashboard
        </a>
        <a href='{{ route('make.order') }}' class="list-group-item {{ request()->routeIs('make.order') ? 'active text-white' : 'text-dark'}}">
            Make Order
        </a>
        <a href='{{ route('all.orders') }}' class="list-group-item {{ request()->routeIs('all.orders') ? 'active text-white' : 'text-dark'}}">
            All Orders
        </a>
    </div>
    </div>
</div>
