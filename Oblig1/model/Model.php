<?php

include_once("model/Book.php");

/** The Model is the class holding data about a collection of books. 
 * @author Rune Hjelsvold
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
class Model
{								  
    /**
	 * @todo Use of the session array for storing book collection is to be replaced by a database.
	 */
	public function __construct()  
    {  
	    // Create an initial collection of books
	    if (!isset($_SESSION['BookList']))
		{
			$_SESSION['BookList'] = array(new Book("Jungle Book", "R. Kipling", "A classic book.", 1),
										  new Book("Moonwalker", "J. Walker", "", 2),
										  new Book("PHP for Dummies", "Some Smart Guy", "", 3)
									);
		} 
	}
	
	/** Function returning the complete list of books in the collection
	 * @return an array of book titles indexed by their ids
	 * @todo replace implementation using a real database
	 */
	public function getBookList()
	{
		// here goes some session values to simulate the database
		return $_SESSION['BookList'];
	}
	
	/** Function retrieveing information about a given book in the collection
	 * @param $id the id of the book to be retrieved
	 * @return an Book object if a book matching the $id exists in the collection; null
	 * @todo replace implementation using a real database
	 */
	public function getBookById($id)
	{
		// we use the previous function to get all the books and then we return the requested one.
		// in a real life scenario this will be done through a db select command
		$idx = $this->getBookIndexById($id);
		if ($idx > -1)
		{
			return $_SESSION['BookList'][$idx];
		}
		return null;
	}
	
	/** Adds a new book to the collection
	 * @param $book the book to be added - the id of the book will be set after successful insertion
	 * @todo replace implementation using a real database
	 */
	public function addBook($book)
	{
	    $book->id = $this->nextId();
		$_SESSION['BookList'][] = $book;
	}

	/** Modifies data related to a book in the collection
	 * @param $book the modified book version
	 * @todo replace implementation using a real database
	 */
	public function modifyBook($book)
	{
		$idx = $this->getBookIndexById($book->id);
		if ($idx > -1)
		{
			$_SESSION['BookList'][$idx] = $book;
		}
	}

	/** Deletes data related to a book from the collection
	 * @param $id the id of the book that should be removed from the collection
	 * @todo replace implementation using a real database
	 */
	public function deleteBook($id)
	{
		$idx = $this->getBookIndexById($id);
		if ($idx > -1)
		{
			array_splice($_SESSION['BookList'],$idx, 1);
		}
	}
	
	/** Helper function finding the location of the book in the collection array.
	 * @param $id the id of the book to look for
	 * @todo replace with a call to a database auto_increment function
	 */
	protected function getBookIndexById($id)
	{
		for ($i = 0; $i < sizeof($_SESSION['BookList']); $i++)
        {
			if ($_SESSION['BookList'][$i]->id == $id)
			{
				return $i;
			}
		}
		return -1;
	}
	
	/** Helper function generating a sequence of ids.
	 * @return an value larger than the largest book id in the collection
	 * @todo replace with a call to a database auto_increment function
	 */
	protected function nextId()
	{
		$maxId = 0;
		foreach ($_SESSION['BookList'] as $book)
		{
			if (isset($book) && $book->id > $maxId)
			{
				$maxId = $book->id;
			}
		}
		return $maxId + 1;
	}

}

?>