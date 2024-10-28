<?php

namespace ZenithPHP\Migrations;

use ZenithPHP\Core\Database\Migration;
use ZenithPHP\Core\Database\Schema;

class _2024_10_28_093729_user extends Migration
{
    public function up()
    {
        Schema::create('user', function (Schema $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->boolean('is_active')->default(1);
            $table->text('bio')->nullable();
            $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
            $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP')->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::drop('user');
    }
}
