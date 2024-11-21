<?php

namespace App\Database\Migrations;

use DzWork\Core\Migration;

class CreateUsersTable extends Migration {
    public function up() {
        $this->schema->create('users', function($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down() {
        $this->schema->drop('users');
    }
}
