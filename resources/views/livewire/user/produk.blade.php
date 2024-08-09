<div>
    <h3>Produk</h3>
    <div class="container-fluid">

        {{-- notif --}}
        @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="row">
            <div class="col-9">
                <div class="row justify-content-md-center">
                    @foreach($product as $p)
                    <div class="col-md-4 p-2">
                        <div class="card p-4 h-100" wire:click="addToCart({{ $p->id }})">
                            {{-- image --}}
                            <div class="h-75 mb-2">
                                <img src="{{ asset('storage/products/'.$p->image) }}" class="img-fluid h-100 rounded"
                                    alt="">
                            </div>
                            {{-- nama produk --}}
                            <div>
                                <h6 class="m-0"> {{ $p->name }}</h6>
                                <span>{{ $p->price }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-3">
                <div class="p-2">
                    <div class="d-flex mb-3 justify-content-between">
                        <h4>Cart</h4>
                        <button wire:click="clearCart" class="btn btn-sm btn-danger">Clear</button>
                    </div>
                    @foreach ($cart as $c)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>{{ $c['name'] }}</h6>
                            <span>{{ $c['price'] }}</span>
                        </div>
                        <div>
                            <span>{{ $c['subTotal'] }}</span>
                            <span class="bg-success rounded px-1 text-white">{{ $c['qty'] }}</span>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                    <div>
                        <div class="d-flex flex-col justify-content-between align-items-center">
                            <h6>Total</h6>
                            <strong>{{ $total }}</strong>
                        </div>
                        {{-- checkout --}}
                        <div>
                            @if($cart)
                            <button wire:click="createSnapToken" id="btn-chechout" type="button"
                                class="btn btn-primary w-100">Checkout</button>
                            @endif  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<script>
    window.addEventListener('payment', (token) => {
        if (token) {
            // console.log(event.detail[0]['token']);
            window.snap.pay(event.detail[0]['token']);
        } else {
            console.log('error');
        }
    });

</script>
@endsection