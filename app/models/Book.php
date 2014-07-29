<?php

class Book extends Eloquent {
	
			# Relationship method...
    			public function author() {
    
    				# Books belongs to Author
	    			return $this->belongsTo('Author');
    			}
    
    			# Relationship method...
    			public function tags() {
    
    				# Books belong to many Tags
        			return $this->belongsToMany('Tag');
    			}


		public static function pretty_debug($books) {

		# If it's an array...
		if(count($books) > 1) {
			foreach($books as $book) {
				echo $book->title."<br>";
			}
		}
		# If it's a string...
		else {
			echo $books->title;
		}
	}




}
