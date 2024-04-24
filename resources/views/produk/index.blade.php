@extends('layouts.main', ['title' => 'Produk'])
@section('title-content')
    <i class="fas fa-box-open mr-2"></i>
    Produk
@endsection
@section('content')
    @if (session('store') == 'success')
    <x-alert type="success">
        <strong>Berhasil diupdate!</strong> Produk berhasil diupdate.
    </x-alert>
    @endif
    @if (session('destroy') == 'success')
    <x-alert type="success">
        <strong>Berhasil dihapus</strong> Produk berhasil dihapus.
    </x-alert>
    @endif
    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('produk.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Tambah
            </a>
            <form action="?" method="get" class="ml-auto">
                <div class="input-group">
                    <input type="tect" class="form-control" name="search" value="<?= request()->search ?>"
                    placeholder="Kode, Nama Produk">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produks as $key => $produk)
                    <tr>
                        <td>{{ $produks->firstItem() + $key }}</td>
                        <td>{{ $produk->kode_produk }}</td>
                        <td>{{ $produk->nama_produk }}</td>
                        <td>{{ $produk->nama_kategori }}</td>
                        <td>{{ $produk->harga }}</td>
                        <td>{{ $produk->stok }}</td>
                        <td class="text-right">
                            <a href="{{ route('produk.edit', [
                                'produk' => $produk->id,
                            ]) }}"
                                class="btn btn-xs text-success p-0 mr-1">
                                <i class="fas fa-edit"></i>
                            </a>

                            <button type="button" data-toggle="modal" data-target="#modalDelete"
                            data-url="{{ route('produk.destroy', [
                                'produk' => $produk->id,
                            ]) }}"
                            class="btn btn-xs text-danger p-0 btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" bgcolor="#808080" ><strong> Produk yang Anda Cari Tidak Dapat Ditemukan.</strong> </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $produks->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@push('modals')
    <x-modal-delete />
@endpush