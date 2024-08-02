<?php

namespace App\Livewire;

use App\Models\Category as CategoryModel;
use Livewire\Component;

class Category extends Component
{
    public $nama;
    public $isModal = false;
    public $isEdit = false;
    public $kategori_id = null;
    public $kategori_nama = '';
    public $isDelete = false;

    public function render()
    {
        $category = CategoryModel::all();
        return view('livewire.category', compact('category'))->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInput();
        $this->dispatch('open-modal');
        $this->isEdit = false;
    }

    public function store()
    {
        $this->dispatch('close-modal');
        $data = $this->validate(
            [
                'nama' => 'required|unique:categories,nama',
            ],
            [
                'nama.required' => 'Nama kategori tidak boleh kosong',
                'nama.unique' => 'Nama kategori sudah ada',
            ]
        );

        CategoryModel::create($data);
        $this->resetInput();
        session()->flash('success', 'Kategori ' . $data['nama'] .  ' berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->resetInput();
        $kategori = CategoryModel::find($id);
        $this->nama = $kategori->nama;
        $this->isEdit = true;
        $this->kategori_id = $id;
        $this->dispatch('open-modal');
    }

    public function update()
    {
        // dd($id);
        $this->dispatch('close-modal');
        $data = $this->validate(
            [
                'nama' => 'required|unique:categories,nama,' . $this->kategori_id,
            ],
            [
                'nama.required' => 'Nama kategori tidak boleh kosong',
                'nama.unique' => 'Nama kategori sudah ada',
            ]
        );

        $category =  CategoryModel::find($this->kategori_id);
        $category->update($data);
        $this->resetInput();
        session()->flash('success', 'Kategori ' . $data['nama'] .  ' berhasil diupdate');
    }



    public function delete($id)
    {
        $this->kategori_id = $id;
        $this->kategori_nama = CategoryModel::find($id)->nama;
        $this->dispatch('open-modal-delete');
    }

    public function destroy()
    {
        $category = CategoryModel::find($this->kategori_id);
        $category->delete();
        $this->dispatch('close-modal-delete');
        $this->resetInput();
        session()->flash('delete', 'Kategori ' . $category->nama . ' berhasil dihapus');
    }

    public function resetInput()
    {
        $this->reset('nama');
        $this->kategori_id = null;
    }
}
