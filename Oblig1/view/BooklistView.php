<?php
include_once('View.php');

/** The BooklistView is the class that creates the page showing the complete collection of books. 
 * @author Rune Hjelsvold
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
Class BooklistView extends View {
	protected $books;
	protected $idParamName;
	protected $opParamName;
	protected $addOpName;
	
    /** Constructor 
     * @author Rune Hjelsvold
	 * @param $books The collection of books - in the form of an array of Books - to be shown.
	 * @param $opParamName The name of the parameter to used in the query string for passing the operation to be performed.
	 * @param $addOpName The name to be used for the add operation.
     * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
     */
	public function __construct($books, $opParamName, $addOpName)  
    {  
        $this->books = $books;
        $this->opParamName = $opParamName;
        $this->addOpName = $addOpName;
    } 
	
	/** Used by the superclass to generate page title
	 */
	protected function getPageTitle() {
		return 'Book Collection';
	}
	
	/** Used by the superclass to generate page content
	 */
	protected function getPageContent() {
		$content = <<<HTML
<h2>Current Titles</h2>
<table id='bookList'>
  <thead>
	<tr><td>ID</td><td>Title</td><td>Author</td><td>Description</td></tr>
  </thead>
  <tbody>
HTML;

		foreach ($this->books as $book) {
    		$content .= '<tr id="book' . $book->id . '"><td><a href="index.php?id=' . $book->id . '">' . $book->id . '</a></td>'
			          . '<td>' . htmlspecialchars($book->title) . '</td><td>' . htmlspecialchars($book->author) . '</td>'
					  . '<td>' . htmlspecialchars($book->description) . '</td></tr>';
		}

		$content .= <<<HTML
  </tbody>
</table>
<h2>New Titles</h2>
HTML;
		$content .= $this->createAddForm();

        return $content;
	}
	
	/** Helper function generating HTML code for the form for adding new books to the collection
	 */
	protected function createAddForm() {
		return 
		'<form id="addForm" action="index.php" method="post">'
		. '<input name="'.$this->opParamName.'" value="'.$this->addOpName.'" type="hidden"/>'
		. 'Title:<br/>'
		. '<input name="title" type="text" value=""/><br/>'
		. 'Author:<br/>'
		. '<input name="author" type="text" value=""/><br/>'
		. 'Description:<br/>'
		. '<input name="description" type="text" value=""/><br/>'
        . '<input type="submit" value="Add new book"/>'
        . '</form>';
	}
}
?>