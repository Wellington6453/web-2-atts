<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('publisher_id')->constrained('publishers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'author_id')) {
                $table->dropForeign(['author_id']);
            }
            if (Schema::hasColumn('books', 'category_id')) {
                $table->dropForeign(['category_id']);
            }
            if (Schema::hasColumn('books', 'publisher_id')) {
                $table->dropForeign(['publisher_id']);
            }
        });

        Schema::dropIfExists('books');
    }
};
