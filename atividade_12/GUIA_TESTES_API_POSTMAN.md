# 🧪 Guia de Testes da API - Postman

## 📋 Informações Gerais

**Base URL:** `http://localhost:8000/api`

**Controller:** `BooksControllerApi.php`

**Observação:** Use a aba **Body** > **x-www-form-urlencoded** para enviar dados nas requisições POST e PUT.

---

## 📚 Endpoints Disponíveis

### 1️⃣ **GET** - Listar todos os livros (com paginação e busca)

**Endpoint:** `GET /api/books`

**URL Completa:** `http://localhost:8000/api/books`

**Parâmetros Opcionais:**
- `search` - Busca por título (ex: `?search=Clean`)
- `per_page` - Itens por página (ex: `?per_page=20`)

**Exemplo com busca:** `http://localhost:8000/api/books?search=Clean&per_page=10`

**Headers:** Nenhum necessário

**Resposta Esperada:** 
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Título do Livro",
      "author_id": 1,
      "category_id": 1,
      "publisher_id": 1,
      "published_year": 2021,
      "cover_image": null,
      "created_at": "2026-02-07T18:42:41.000000Z",
      "updated_at": "2026-02-07T18:42:41.000000Z",
      "author": {
        "id": 1,
        "name": "Nome do Autor"
      },
      "category": {
        "id": 1,
        "name": "Categoria"
      },
      "publisher": {
        "id": 1,
        "name": "Editora"
      }
    }
  ],
  "pagination": {
    "total": 1000,
    "per_page": 15,
    "current_page": 1,
    "last_page": 67
  }
}
```

---

### 2️⃣ **GET** - Buscar livro por ID

**Endpoint:** `GET /api/books/{id}`

**URL Exemplo:** `http://localhost:8000/api/books/1`

**Headers:** Nenhum necessário

**Resposta Esperada (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Título do Livro",
    "author_id": 1,
    "category_id": 1,
    "publisher_id": 1,
    "published_year": 2021,
    "cover_image": null,
    "created_at": "2026-02-07T18:42:41.000000Z",
    "updated_at": "2026-02-07T18:42:41.000000Z",
    "author": {
      "id": 1,
      "name": "Nome do Autor"
    },
    "category": {
      "id": 1,
      "name": "Categoria"
    },
    "publisher": {
      "id": 1,
      "name": "Editora"
    }
  }
}
```

**Resposta Esperada (404):**
```json
{
  "success": false,
  "message": "Livro não encontrado"
}
```

---

### 3️⃣ **POST** - Criar novo livro (com validação)

**Endpoint:** `POST /api/books`

**URL Completa:** `http://localhost:8000/api/books`

**Headers:** Nenhum necessário

**Body (x-www-form-urlencoded):**

| Key | Value | Obrigatório | Validação |
|-----|-------|-------------|-----------|
| `title` | `"Meu Livro Teste"` | ✅ Sim | Máximo 255 caracteres |
| `author_id` | `1` | ✅ Sim | Deve existir na tabela authors |
| `category_id` | `1` | ✅ Sim | Deve existir na tabela categories |
| `publisher_id` | `1` | ✅ Sim | Deve existir na tabela publishers |
| `published_year` | `2024` | ✅ Sim | Entre 1000 e ano atual + 1 |
| `cover_image` | `null` | ❌ Não | - |

**Exemplo de dados:**
```
title: Clean Code
author_id: 5
category_id: 1
publisher_id: 10
published_year: 2008
```

**Resposta Esperada (201 - Sucesso):**
```json
{
  "success": true,
  "message": "Livro criado com sucesso",
  "data": {
    "id": 1001,
    "title": "Clean Code",
    "author_id": 5,
    "category_id": 1,
    "publisher_id": 10,
    "published_year": 2008,
    "cover_image": null,
    "created_at": "2026-02-07T19:30:00.000000Z",
    "updated_at": "2026-02-07T19:30:00.000000Z",
    "author": {
      "id": 5,
      "name": "Robert C. Martin"
    },
    "category": {
      "id": 1,
      "name": "Programação"
    },
    "publisher": {
      "id": 10,
      "name": "Prentice Hall"
    }
  }
}
```

**Resposta Esperada (422 - Erro de Validação):**
```json
{
  "success": false,
  "message": "Erro de validação",
  "errors": {
    "title": ["O título é obrigatório"],
    "author_id": ["Autor não encontrado"],
    "published_year": ["O ano deve ser um número inteiro"]
  }
}
```

---

### 4️⃣ **PUT** - Atualizar livro existente (com validação)

**Endpoint:** `PUT /api/books/{id}`

**URL Exemplo:** `http://localhost:8000/api/books/1001`

**Headers:** Nenhum necessário

**Body (x-www-form-urlencoded):**

| Key | Value | Observação |
|-----|-------|------------|
| `title` | `"Clean Code - 2ª Edição"` | Opcional - só envia o que quer atualizar |
| `author_id` | `5` | Opcional |
| `category_id` | `1` | Opcional |
| `publisher_id` | `10` | Opcional |
| `published_year` | `2009` | Opcional |

**⚠️ IMPORTANTE:** 
- Use **PUT** no Postman, não POST
- Você pode enviar apenas os campos que deseja atualizar

**Resposta Esperada (200 - Sucesso):**
```json
{
  "success": true,
  "message": "Livro atualizado com sucesso",
  "data": {
    "id": 1001,
    "title": "Clean Code - 2ª Edição",
    "author_id": 5,
    "category_id": 1,
    "publisher_id": 10,
    "published_year": 2009,
    "cover_image": null,
    "created_at": "2026-02-07T19:30:00.000000Z",
    "updated_at": "2026-02-07T19:35:00.000000Z",
    "author": {
      "id": 5,
      "name": "Robert C. Martin"
    },
    "category": {
      "id": 1,
      "name": "Programação"
    },
    "publisher": {
      "id": 10,
      "name": "Prentice Hall"
    }
  }
}
```

**Resposta Esperada (404):**
```json
{
  "success": false,
  "message": "Livro não encontrado"
}
```

**Resposta Esperada (422 - Validação):**
```json
{
  "success": false,
  "message": "Erro de validação",
  "errors": {
    "author_id": ["Autor não encontrado"]
  }
}
```

---

### 5️⃣ **DELETE** - Deletar livro

**Endpoint:** `DELETE /api/books/{id}`

**URL Exemplo:** `http://localhost:8000/api/books/1001`

**Headers:** Nenhum necessário

**Body:** Nenhum necessário

**Resposta Esperada (200 - Sucesso):**
```json
{
  "success": true,
  "message": "Livro 'Clean Code' removido com sucesso"
}
```

**Resposta Esperada (404):**
```json
{
  "success": false,
  "message": "Livro não encontrado"
}
```

---

## 🧪 Testes das Novas Funcionalidades

### ✨ Teste 1: Paginação
```
GET http://localhost:8000/api/books?per_page=5
```
**Resultado Esperado:**
- ✅ Retorna apenas 5 livros
- ✅ Inclui objeto `pagination` com `total`, `current_page`, `last_page`, `per_page`
- ✅ Para acessar página 2: `?per_page=5&page=2`

---

### ✨ Teste 2: Busca por Título
```
GET http://localhost:8000/api/books?search=Clean
```
**Resultado Esperado:**
- ✅ Retorna apenas livros que contêm "Clean" no título
- ✅ Compatível com paginação: `?search=Clean&per_page=10`

---

### ✨ Teste 3: Validação de Campos Obrigatórios
```
POST http://localhost:8000/api/books
Body: {} (vazio)
```
**Resultado Esperado:**
- ✅ Status 422 (Unprocessable Entity)
- ✅ Resposta inclui `success: false` e `errors` com mensagens em português:
  - "O título é obrigatório"
  - "O ID do autor é obrigatório"
  - "O ID da categoria é obrigatório"
  - "O ID da editora é obrigatório"
  - "O ano de publicação é obrigatório"

---

### ✨ Teste 4: Validação de Ano Inválido
```
POST http://localhost:8000/api/books
Body (x-www-form-urlencoded):
  title: Livro Teste
  author_id: 1
  category_id: 1
  publisher_id: 1
  published_year: 500
```
**Resultado Esperado:**
- ✅ Status 422
- ✅ Erro: "O ano deve ser no mínimo 1000"

---

### ✨ Teste 5: Validação de ID Inexistente
```
POST http://localhost:8000/api/books
Body:
  title: Livro Teste
  author_id: 99999
  category_id: 1
  publisher_id: 1
  published_year: 2024
```
**Resultado Esperado:**
- ✅ Status 422
- ✅ Erro: "Autor não encontrado"

---

### ✨ Teste 6: Relacionamentos nas Respostas
```
GET http://localhost:8000/api/books/1
```
**Resultado Esperado:**
- ✅ Resposta inclui objeto `author` com `id` e `name`
- ✅ Resposta inclui objeto `category` com `id` e `name`
- ✅ Resposta inclui objeto `publisher` com `id` e `name`
- ✅ Todos os relacionamentos vêm juntos, sem precisar de requests adicionais

---

### ✨ Teste 7: Atualização Parcial
```
PUT http://localhost:8000/api/books/1
Body (x-www-form-urlencoded):
  title: Novo Título Atualizado
```
**Resultado Esperado:**
- ✅ Status 200
- ✅ Apenas o título é atualizado
- ✅ Outros campos (author_id, category_id, etc.) permanecem inalterados

---

### ✨ Teste 8: Título com 300 Caracteres
```
POST http://localhost:8000/api/books
Body:
  title: [string com 300 caracteres]
  author_id: 1
  category_id: 1
  publisher_id: 1
  published_year: 2024
```
**Resultado Esperado:**
- ✅ Status 422
- ✅ Erro: "O título não pode exceder 255 caracteres"

---

### ✨ Teste 9: Ano Futuro Inválido
```
POST http://localhost:8000/api/books
Body:
  title: Livro do Futuro
  author_id: 1
  category_id: 1
  publisher_id: 1
  published_year: 2030
```
**Resultado Esperado:**
- ✅ Status 422
- ✅ Erro: "O ano não pode ser maior que 2027" (ano atual + 1)

---

### ✨ Teste 10: Busca + Paginação Combinadas
```
GET http://localhost:8000/api/books?search=Code&per_page=3&page=1
```
**Resultado Esperado:**
- ✅ Retorna até 3 livros que contêm "Code" no título
- ✅ Paginação funciona corretamente com os resultados filtrados
- ✅ `pagination.total` mostra quantos livros correspondem à busca

---

## 🚀 Como Testar no Postman

### Passo 1: Iniciar o servidor
```bash
cd ~/web-2-atts/atividade_12
php artisan serve
```

O servidor estará disponível em: `http://localhost:8000`

### Passo 2: Abrir Postman
Acesse: https://web.postman.co/

### Passo 3: Testar cada endpoint na ordem

1. **GET /api/books** - Listar todos (deve retornar 1000 livros)
2. **GET /api/books/1** - Buscar livro específico
3. **POST /api/books** - Criar novo livro
   - Selecione **Body** > **x-www-form-urlencoded**
   - Adicione todos os campos
4. **GET /api/books/{id_criado}** - Verificar livro criado
5. **PUT /api/books/{id_criado}** - Atualizar livro
   - Selecione **Body** > **x-www-form-urlencoded**
   - Modifique os campos
6. **DELETE /api/books/{id_criado}** - Deletar livro
7. **GET /api/books/{id_criado}** - Confirmar deleção (deve retornar 404)

---

## ✅ Checklist de Validação

- [ ] GET /api/books retorna lista de livros (status 200)
- [ ] GET /api/books/1 retorna livro específico (status 200)
- [ ] GET /api/books/99999 retorna "Livro não encontrado" (status 404)
- [ ] POST /api/books cria novo livro (status 201)
- [ ] PUT /api/books/{id} atualiza livro existente (status 200)
- [ ] PUT /api/books/99999 retorna "Livro não encontrado" (status 404)
- [ ] DELETE /api/books/{id} remove livro (status 200)
- [ ] DELETE /api/books/99999 retorna "Livro não encontrado" (status 404)

---

## 🔧 Troubleshooting

### Erro 404 em todas as rotas
- Verifique se o servidor está rodando: `php artisan serve`
- Confirme que a URL base está correta: `http://localhost:8000/api`

### Erro 500 ao criar livro
- Verifique se os IDs de author, category e publisher existem no banco
- Para ver IDs válidos: `php artisan tinker` > `App\Models\Author::pluck('id')`

### Dados não são enviados no PUT
- Certifique-se de usar **x-www-form-urlencoded** no Body
- Use o método **PUT**, não POST

---

## 📊 Dados de Teste Recomendados

### IDs Válidos (baseados no seed):
- **Authors:** 1 a 100
- **Categories:** 1 a 10
- **Publishers:** 1 a 50

### Exemplo de Livro para Criar:
```
title: Clean Code
author_id: 5
category_id: 1
publisher_id: 10
published_year: 2008
```

---

## 🎯 Repositório de Referência

Exemplo visto em sala de aula:
https://github.com/AlexandreSGV/exemplo-api-laravel-12.git
