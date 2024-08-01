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
                    <form wire:submit.prevent="update">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" wire:model="nama" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                    @else
                    <form wire:submit.prevent="store">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" wire:model="nama" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
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
                        <p>Apakah anda yakin ingin menghapus <strong>{{ $kategori_nama }}</strong>?</p>
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
    <div class=" container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Category</h1>
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
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th class="d-flex justify-content-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategori as $k)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $k->nama }}</td>
                        <td class="d-flex justify-content-center gap-2">
                            <button wire:click="edit({{ $k->id }})" class="btn btn-sm btn-warning"><i
                                    class="fa-solid fa-pencil"></i></button>
                            <button wire:click="delete({{ $k->id }})" class="btn btn-sm btn-danger"><i
                                    class="fa-solid fa-trash-can"></i></button>
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