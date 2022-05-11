<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCommentsTable
 */
class CreateCommentsTable extends Migration
{

    /** @return void */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIdFor(User::class);
            $table->foreignId('author_id');
            $table->text('text');
            $table->unsignedInteger('parent_id')->nullable()->default(null);
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('author_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('parent_id')
                ->references('id')->on('comments');
        });
    }

    /** @return void */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
