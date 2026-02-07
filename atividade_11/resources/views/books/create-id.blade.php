@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Cadastrar Novo Livro - Modo ID</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('books.store.id') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Campo Título -->
                        <div class="form-group mb-3">
                            <label for="title" class="form-label fw-bold">Título do Livro</label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo Autor ID -->
                        <div class="form-group mb-3">
                            <label for="author_id" class="form-label fw-bold">ID do Autor</label>
                            <input type="number" 
                                   name="author_id" 
                                   id="author_id" 
                                   class="form-control @error('author_id') is-invalid @enderror"
                                   value="{{ old('author_id') }}"
                                   required>
                            @error('author_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo Editora ID -->
                        <div class="form-group mb-3">
                            <label for="publisher_id" class="form-label fw-bold">ID da Editora</label>
                            <input type="number" 
                                   name="publisher_id" 
                                   id="publisher_id" 
                                   class="form-control @error('publisher_id') is-invalid @enderror"
                                   value="{{ old('publisher_id') }}"
                                   required>
                            @error('publisher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo Categoria ID -->
                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label fw-bold">ID da Categoria</label>
                            <input type="number" 
                                   name="category_id" 
                                   id="category_id" 
                                   class="form-control @error('category_id') is-invalid @enderror"
                                   value="{{ old('category_id') }}"
                                   required>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo Imagem de Capa -->
                        <div class="form-group mb-4">
                            <label for="cover_image" class="form-label fw-bold">
                                Imagem de Capa <small class="text-muted">(Opcional)</small>
                            </label>
                            <input type="file" 
                                   name="cover_image" 
                                   id="cover_image" 
                                   class="form-control @error('cover_image') is-invalid @enderror"
                                   accept="image/*">
                            <small class="form-text text-muted">
                                Formatos aceitos: JPG, PNG, GIF, WEBP (máx. 2MB)
                            </small>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botões -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Salvar Livro
                            </button>
                            <a href="{{ route('books.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection