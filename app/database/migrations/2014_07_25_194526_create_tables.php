<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		# Create the authors table
		Schema::create('authors', function($table) {

			# AI, PK
			$table->increments('id');

			# created_at, updated_at columns
			$table->timestamps();

			# General data...
			$table->string('name');
			$table->date('birth_date');

			# Define foreign keys...
			# none needed

		});

		# Create the books table
		Schema::create('books', function($table) {

			# AI, PK
			$table->increments('id');

			# created_at, updated_at columns
			$table->timestamps();

			# General data...
			$table->string('title');
			$table->integer('author_id')->unsigned(); # Important! FK has to be unsigned because the PK it will reference is auto-incrementing
			$table->integer('published');
			$table->string('cover');
			$table->string('purchase_link');

			# Define foreign keys...
			$table->foreign('author_id')->references('id')->on('authors');


		});


		# Create the tags table
		Schema::create('tags', function($table) {

			# AI, PK
			$table->increments('id');

			# created_at, updated_at columns
			$table->timestamps();

			# General data....
			$table->string('name', 64);

			# Define foreign keys...
			# none needed

		});

		# Create pivot table connecting `books` and `tags`
		Schema::create('book_tag', function($table) {

			# AI, PK
			# none needed

			# General data...
			$table->integer('book_id')->unsigned();
			$table->integer('tag_id')->unsigned();

			# Define foreign keys...
			$table->foreign('book_id')->references('id')->on('books');
			$table->foreign('tag_id')->references('id')->on('tags');

		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//

		Schema::drop('authors');
		Schema::drop('books');
		Schema::drop('tags');
		Schema::drop('book_tag');


	}

}
