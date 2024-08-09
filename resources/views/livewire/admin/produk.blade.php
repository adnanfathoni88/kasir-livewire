<div>

    {{-- modal --}}
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    @if($isEdit == true)
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Form Edit</h1>
                    @else
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah</h1>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($isEdit == true)
                    {{-- edit --}}
                    <form wire:submit.prevent="update">
                        <div class="mb-3">
                            <label for="code" class="form-label">code</label>
                            <input type="text" class="form-control" id="code" wire:model="code" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" wire:model="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="text" inputmode="numeric" class="form-control" id="price" wire:model="price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="image" wire:model="image">
                            <div class="w-100">
                                <p>Preview: </p>
                                @if ($image)
                                <img class="img-fluid" src="{{ $image->temporaryUrl() }}" style="height: 150px">
                                @elseif ($current_image)
                                <img class="img-fluid" src="{{ asset('storage/products/' . $current_image) }}"
                                    style="height: 150px">
                                @endif
                            </div>
                        </div>

                        {{-- select --}}
                        <div class="mb-3">
                            <label for="category_id" class="form-label" required>Kategori</label>
                            <select class="form-select" id="category_id" wire:model="category_id">
                                <option value="">Pilih Kategori</option>
                                @if($category_list)
                                <option value="{{ $category_id }}" selected>{{ $category_name }}</option>
                                @foreach($category_list as $c)
                                <option value="{{ $c['id'] }}">{{ $c['nama'] }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4">Update</button>
                    </form>
                    @else
                    {{-- store --}}
                    <form wire:submit.prevent="store">
                        <div class="mb-3">
                            <label for="code" class="form-label">code</label>
                            <input type="text" class="form-control" id="code" wire:model="code" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" wire:model="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="text" inputmode="numeric" class="form-control" id="price" wire:model="price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="image" wire:model="image" required>
                            <div class="w-100">
                                @if ($image)
                                <p>Preview: </p>
                                <img class="img-fluid" src="{{ $image->temporaryUrl() }}">
                                @endif
                            </div>
                        </div>

                        {{-- select --}}
                        <div class="mb-3">
                            <label for="category_id" class="form-label" required>Kategori</label>
                            <select class="form-select" id="category_id" wire:model="category_id">
                                <option value="">Pilih Kategori</option>
                                @if($category_list)
                                @foreach($category_list as $c)
                                <option value="{{ $c['id'] }}">{{ $c['nama'] }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4">Tambah</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- modal hapus --}}
    <div wire:ignore.self class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <p>Apakah anda yakin ingin menghapus <strong>{{ $product_name}}</strong>?</p>
                        <div>
                            <button wire:click="destroy" class="btn btn-danger">Ya</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- konten --}}
    <div class=" container">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Produk</h1>
            <div>
                <button wire:click="create" type="button" class="btn btn-primary">
                    Tambah
                </button>
            </div>
        </div>
        <div class="mt-4">
            <div>
                @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if (session()->has('delete'))
                <div class="alert alert-danger">
                    {{ session('delete') }}
                </div>
                @endif
            </div>
            <table id="table" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Kategori</th>

                        <th class="d-flex justify-content-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product as $p)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td><img src="{{ asset('storage/products/' . $p->image) }}"
                                style="height: 100px; max-width:200px; object-fit:cover">
                        </td>
                        <td>
                            {{ $p->code }}
                        </td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->price }}</td>
                        <td>{{ $p->category->nama }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" wire:click="edit({{ $p->id }})" class="btn btn-sm btn-warning"><i
                                        class="fa-solid fa-pencil"></i></button>
                                <button type="button" wire:click="delete({{ $p->id }})" class="btn btn-sm btn-danger"><i
                                        class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <script>
        // close modal
        window.addEventListener('close-modal', () => {
            $('#exampleModal').modal('hide');
        });

        // open modal
        window.addEventListener('open-modal', () => {
            $('#exampleModal').modal('show');
        });

        // open modal delete
        window.addEventListener('open-modal-delete', () => {
            $('#modalDelete').modal('show');
        });

        // close modal delete
        window.addEventListener('close-modal-delete', () => {
            $('#modalDelete').modal('hide');
        });
    </script>
</div>