<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product as ProdukModel;
use App\Models\Category as CategoryModel;
use Livewire\WithFileUploads;

class Produk extends Component
{
    use WithFileUploads;


    public $produk_id, $code, $name, $price, $image, $category_id, $category_list, $current_image, $category_name, $product_name;
    public $isEdit = false;

    public function render()
    {
        $product = ProdukModel::with('category')->get();
        return view('livewire.produk', compact('product'))->layout('layouts.app');
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
}
