<?php 

class Author extends Eloquent { 

	# Relationship method
	public function books() {

		# Author has many books
        	return $this->hasMany('Book');
        
    }
  	
}