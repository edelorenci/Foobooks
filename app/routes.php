<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/



// Home page
Route::get('/', function() {
	return View::make('index');		
});

/*-------------------------------------------------------------------------------------------------
Demonstration of evil
// !csrf-example
-------------------------------------------------------------------------------------------------*/
Route::get('/csrf-example', function() {

	return View::make('csrf-example');

});

/*-------------------------------------------------------------------------------------------------
// !get login
-------------------------------------------------------------------------------------------------*/
Route::get('/login',
	array(
		'before' => 'guest',
		function() {
			return View::make('login');
		}
	)
);
/*-------------------------------------------------------------------------------------------------
// !post login
-------------------------------------------------------------------------------------------------*/
Route::post('/login', array('before' => 'csrf', function() {

	$credentials = Input::only('email', 'password');

	if (Auth::attempt($credentials, $remember = true)) {
		return Redirect::intended('/')->with('flash_message', 'Welcome Back!');
	}
	else {
		return Redirect::to('/login')->with('flash_message', 'Log in failed; please try again.');
	}

	return Redirect::to('login');

}));


/*-------------------------------------------------------------------------------------------------
// !get logout
-------------------------------------------------------------------------------------------------*/
Route::get('/logout', function() {

	# Log out
	Auth::logout();

	# Send them to the homepage
	return Redirect::to('/');

});

/*-------------------------------------------------------------------------------------------------
// !get signup
-------------------------------------------------------------------------------------------------*/
Route::get('/signup',
	array(
		'before' => 'guest',
		function() {
	    	return View::make('signup');
		}
	)
);

/*-------------------------------------------------------------------------------------------------
// !post signup
-------------------------------------------------------------------------------------------------*/
Route::post('/signup', array('before' => 'csrf', function() {

	$user = new User;
	$user->email    = Input::get('email');
	$user->password = Hash::make(Input::get('password'));

	try {
		$user->save();
	}
	catch (Exception $e) {
		return Redirect::to('/signup')
			->with('flash_message', 'Sign up failed; please try again.')
			->withInput();
	}

	# Log in
	Auth::login($user);

	return Redirect::to('/list')->with('flash_message', 'Welcome to Foobooks!');

}));

/*-------------------------------------------------------------------------------------------------
For demo purposes, print all routes
-------------------------------------------------------------------------------------------------*/
Route::get('/routes', function() {

	$routeCollection = Route::getRoutes();

	foreach ($routeCollection as $value) {
	    echo "<a href='/".$value->getPath()."' target='_blank'>".$value->getPath()."</a><br>";
	}

});


// Display edit form
Route::get('/edit/{title}', function() {

});

// Process edit form
Route::post('/edit/{title}', function() {

});


# Display add form
Route::get('/add/', function() {

	return View::make('add');

});

# Process add form
Route::post('/add/', function() {

	//echo Pre::render(Input::all());

	# Instantiate the book model
	$book = new Book();

	$book->title = Input::get('title');
	$book->author = Input::get('author');
	$book->published = Input::get('published');
	$book->cover = Input::get('cover');
	$book->purchase_link = Input::get('purchase_link');

	# Magic: Eloquent
	$book->save();

	return "Added a new row";

});


// Debug route: Read in the books.json file
Route::get('/data', function() {

	// Set the path
	$path = app_path().'/database/books.json';

	// Load the json file
	$books = File::get($path);

	// Convert the string of JSON into object
	$books = json_decode($books,true);

	// Output so we can check it out
	return Pre::render($books, 'Books');

});

Route::get('mysql-test', function() {

    # Use the DB component to select all the databases
    $results = DB::select('SHOW DATABASES;');

    # If the "Pre" package is not installed, you should output using print_r instead
    return Pre::render($results);

});

Route::get('/practice-creating', function() {

    # Instantiate a new Book model class
    //ORM - Eloquent

    $book = new Book();

    # Set 
    $book->title = 'The Great Gatsby';
    $book->author = 'F. Scott Fiztgerald';
    $book->published = 1925;
    $book->cover = 'http://img2.imagesbn.com/p/9780743273565_p0_v4_s114x166.JPG';
    $book->purchase_link = 'http://www.barnesandnoble.com/w/the-great-gatsby-francis-scott-fitzgerald/1116668135?ean=9780743273565';

    # This is where the Eloquent ORM magic happens
    $book->save();

    return 'A new book has been added! Check your database to see...';

});



Route::get('/practice-read', function() {


	# Magic: Eloquent
	$books = Book::all();

	# Debugging
	foreach($books as $book) {
		echo $book->title."<br>";
	}


});

Route::get('/practice-reading-one-book', function() {

    $book = Book::where('author', 'LIKE', '%Scott%')->first();

    return $book->title;

});

Route::get('/practice-update', function() {

	//$book = Book::where('id', 'LIKE', '%Scott%')->first();
	//$book = Book::where('id', '=', 1);

	$book = Book::find(1);

	$book->title = 'The Great Gatsby...!';

	$book->save();

	echo "You updated the book.";

});

Route::get('/practice-delete', function() {

	$book = Book::find(2);

	$book->delete();

	echo "This book has been deleted";

});

# Display add form
Route::get('/add/', function() {

	return View::make('add');

});

# Process add form
Route::post('/add/', function() {

	//echo Pre::render(Input::all());

	# Instantiate the book model
	$book = new Book();

	$book->title = Input::get('title');
	$book->author = Input::get('author');
	$book->published = Input::get('published');
	$book->cover = Input::get('cover');
	$book->purchase_link = Input::get('purchase_link');

	# Magic: Eloquent
	$book->save();

	return "Added a new row";

});

//ORM - Eloquent
# List books/search results of books
Route::get('/list/{format?}', function($format = 'html') {

	$query = Input::get('query');

	# If there is a query, search the library with that query
	if($query) {

		# This is how we did it in class...
		//$books = Book::where('author', 'LIKE', "%$query%")->get();

		# Here's a better option because it searches across multiple fields
		$books = Book::where('author_id', 'LIKE', "%$query%")
			->orWhere('title', 'LIKE', "%$query%")
			->orWhere('published', 'LIKE', "%$query%")
			->get();

	}
	# Otherwise, just fetch all books
	else {
		$books = Book::all();	
	}

	# Decide on output method...
	# Default - HTML
	if($format == 'html') {
		return View::make('list')
			->with('books', $books)
			->with('query', $query);
	}
	# JSON
	elseif($format == 'json') {
		return Response::json($books);
	}
	# PDF (Coming soon)
	elseif($format == 'pdf') {
		return "This is the pdf (Coming soon).";
	}	
});


/*-------------------------------------------------------------------------------------------------
// !query-without-constraints
-------------------------------------------------------------------------------------------------*/
Route::get('/query-without-constraints', function() {

	//$books = Book::find(1);

	//$books = Book::first();	

	//$books = Book::all(); //ORM query
	
	//treat as array
	//foreach($books as $book) {
   	 //	echo $book['title']."<br>";
	//}

	//treat as Json
	echo $books;
	
	//treat as object
	//foreach($books as $book) {
    	//echo $book->title."<br>";
	//}

	//pure array
	//var_dump($books->toArray());
	   	

	//Book::pretty_debug($books);

});

//Query with constraints
Route::get('/order-example', function() {

	$books = Book::where('published', '<', 1950)->
		orderBy('title','asc')
		->get();
	//echo $books;
	Book::pretty_debug($books);

});

Route::get('/get-environment',function() {

    echo "Environment: ".App::environment();

});

Route::get('/trigger-error',function() {

    # Class Foobar should not exist, so this should create an error
    $foo = new Foobar;

});

/*-------------------------------------------------------------------------------------------------
// !seed-orm
# Quickly seed books table for demonstration purposes
-------------------------------------------------------------------------------------------------*/
Route::get('/seed-orm', function() {

	# Clear the tables to a blank slate
	DB::statement('SET FOREIGN_KEY_CHECKS=0'); # Disable FK constraints so that all rows can be deleted, even if there's an associated FK
	DB::statement('TRUNCATE books');
	DB::statement('TRUNCATE authors');
	DB::statement('TRUNCATE tags');
	DB::statement('TRUNCATE book_tag');

	# Authors
	$fitzgerald = new Author;
	$fitzgerald->name = 'F. Scott Fitzgerald';
	$fitzgerald->birth_date = '1896-09-24';
	$fitzgerald->save();

	$plath = new Author;
	$plath->name = 'Sylvia Plath';
	$plath->birth_date = '1932-10-27';
	$plath->save();

	$angelou = new Author;
	$angelou->name = 'Maya Angelou';
	$angelou->birth_date = '1928-04-04';
	$angelou->save();

	# Tags (Created using the Model Create shortcut method)
	# Note: Tags model must have `protected $fillable = array('name');` in order for this to work
	$novel         = Tag::create(array('name' => 'novel'));
	$fiction       = Tag::create(array('name' => 'fiction'));
	$nonfiction    = Tag::create(array('name' => 'nonfiction'));
	$classic       = Tag::create(array('name' => 'classic'));
	$wealth        = Tag::create(array('name' => 'wealth'));
	$women         = Tag::create(array('name' => 'women'));
	$autobiography = Tag::create(array('name' => 'autobiography'));

	# Books		
	$gatsby = new Book;
	$gatsby->title = 'The Great Gatsby';
	$gatsby->published = 1925;
	$gatsby->cover = 'http://img2.imagesbn.com/p/9780743273565_p0_v4_s114x166.JPG';
	$gatsby->purchase_link = 'http://www.barnesandnoble.com/w/the-great-gatsby-francis-scott-fitzgerald/1116668135?ean=9780743273565';

	# Associate has to be called *before* the book is created (save()) 
	$gatsby->author()->associate($fitzgerald); # Equivalent of $gatsby->author_id = $fitzgerald->id
	$gatsby->save();

	# Attach has to be called *after* the book is created (save()), 
	# since resulting `book_id` is needed in the book_tag pivot table
	$gatsby->tags()->attach($novel); 
	$gatsby->tags()->attach($fiction); 
	$gatsby->tags()->attach($classic); 
	$gatsby->tags()->attach($wealth); 

	$belljar = new Book;
	$belljar->title = 'The Bell Jar';
	$belljar->published = 1963;
	$belljar->cover = 'http://img1.imagesbn.com/p/9780061148514_p0_v2_s114x166.JPG';
	$belljar->purchase_link = 'http://www.barnesandnoble.com/w/bell-jar-sylvia-plath/1100550703?ean=9780061148514';
	$belljar->author()->associate($plath);
	$belljar->save();

	$belljar->tags()->attach($novel); 	
	$belljar->tags()->attach($fiction); 
	$belljar->tags()->attach($classic); 
	$belljar->tags()->attach($women); 

	$cagedbird = new Book;
	$cagedbird->title = 'I Know Why the Caged Bird Sings';
	$cagedbird->published = 1969;
	$cagedbird->cover = 'http://img1.imagesbn.com/p/9780345514400_p0_v1_s114x166.JPG';
	$cagedbird->purchase_link = 'http://www.barnesandnoble.com/w/i-know-why-the-caged-bird-sings-maya-angelou/1100392955?ean=9780345514400';
	$cagedbird->author()->associate($angelou);
	$cagedbird->save();
	$cagedbird->tags()->attach($autobiography); 
	$cagedbird->tags()->attach($nonfiction); 
	$cagedbird->tags()->attach($classic); 
	$cagedbird->tags()->attach($women);

	echo "Done; check DB for results."; 

});

Route::get('/query-eager-loading-authors', function() {

	# Without eager loading (4 queries)
	$books = Book::orderBy('title')->get();

	# With eager loading (2 queries)
	$books = Book::with('author')->orderBy('title')->get();

	foreach($books as $book) {
		echo $book->author->name.' wrote '.$book->title.'<br>';
	}

});

# /app/routes.php
Route::get('/debug', function() {

    echo '<pre>';

    echo '<h1>environment.php</h1>';
    $path   = base_path().'/environment.php';

    try {
        $contents = 'Contents: '.File::getRequire($path);
        $exists = 'Yes';
    }
    catch (Exception $e) {
        $exists = 'No. Defaulting to `production`';
        $contents = '';
    }

    echo "Checking for: ".$path.'<br>';
    echo 'Exists: '.$exists.'<br>';
    echo $contents;
    echo '<br>';

    echo '<h1>Environment</h1>';
    echo App::environment().'</h1>';

    echo '<h1>Debugging?</h1>';
    if(Config::get('app.debug')) echo "Yes"; else echo "No";

    echo '<h1>Database Config</h1>';
    print_r(Config::get('database.connections.mysql'));

    echo '<h1>Test Database Connection</h1>';
    try {
        $results = DB::select('SHOW DATABASES;');
        echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
        echo "<br><br>Your Databases:<br><br>";
        print_r($results);
    } 
    catch (Exception $e) {
        echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    }

    echo '</pre>';

});