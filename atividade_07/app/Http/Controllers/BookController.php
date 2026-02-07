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
    /**
     * Exibe formulário de criação com campos de ID numérico
     */
    public function createWithId()
    {
        return view('books.create-id');
    }

    /**
     * Salva livro a partir do formulário com IDs
     */
    public function storeWithId(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $bookData = $request->only(['title', 'author_id', 'publisher_id', 'category_id']);
        $bookData['cover_image'] = $this->handleImageUpload($request);

        Book::create($bookData);

        return redirect()->route('books.index')
            ->with('success', 'Livro cadastrado com sucesso!');
    }


    /**
     * Exibe formulário de criação com seletores dropdown
     */
    public function createWithSelect()
    {
        $data = [
            'authors' => Author::all(),
            'publishers' => Publisher::all(),
            'categories' => Category::all()
        ];

        return view('books.create-select', $data);
    }

    /**
     * Processa e salva livro do formulário com seletores
     */
    public function storeWithSelect(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $attributes = $request->except('cover_image');
        $attributes['cover_image'] = $this->handleImageUpload($request);

        Book::create($attributes);

        return redirect()->route('books.index')
            ->with('success', 'Livro cadastrado com sucesso!');
    }


    
        public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    /**
     * Atualiza dados do livro existente
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $updateData = $request->only(['title', 'author_id', 'publisher_id', 'category_id']);
        
        if ($request->hasFile('cover_image')) {
            $this->deleteOldImage($book->cover_image);
            $updateData['cover_image'] = $this->handleImageUpload($request);
        }

        $book->update($updateData);

        return redirect()->route('books.show', $book)
            ->with('success', 'Dados do livro atualizados!');
    }



        public function index()
    {
        // Carregar os livros com autores usando eager loading e paginação
        $books = Book::with('author')->paginate(20);

        return view('books.index', compact('books'));

    }

        public function show(Book $book)
    {
        // Carregando autor, editora e categoria do livro com eager loading
        $book->load(['author', 'publisher', 'category']);
        $users = User::all();

        return view('books.show', compact('book','users'));

    }

    /**
     * Remove livro do sistema
     */
    public function destroy(Book $book)
    {
        $this->deleteOldImage($book->cover_image);
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Livro removido com sucesso!');
    }

    /**
     * Faz upload da imagem de capa e retorna o caminho
     */
    private function handleImageUpload(Request $request): ?string
    {
        if (!$request->hasFile('cover_image')) {
            return null;
        }

        $uploadedFile = $request->file('cover_image');
        return $uploadedFile->store('books', 'public');
    }

    /**
     * Remove imagem antiga do storage se existir
     */
    private function deleteOldImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
