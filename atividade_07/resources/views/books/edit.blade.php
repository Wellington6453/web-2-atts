@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Editar Informações do Livro</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Coluna Esquerda -->
                            <div class="col-md-6">
                                <!-- Título -->
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">Título</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $book->title) }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Autor -->
                                <div class="mb-3">
                                    <label for="author_id" class="form-label fw-bold">Autor</label>
                                    <select class="form-select @error('author_id') is-invalid @enderror" 
                                            id="author_id" 
                                            name="author_id" 
                                            required>
                                        <option value="">-- Selecione --</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" 
                                                {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('author_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Coluna Direita -->
                            <div class="col-md-6">
                                <!-- Editora -->
                                <div class="mb-3">
                                    <label for="publisher_id" class="form-label fw-bold">Editora</label>
                                    <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                            id="publisher_id" 
                                            name="publisher_id" 
                                            required>
                                        <option value="">-- Selecione --</option>
                                        @foreach($publishers as $publisher)
                                            <option value="{{ $publisher->id }}" 
                                                {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                                {{ $publisher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('publisher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Categoria -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label fw-bold">Categoria</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id" 
                                            required>
                                        <option value="">-- Selecione --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Upload de Nova Capa -->
                        <hr class="my-4">
                        <div class="mb-3">
                            <label for="cover_image" class="form-label fw-bold">
                                Atualizar Imagem de Capa <span class="text-muted">(Opcional)</span>
                            </label>
                            
                            @if($book->cover_image)
                                <div class="mb-2">
                                    <p class="text-muted small mb-1">Capa atual:</p>
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                         alt="Capa atual" 
                                         class="img-thumbnail" 
                                         style="max-width: 150px;">
                                </div>
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('cover_image') is-invalid @enderror" 
                                   name="cover_image" 
                                   id="cover_image"
                                   accept="image/*">
                            <small class="form-text text-muted">
                                Envie uma nova imagem para substituir a capa atual (JPG, PNG, GIF, WEBP - max 2MB)
                            </small>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection