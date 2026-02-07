<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Constantes de papéis
    const ROLE_ADMIN = 'admin';
    const ROLE_BIBLIOTECARIO = 'bibliotecario';
    const ROLE_CLIENTE = 'cliente';

    // Funções para verificar papel
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isBibliotecario()
    {
        return $this->role === self::ROLE_BIBLIOTECARIO;
    }

    public function isCliente()
    {
        return $this->role === self::ROLE_CLIENTE;
    }

    public function books()
{
    return $this->belongsToMany(Book::class, 'borrowings')
                ->withPivot('id', 'borrowed_at', 'returned_at')
                ->withTimestamps();
}


}
