<?php
include_once('View.php');

/** The BooklistView is the class that creates the page showing the complete collection of books. 
 * @author Rune Hjelsvold
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
Class BooklistView extends View {
	protected $books;
	
    /** Constructor 
     * @author Rune Hjelsvold
	 * @param $books The collection of books - in the form of an array of Books - to be shown.
     * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
     */
	public function __construct($books)  
    {  
        $this->books = $books;
    } 
	
	protected function getPageTitle() {
		return 'Book Collection';
	}
	
	protected function getPageContent() {
		$content = <<<HTML
<table>
	<tr><td>ID</td><td>Title</td><td>Author</td><td>Description</td></tr>
HTML;

		foreach ($this->books as $book) {
    		$content .= '<tr><td><a href="index.php?book='.$book->id.'">'.$book->id.'</a></td><td>'.$book->title.'</td><td>'.$book->author.'</td><td>'.$book->description.'</td></tr>';
		}

		$content .= <<<HTML
</table>

</body>
</html>
HTML;

        return $content;
	}
	
}
?>