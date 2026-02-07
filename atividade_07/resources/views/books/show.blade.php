@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Informações do Livro -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">{{ $book->title }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Coluna da Imagem -->
                        <div class="col-md-4">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}"
                                     alt="Capa: {{ $book->title }}"
                                     class="img-fluid rounded shadow-sm mb-3"
                                     style="max-height: 280px; width: 100%; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 280px;">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-book" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Sem capa</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Coluna das Informações -->
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th style="width: 120px;">Autor:</th>
                                        <td>
                                            <a href="{{ route('authors.show', $book->author->id) }}" 
                                               class="text-decoration-none">
                                                {{ $book->author->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Editora:</th>
                                        <td>
                                            <a href="{{ route('publishers.show', $book->publisher->id) }}" 
                                               class="text-decoration-none">
                                                {{ $book->publisher->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Categoria:</th>
                                        <td>
                                            <a href="{{ route('categories.show', $book->category->id) }}" 
                                               class="text-decoration-none">
                                                {{ $book->category->name }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-3">
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="{{ route('books.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histórico de Empréstimos -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Histórico de Empréstimos</h5>
                </div>
                <div class="card-body">
                    @if($book->users->isEmpty())
                        <p class="text-muted">Nenhum empréstimo registrado para este livro.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Empréstimo</th>
                                        <th>Devolução</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($book->users as $user)
                                        <tr>
                                            <td>
                                                <a href="{{ route('users.show', $user->id) }}">
                                                    {{ $user->name }}
                                                </a>
                                            </td>
                                            <td><small>{{ $user->pivot->borrowed_at }}</small></td>
                                            <td>
                                                @if($user->pivot->returned_at)
                                                    <small>{{ $user->pivot->returned_at }}</small>
                                                @else
                                                    <span class="badge bg-warning">Pendente</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$user->pivot->returned_at)
                                                    <form action="{{ route('borrowings.return', $user->pivot->id) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-sm btn-success" 
                                                                type="submit"
                                                                onclick="return confirm('Confirmar devolução?')">
                                                            <i class="bi bi-check-circle"></i> Devolver
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Formulário de Empréstimo -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Novo Empréstimo</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.borrow', $book) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-bold">Selecione o Usuário</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">-- Escolha um usuário --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-circle"></i> Registrar Empréstimo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
