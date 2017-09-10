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
 * @param string $title Book title
 * @param string $author Book author 
 * @param string $description Book description 
 * @param integer $id Book id (optional) 
 */
	public function __construct()  
    {
		$numargs = func_num_args();
		$id = -1;
		if($numargs == 1) {
			$book = func_get_arg(0);
			$id = $book->id;
			$title = $book->title;
			$author = $book->author;
			$description = $book->description;
		}elseif($numargs > 2) {
			if($numargs == 4)
				$id = func_get_arg(3);
			$title = func_get_arg(0);
			$author = func_get_arg(1);
			$description = func_get_arg(2);
		}
        $this->id = $id;
        $this->title = $title;
	    $this->author = $author;
	    $this->description = $description;
    } 
}

?>