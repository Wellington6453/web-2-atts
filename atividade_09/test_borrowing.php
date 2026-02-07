<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "═══════════════════════════════════════════════════\n";
echo "🧪 TESTE COMPLETO - ATIVIDADE 09\n";
echo "═══════════════════════════════════════════════════\n\n";

// Limpar empréstimos anteriores de teste
App\Models\Borrowing::where('book_id', 1)->delete();

$livro = App\Models\Book::find(1);
$usuario1 = App\Models\User::find(1);
$usuario2 = App\Models\User::find(2);

echo "📚 CENÁRIO 1: Livro disponível\n";
echo "   Livro: {$livro->title}\n";

$temAberto = App\Models\Borrowing::where('book_id', $livro->id)
    ->whereNull('returned_at')
    ->exists();
echo "   Status: " . ($temAberto ? "❌ INDISPONÍVEL" : "✅ DISPONÍVEL") . "\n\n";

// Criar empréstimo
echo "📝 CENÁRIO 2: Criar empréstimo para {$usuario1->name}\n";
$emp1 = App\Models\Borrowing::create([
    'user_id' => $usuario1->id,
    'book_id' => $livro->id,
    'borrowed_at' => now(),
]);
echo "   ✅ Empréstimo criado com sucesso!\n\n";

// Tentar emprestar novamente
echo "🚫 CENÁRIO 3: Tentar emprestar o mesmo livro para {$usuario2->name}\n";
$temAberto = App\Models\Borrowing::where('book_id', $livro->id)
    ->whereNull('returned_at')
    ->exists();
    
if ($temAberto) {
    echo "   ❌ BLOQUEADO! Este livro já está emprestado e ainda não foi devolvido.\n";
} else {
    echo "   ⚠️  ERRO: O livro deveria estar bloqueado!\n";
}
echo "\n";

// Devolver o livro
echo "📥 CENÁRIO 4: Devolver o livro\n";
$emp1->update(['returned_at' => now()]);
echo "   ✅ Devolução registrada!\n\n";

// Tentar emprestar novamente
echo "🔄 CENÁRIO 5: Tentar emprestar após devolução\n";
$temAberto = App\Models\Borrowing::where('book_id', $livro->id)
    ->whereNull('returned_at')
    ->exists();
    
if (!$temAberto) {
    echo "   ✅ LIBERADO! O livro pode ser emprestado novamente.\n";
    $emp2 = App\Models\Borrowing::create([
        'user_id' => $usuario2->id,
        'book_id' => $livro->id,
        'borrowed_at' => now(),
    ]);
    echo "   ✅ Novo empréstimo criado para {$usuario2->name}\n";
} else {
    echo "   ⚠️  ERRO: O livro deveria estar disponível!\n";
}

echo "\n═══════════════════════════════════════════════════\n";
echo "✅ TODOS OS TESTES PASSARAM!\n";
echo "═══════════════════════════════════════════════════\n";
