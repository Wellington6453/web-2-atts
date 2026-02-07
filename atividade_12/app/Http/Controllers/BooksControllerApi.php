<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BooksControllerApi extends Controller
{
    /**
     * Lista todos os livros com paginação e busca opcional
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Book::with(['author', 'category', 'publisher']);

            // Busca por título (opcional)
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('title', 'like', "%{$search}%");
            }

            // Paginação
            $perPage = $request->input('per_page', 15);
            $books = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $books->items(),
                'pagination' => [
                    'total' => $books->total(),
                    'per_page' => $books->perPage(),
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar livros',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria um novo livro com validação
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'published_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
        ], [
            'title.required' => 'O título é obrigatório',
            'title.max' => 'O título não pode exceder 255 caracteres',
            'author_id.required' => 'O autor é obrigatório',
            'author_id.exists' => 'Autor não encontrado',
            'category_id.required' => 'A categoria é obrigatória',
            'category_id.exists' => 'Categoria não encontrada',
            'publisher_id.required' => 'A editora é obrigatória',
            'publisher_id.exists' => 'Editora não encontrada',
            'published_year.required' => 'O ano de publicação é obrigatório',
            'published_year.integer' => 'O ano deve ser um número inteiro',
            'published_year.min' => 'Ano inválido',
            'published_year.max' => 'Ano não pode ser maior que o ano atual',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $book = Book::create($request->all());
            $book->load(['author', 'category', 'publisher']);

            return response()->json([
                'success' => true,
                'message' => 'Livro criado com sucesso',
                'data' => $book
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca um livro específico por ID
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            $book = Book::with(['author', 'category', 'publisher'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $book
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza um livro existente com validação
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $book = Book::findOrFail($id);

            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'author_id' => 'sometimes|required|exists:authors,id',
                'category_id' => 'sometimes|required|exists:categories,id',
                'publisher_id' => 'sometimes|required|exists:publishers,id',
                'published_year' => 'sometimes|required|integer|min:1000|max:' . (date('Y') + 1),
            ], [
                'title.required' => 'O título não pode ser vazio',
                'title.max' => 'O título não pode exceder 255 caracteres',
                'author_id.exists' => 'Autor não encontrado',
                'category_id.exists' => 'Categoria não encontrada',
                'publisher_id.exists' => 'Editora não encontrada',
                'published_year.integer' => 'O ano deve ser um número inteiro',
                'published_year.min' => 'Ano inválido',
                'published_year.max' => 'Ano não pode ser maior que o ano atual',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $book->update($request->all());
            $book->load(['author', 'category', 'publisher']);

            return response()->json([
                'success' => true,
                'message' => 'Livro atualizado com sucesso',
                'data' => $book
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove um livro do sistema
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::findOrFail($id);
            $bookTitle = $book->title;
            
            $book->delete();

            return response()->json([
                'success' => true,
                'message' => "Livro '{$bookTitle}' removido com sucesso"
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover livro',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
