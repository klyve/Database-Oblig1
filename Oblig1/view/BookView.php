<?php
include_once('View.php');

/** The BookView is the class that creates the page showing details about one book. 
 * @author Rune Hjelsvold
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
Class BookView extends View {
	protected $book;
	
    /** Constructor 
     * @author Rune Hjelsvold
	 * @param $book The book to be shown.
     * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
     */
	public function __construct($book)  
    {  
        $this->book = $book;
    } 
	
	protected function getPageTitle() {
		return 'Book Details';
	}
	
	protected function getPageContent() {
        return 'ID:' . $this->book->id . '<br/>'
	           . 'Title:' . $this->book->title . '<br/>'
	           . 'Author:' . $this->book->author . '<br/>'
	           . 'Description:' . $this->book->description 
			   . '<p><a href=index.php>Back to book list</a></p>';
	}	
}
?>
