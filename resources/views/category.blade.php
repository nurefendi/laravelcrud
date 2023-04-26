<!-- resources/views/categories/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">List Kategori</h1>
        <div class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input id="search" type="text" class="form-control" name="q" placeholder="Cari berdasarkan nama kategori">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Kategori
                    </button>
                </div>
            </div>
        </div>
        <table id="category-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Status Publish</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul id="pagination-links" class="pagination justify-content-end">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item">
                <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="form-tambah">
                
                <div class="mb-3">
                    <label for="inputKategori" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control" id="inputKategori" name="name">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox"  name="is_publish" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Status Publish</label>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button"  class="btn btn-primary btn-simpan-tambah">Simpan</button>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
 @include('categoryscript')
@endsection
