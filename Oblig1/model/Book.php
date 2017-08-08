<?php

/** The Model is the class holding data related to one book. 
 * @author Rune Hjelsvold
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
class Book {
	public $id;
	public $title;
	public $author;
	public $description;

/** Constructor
 * @param $title Book title
 * @param $author Book author 
 * @param $description Book description 
 * @param $id Book id (optional) 
 */
	public function __construct($title, $author, $description, $id = null)  
    {  
        $this->id = $id;
        $this->title = $title;
	    $this->author = $author;
	    $this->description = $description;
    } 
}

?>