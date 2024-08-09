<?php

namespace App\Livewire;

use Midtrans\Snap;
use Midtrans\Config;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Product as ProdukModel;
use Illuminate\Support\Facades\Request;
use App\Models\Category as CategoryModel;

class Product extends Component
{
    use WithFileUploads;


    public $produk_id, $code, $name, $price, $image, $category_id, $category_list, $current_image, $category_name, $product_name, $total;

    public $isEdit = false;
    public $cart = [];

    // midtrans
    public $snapToken;

    public function mount()
    {
        $this->cart = session()->get('cart') ?? [];
        $this->getTotal();
    }

    public function render()
    {
        $product = ProdukModel::with('category')->get();

        // dd($this->cart);
        // dd(Auth::user()->role);

        if (Auth::user()->role == 'admin') {
            return view('livewire.admin.produk', compact('product'))->layout('layouts.admin');
        } else {
            return view('livewire.user.produk', compact('product'))->layout('layouts.user');
        }
    }

    public function create()
    {
        $this->resetInput();
        $this->getCategoryList();
        $this->isEdit = false;
        $this->dispatch('open-modal');
    }

    public function store()
    {
        $this->dispatch('close-modal');
        $data = $this->validate(
            [
                'code' => 'required|unique:products,code',
                'name' => 'required',
                'price' => 'required|numeric',
                'image' => 'required|image|max:2048',
                'category_id' => 'required',
            ],
            [
                'code.required' => 'Kode produk tidak boleh kosong',
                'code.unique' => 'Kode produk sudah ada',
                'name.required' => 'Nama produk tidak boleh kosong',
                'price.required' => 'Harga produk tidak boleh kosong',
                'price.numeric' => 'Harga produk harus berupa angka',
                'image.required' => 'Gambar produk tidak boleh kosong',
                'image.image' => 'Gambar produk harus berupa gambar',
                'image.max' => 'Gambar produk maksimal 2MB',
                'category_id.required' => 'Kategori produk tidak boleh kosong',
            ]
        );

        // sisipkan is_ready
        $data['is_ready'] = true;

        // file name
        $fileName = $data['code'] . '_' . $data['name'] . '.' . $this->image->extension();
        $this->image->storeAs('products', $fileName, 'public');
        $data['image'] = $fileName;

        // dd($data);
        ProdukModel::create($data);
        $this->resetInput();
        session()->flash('success', 'Produk ' . $data['name'] .  ' berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->resetInput();
        $this->getCategoryList();
        $produk = ProdukModel::find($id);
        $this->code = $produk->code;
        $this->name = $produk->name;
        $this->price = $produk->price;
        $this->category_id = $produk->category_id;
        $this->category_name = $produk->category->nama;
        $this->current_image = $produk->image;
        $this->isEdit = true;
        $this->produk_id = $id;
        $this->dispatch('open-modal');
    }

    public function update()
    {
        $this->dispatch('close-modal');
        $data = $this->validate(
            [
                'code' => 'required|unique:products,code,' . $this->produk_id,
                'name' => 'required',
                'price' => 'required|numeric',
                'image' => 'nullable|image|max:2048',
                'category_id' => 'required',
            ],
            [
                'code.required' => 'Kode produk tidak boleh kosong',
                'code.unique' => 'Kode produk sudah ada',
                'name.required' => 'Nama produk tidak boleh kosong',
                'price.required' => 'Harga produk tidak boleh kosong',
                'price.numeric' => 'Harga produk harus berupa angka',
                'image.image' => 'Gambar produk harus berupa gambar',
                'category_id.required' => 'Kategori produk tidak boleh kosong',
            ]
        );

        // file name
        if ($this->image) {
            $fileName = $data['code'] . '_' . $data['name'] . '.' . $this->image->extension();
            $this->image->storeAs('products', $fileName, 'public');
            $data['image'] = $fileName;
        } else {
            $data['image'] = $this->current_image;
        }

        // dd($data);
        $produk =  ProdukModel::find($this->produk_id);
        $produk->update($data);
        $this->resetInput();
        session()->flash('success', 'Produk ' . $data['name'] .  ' berhasil diupdate');
    }

    public function resetInput()
    {
        $this->code = '';
        $this->name = '';
        $this->price = '';
        $this->current_image = '';
        $this->category_id = '';
        $this->category_list = [];
        $this->produk_id = '';
        $this->image = '';
    }

    public function delete($id)
    {
        $this->produk_id = $id;
        $this->product_name = ProdukModel::find($id)->name;
        $this->dispatch('open-modal-delete');
    }

    public function destroy()
    {
        $produk = ProdukModel::find($this->produk_id);
        $produk->delete();
        $this->resetInput();
        $this->dispatch('close-modal-delete');
        session()->flash('delete', 'Produk ' . $this->product_name .  ' berhasil dihapus');
    }

    public function getCategoryList()
    {
        $kategori = CategoryModel::select('id', 'nama')->get();
        foreach ($kategori as $item) {
            $this->category_list[] = [
                'id' => $item->id,
                'nama' => $item->nama,
            ];
        }
    }

    public function addToCart($id)
    {
        $produk = ProdukModel::find($id);

        if ($produk) {

            $this->cart = session()->get('cart') ?? [];

            // jika produk sama
            foreach ($this->cart as $key => $item) {
                if ($item['id'] == $produk->id) {
                    $this->cart[$key]['qty'] += 1;
                    $this->cart[$key]['subTotal'] = $this->cart[$key]['qty'] * $this->cart[$key]['price'];

                    session()->put('cart', $this->cart);
                    session()->flash('success', 'Produk ' . $produk->name .  ' berhasil ditambahkan ke keranjang');
                    $this->getTotal();
                    return;
                }
            }

            // tambahkan produk ke cart
            $this->cart[] = [
                'id' => $produk->id,
                'code' => $produk->code,
                'name' => $produk->name,
                'price' => $produk->price,
                'qty' => 1,
                'subTotal' => $produk->price,
            ];

            session()->put('cart', $this->cart);
            session()->flash('success', 'Produk ' . $produk->name .  ' berhasil ditambahkan ke keranjang');
        } else {
            session()->flash('error', 'Produk tidak ditemukan');
        }

        $this->getTotal();
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->cart = [];
        $this->getTotal();
    }
    public function getTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['subTotal'];
        }
    }

    public function createSnapToken()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        $cart = session()->get('cart');

        // new cart for mitrans (format)
        $itemDetail = [];
        foreach ($cart as $c) {
            $itemDetail[] = [
                'id' => $c['id'],
                'price' => $c['price'],
                'quantity' => $c['qty'],
                'name' => $c['name'],
            ];
        }


        // params   
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $this->total, // Total amount
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => $itemDetail,
        ];

        // dd($params);

        $this->snapToken = Snap::getSnapToken($params);
        $this->clearCart();

        if ($this->snapToken) {
            $this->dispatch('payment', ['token' => $this->snapToken]);
        } else {
            session()->flash('error', 'Token tidak ditemukan');
        }
    }
}
