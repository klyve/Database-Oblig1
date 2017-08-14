<?php

include_once("model/Book.php");

/** The Model is the class holding data about a collection of books. 
 * @author Rune Hjelsvold
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
class Model {
									  
	/** Function returning the complete list of books in the collection
	 * @return an array of book titles indexed by their ids
	 */
	public function getBookList()
	{
		// here goes some hardcoded values to simulate the database
		return array(new Book("Jungle Book", "R. Kipling", "A classic book.", 1),
					 new Book("Moonwalker", "J. Walker", "", 2),
					 new Book("PHP for Dummies", "Some Smart Guy", "", 3)
			    );
	}
	
	/** Function retrieveing information about a given book in the collection
	 * @param $id the id of the book to be retrieved
	 * @return an Book object if a book matching the $id exists in the collection; null
	 */
	public function getBookById($id)
	{
		// we use the previous function to get all the books and then we return the requested one.
		// in a real life scenario this will be done through a db select command
		$allBooks = $this->getBookList();
		foreach ($allBooks as $book) {
			if ($id == (string)$book->id) {
				return $book;
			}
		}
		return null;
	}
	
	
}

?>