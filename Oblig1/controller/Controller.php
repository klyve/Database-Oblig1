<?php
include_once("model/Model.php");
include_once("view/BooklistView.php");
include_once("view/BookView.php");

/** The Controller is responsible for handling user requests, for exchanging data with the Model,
 * and for passing user response data to the various Views. 
 * @author Rune Hjelsvold
 * @see model/Model.php The Model class holding book data.
 * @see view/viewbook.php The View class displaying information about one book.
 * @see view/booklist.php The View class displaying information about all books.
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
class Controller {
	public $model;
	
	public function __construct()  
    {  
        $this->model = new Model();
    } 
	
/** The one function running the controller code
 */
	public function invoke()
	{
		if (!isset($_GET['book']))
		{
			// no special book is requested, we'll show a list of all available books
			$books = $this->model->getBookList();
			$view = new BooklistView($books);
			$view->create();
		}
		else
		{
			// show the requested book
			$book = $this->model->getBookById($_GET['book']);
			$view = new BookView($book);
			$view->create();
		}
	}
}

?>