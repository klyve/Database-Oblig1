<?php
include_once('View.php');

/** The BookView is the class that creates the page showing details about one book. 
 * @author Rune Hjelsvold
 * @see http://php-html.net/tutorials/model-view-controller-in-php/ The tutorial code used as basis.
 */
Class ErrorView extends View {
	/** Used by the superclass to generate page title
	 */
	protected function getPageTitle() {
		return 'Error Page';
	}
	
	/** Used by the superclass to generate page content
	 */
	protected function getPageContent() {
        return '<p>Something bad happened. Please try again later.</p>';
	}	
}
?>