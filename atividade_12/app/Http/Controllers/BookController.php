<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Book::class, 'book');
    }

    private function handleImageUpload(Request $request): ?string
    {
        if (!$request->hasFile('cover_image')) {
            return null;
        }
        return $request->file('cover_image')->store('books', 'public');
    }

    private function deleteOldImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    // Formulário com input de ID
    public function createWithId()
    {
        $this->authorize('create', Book::class);
        return view('books.create-id');
    }

    public function storeWithId(Request $request)
    {
        $this->authorize('create', Book::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'publisher_id', 'author_id', 'category_id']);
        $data['cover_image'] = $this->handleImageUpload($request);

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    // Formulário com input select
    public function createWithSelect()
    {
        $this->authorize('create', Book::class);

        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    // Salvar livro com input select
    public function storeWithSelect(Request $request)
    {
        $this->authorize('create', Book::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'publisher_id', 'author_id', 'category_id']);
        $data['cover_image'] = $this->handleImageUpload($request);

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = $request->only(['title', 'publisher_id', 'author_id', 'category_id']);
        
        if ($request->hasFile('cover_image')) {
            $this->deleteOldImage($book->cover_image);
            $data['cover_image'] = $this->handleImageUpload($request);
        }

        $book->update($data);

        return redirect()->route('books.show', $book)
                        ->with('success', 'Livro atualizado com sucesso.');
    }

    public function index()
    {
        $books = Book::with('author')->paginate(20);

        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        $book->load(['author', 'publisher', 'category']);
        $users = User::all();

        return view('books.show', compact('book','users'));
    }

    public function destroy(Book $book)
    {
        // A imagem será deletada automaticamente pelo evento no boot() do modelo
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro excluído com sucesso.');
    }
}
