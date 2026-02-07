@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>💰 Gestão de Débitos (Multas)</h1>
    </div>

    {{-- Mensagens de sucesso/erro --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Informações gerais --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">📋 Informações</h5>
            <p class="mb-2"><strong>Total de usuários com débito:</strong> {{ $usersWithDebit->total() }}</p>
            <p class="mb-2"><strong>Valor total em débito:</strong> R$ {{ number_format($usersWithDebit->sum('debit'), 2, ',', '.') }}</p>
            <p class="mb-0"><strong>Prazo de devolução:</strong> 15 dias</p>
            <p class="mb-0"><strong>Multa por dia de atraso:</strong> R$ 0,50</p>
        </div>
    </div>

    @if($usersWithDebit->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Papel</th>
                        <th class="text-end">Débito</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usersWithDebit as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <a href="{{ route('users.show', $user) }}" class="text-decoration-none">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role === 'bibliotecario')
                                    <span class="badge bg-primary">Bibliotecário</span>
                                @else
                                    <span class="badge bg-secondary">Cliente</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong class="text-danger">R$ {{ number_format($user->debit, 2, ',', '.') }}</strong>
                            </td>
                            <td class="text-center">
                                {{-- Botão Ver Empréstimos --}}
                                <a href="{{ route('users.borrowings', $user) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Ver empréstimos">
                                    <i class="bi bi-book"></i> Empréstimos
                                </a>

                                {{-- Botão Quitar Débito --}}
                                <form action="{{ route('debits.clear', $user) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Confirma o pagamento de R$ {{ number_format($user->debit, 2, ',', '.') }} do usuário {{ $user->name }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm" title="Confirmar pagamento">
                                        <i class="bi bi-cash"></i> Quitar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total geral:</strong></td>
                        <td class="text-end">
                            <strong class="text-danger">
                                R$ {{ number_format($usersWithDebit->sum('debit'), 2, ',', '.') }}
                            </strong>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $usersWithDebit->links() }}
        </div>
    @else
        <div class="alert alert-success text-center" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <h4 class="alert-heading mt-2">Nenhum débito pendente!</h4>
            <p class="mb-0">Todos os usuários estão em dia com suas obrigações.</p>
        </div>
    @endif

    {{-- Botão Voltar --}}
    <div class="mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar ao Início
        </a>
    </div>
</div>
@endsection
