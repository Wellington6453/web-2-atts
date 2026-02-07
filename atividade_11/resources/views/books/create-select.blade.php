@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Cadastrar Novo Livro - Modo Select</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('books.store.select') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Título -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Título do Livro</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Autor -->
                        <div class="mb-3">
                            <label for="author_id" class="form-label fw-bold">Selecione o Autor</label>
                            <select class="form-select @error('author_id') is-invalid @enderror" 
                                    id="author_id" 
                                    name="author_id" 
                                    required>
                                <option value="">-- Escolha um autor --</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('author_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Editora -->
                        <div class="mb-3">
                            <label for="publisher_id" class="form-label fw-bold">Selecione a Editora</label>
                            <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                    id="publisher_id" 
                                    name="publisher_id" 
                                    required>
                                <option value="">-- Escolha uma editora --</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
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
                            <label for="category_id" class="form-label fw-bold">Selecione a Categoria</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">-- Escolha uma categoria --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload de Capa -->
                        <div class="mb-4">
                            <label for="cover_image" class="form-label fw-bold">
                                Imagem de Capa <span class="text-muted">(Opcional)</span>
                            </label>
                            <input type="file" 
                                   class="form-control @error('cover_image') is-invalid @enderror" 
                                   name="cover_image" 
                                   id="cover_image"
                                   accept="image/*">
                            <div class="form-text">Arquivos aceitos: JPG, PNG, GIF, WEBP - Tamanho máximo: 2MB</div>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botões de Ação -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Cadastrar
                            </button>
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection