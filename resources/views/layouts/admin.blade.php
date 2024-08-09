<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS-APP</title>
    @livewireStyles
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- font awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- DataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" />
</head>

<body>

    {{-- modal hapus --}}
    <div wire:ignore.self class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalDeleteLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <p>Anda Ingin Logout ?</p>
                        <div>
                            <a href="/logout" class="btn btn-danger mx-2">Ya</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- sidebar --}}
    <div class="sidebar">
        <div class="w-100 text-center mt-2">
            <h3>{{ config('app.name') }}</h3>
        </div>
        <nav class="nav mt-4 flex-column">
            <a class="{{ Request::is('admin/kategori') ? 'nav-link-active' : '' }} nav-link"
                href="/admin/kategori"><span>Kategori</span></a>
            <a class="{{ Request::is('admin/produk') ? 'nav-link-active' : '' }} nav-link"
                href="/admin/produk"><span>Produk</span></a>
            <button type="button" class="d-flex btn btn-logout" data-bs-toggle="modal" data-bs-target="#modalLogout">
                <span>Logout</span>
            </button>
        </nav>
    </div>

    <div class="container mt-4" style="padding: 0px 40px;">
        {{ $slot }}
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

</body>

</html>