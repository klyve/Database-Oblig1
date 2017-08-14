<?php
require_once("vendor/autoload.php");

/**
 * Class for testing the functionality of an Oblig 1 implementation
 * in IMT2571.
 * @author Rune Hjelsvold
 */
class Oblig1Test extends \PHPUnit_Framework_TestCase
{
	/**
	 * Holds the root URL for the Oblig 1 site.
	 */
	protected $baseUrl = 'http://localhost/IMT2571/Oblig1/mvc/';
	protected $session;
	/**
	 * Holds the ids of books created during testing for cleanup during teardown.
	 */
	protected $testBookIds;

	protected function setup()
	{
		$this->testBookIds = array();
        $driver = new \Behat\Mink\Driver\GoutteDriver();
        $this->session = new \Behat\Mink\Session($driver);
        $this->session->start();
	}
		
 	protected function teardown()
	{
		// Remove all book entries added when testing server
		foreach ($this->testBookIds as $bookId) {
			$this->removeBookEntry($bookId);
		}
	}
	
	/**
	 * Generates a table of book data used when testing add and modify
	 * operations.
	 */
	protected function generateTestCases() 
	{
		$cases = array();
		// The simple case
		$cases[0] = array
		            (
					    'title' => 'Test title',
					    'author' => 'Test author',
					    'description' => 'Test author'
					);
		// Case where value contains single quote character - may break SQL statements
		$cases[1] = array
		            (
					    'title' => "Test title with ' inside",
					    'author' => "Test title with ' inside",
					    'description' => "Test title with ' inside"
					);
		// Case where value contains less than character - may break HTML code
		$cases[2] = array
		            (
					    'title' => '<script document.body.style.visibility="hidden" />',
					    'author' => '<script document.body.style.visibility="hidden" />',
					    'description' => '<script document.body.style.visibility="hidden" />'
					);
		return $cases;
	}
	
	/**
	 * Computes the number of books in the listed collection.
	 * @param $page the web page containing the book list, or the site root page
	 *        if no page reference is passed.
	 * @return the number of books listed on the page.
	 */
	protected function getBookListLength($page = null)
	{
		if (!$page)
		{
			$this->session->visit($this->baseUrl);
			$page = $this->session->getPage();
		}
		return sizeof($page->findAll('xpath', 'body/table[@id="bookList"]/tbody/tr'));
	}
		
	/**
	 * Adds a book to the collection. The id of the new created book is added to
	 * in $testBookIds for cleanup purposes.
	 * @param $id book id will be set when the book is added to the collection.
	 * @param $title title of the book to be added.
	 * @param $author author of the book to be added.
	 * @param $description description of the book to be added.
	 * @see teardown()
	 */
 	protected function addBook(&$id, $title, $author, $description)
	{
		// Load book list to get to the addForm
        $this->session->visit($this->baseUrl);
        $page = $this->session->getPage();
        $addForm = $page->find('xpath', 'body/form[@id="addForm"]');
		
		// Complete and submit addForm
        $addForm->find('xpath', 'input[@name="title"]')->setValue($title);
        $addForm->find('xpath', 'input[@name="author"]')->setValue($author);
        $addForm->find('xpath', 'input[@name="description"]')->setValue($description);
		$addForm->submit();
		
		// Record the id that was assigned to the book - assuming that the newest book is the last
        $page = $this->session->getPage();		
		$id = substr($page->find('xpath', 'body/table/tbody/tr[last()]/@id')->getText(),4);
		$this->testBookIds[] = $id;
	}
		
	/**
	 * Deletes a book from the collection. The id of the new created book is removed 
	 * from $testBookIds because it no longer needs to be cleaned up.
	 * @param $id id of the book to be deleted from the collection.
	 * @see teardown()
	 * @uses removeBookEntry()
	 */
 	protected function deleteBook($id)
	{
		// Remove book entry in collection
        $this->removeBookEntry($id);
		
		// Remove book id from cleanup array
		$idx = array_search($id, $this->testBookIds);
		array_splice($this->testBookIds, $idx, 1);
	}
	
	/**
	 * Removes a book entry from the web site collection
	 * @param $id id of the book to be removed from the collection.
	 * @see teardown()
	 */
 	protected function removeBookEntry($bookId)
	{
		// Load page containing form to delete the book entry
        $this->session->visit($this->baseUrl . '?id=' . $bookId);
        $page = $this->session->getPage();
		
		// Submit the delete form
        $delForm = $page->find('xpath', 'body/form[@id="delForm"]');
		$delForm->submit();
	}
	
	/**
	 * Modifies data about a book in the collection
	 * one requested.
	 * @param $id id of the book to be updated.
	 * @param $title book title 
	 * @param $author book author
	 * @param $description book description
	 */
 	protected function modifyBook($id, $title, $author, $description)
	{
		// Load page containing form to modify the book entry
        $this->session->visit($this->baseUrl . '?id=' . $id);
        $page = $this->session->getPage();

		// Complete and submit the form to modify the book entry
        $modForm = $page->find('xpath', 'body/form[@id="modForm"]');
        $modForm->find('xpath', 'input[@name="title"]')->setValue($title);
        $modForm->find('xpath', 'input[@name="author"]')->setValue($author);
        $modForm->find('xpath', 'input[@name="description"]')->setValue($description);
		$modForm->submit();
		
	}
		
	/**
	 * Asserts that data about a book in the collection matches the passed values.
	 * Also asserts that the data matches the data on the book details page.
	 * @param $bookId id of the book to be asserted.
	 * @param $bookTitle book title 
	 * @param $bookAuthor book author
	 * @param $bookDescription book description
	 * @uses $assertBookDetails() 
	 */
	protected function assertBookListEntry($bookId, $bookTitle, $bookAuthor, $bookDescription)
	{
		// Load book list to get to the book entries
		$this->session->visit($this->baseUrl);
        $page = $this->session->getPage();		

		// Find the book entry and verify the data matches the expected value
		$book = $page->find('xpath', 'body/table/tbody/tr[@id="book' . $bookId . '"]');
        $this->assertEquals($bookId, $book->find('xpath', 'td[1]/a')->getText(), 'assertBookListEntry: id');
        $this->assertEquals($bookTitle, $book->find('xpath', 'td[position() = 2]')->getText(), 'assertBookListEntry: title');
        $this->assertEquals($bookAuthor, $book->find('xpath', 'td[position() = 3]')->getText(), 'assertBookListEntry: author');
        $this->assertEquals($bookDescription, $book->find('xpath', 'td[position() = 4]')->getText(), 'assertBookListEntry: description');
		
		// Further verify that the content is the same on the details page
		$this->assertBookDetails($bookId, $bookTitle, $bookAuthor, $bookDescription);
	}

	/**
	 * Asserts that data about a book matches the data displayed on the book details page.
	 * @param $bookId id of the book to be asserted.
	 * @param $bookTitle book title 
	 * @param $bookAuthor book author
	 * @param $bookDescription book description
	 */
    protected function assertBookDetails($bookId, $bookTitle, $bookAuthor, $bookDescription)
    {
		// Load book details page
		$this->session->visit($this->baseUrl . '?id=' . $bookId);
        $page = $this->session->getPage();		

		// Verify values shown on form
        $modForm = $page->find('xpath', 'body/form[@id="modForm"]');
        $this->assertEquals($bookId, $modForm->find('xpath', 'input[@name="id"]')->getValue(), 'assertBookListEntry: book id');
        $this->assertEquals($bookTitle, $modForm->find('xpath', 'input[@name="title"]')->getValue(), 'assertBookListEntry: book title');
        $this->assertEquals($bookAuthor, $modForm->find('xpath', 'input[@name="author"]')->getValue(), 'assertBookListEntry: book author');
        $this->assertEquals($bookDescription, $modForm->find('xpath', 'input[@name="description"]')->getValue(), 'assertBookListEntry: book description');
    }
	
	/**
	 * General test of the book collection page.
	 */
    public function testBookCollectionPage()
    {
        $this->session->visit($this->baseUrl);
        $page = $this->session->getPage();

		// Verifying title 
        $title = $page->find('xpath', 'head/title');
        $this->assertEquals('Book Collection', $title->getText(), 'testBookCollectionPage: page title');

		// Verifying the presence of the form for adding new books
		$addForm = $page->find('xpath', 'body/form[@id="addForm"]');
		$this->assertNotNull($addForm, 'testBookCollectionPage: addForm');

		// Verifying the presence of the operator for adding new books
		$addOp = $addForm->find('xpath', 'input[@name="op"]');
        $this->assertEquals('add', $addOp->getValue(), 'testBookCollectionPage: page title');
	}
	
	/**
	 * General test of the book details page
	 * @depends testBookCollectionPage
	 */
    public function testBookDetailsPage()
    {
		$testBookId = -1;
		$this->addBook($testBookId, 'Test title', 'Test author', 'Test author');

		// Load book details page
		$this->session->visit($this->baseUrl . '?id=' . $testBookId);
        $page = $this->session->getPage();

		// Verifying page title
        $title = $page->find('xpath', 'head/title');
        $this->assertEquals('Book Details', $title->getText(), 'testBookDetailsPage: page title');

		// Verifying the form for modifying book data
        $form = $page->find('xpath', 'body/form[@id="modForm"]');
		$this->assertNotNull($form, 'testBookDetailsPage: modForm');
		$op = $form->find('xpath', 'input[@name="op"]');
        $this->assertEquals('mod', $op->getValue(), 'testBookCollectionPage: mod operation');

		// Verifying the form for deleting book entries
        $form = $page->find('xpath', 'body/form[@id="delForm"]');
		$this->assertNotNull($form, 'testBookDetailsPage: delForm');
		$op = $form->find('xpath', 'input[@name="op"]');
        $this->assertEquals('del', $op->getValue(), 'testBookCollectionPage: del operation');
	}
	
	/**
	 * Tests adding new books using various book test cases
	 * @see generateTestCases()
	 * @depends testBookDetailsPage
	 */
    public function testAdd()
    {
		$testBookId = -1;
		$bookListLength = $this->getBookListLength();
				
		foreach ($this->generateTestCases() as $testCase)
		{
			$this->addBook($testBookId, $testCase['title'], $testCase['author'], $testCase['description']);
			$bookListLength += 1;
			
			// Verifying book content in book list and on book details page
			$this->assertEquals($bookListLength, $this->getBookListLength(), 'testAdd: bookListLength');		
			$this->assertBookListEntry($testBookId, $testCase['title'], $testCase['author'], $testCase['description']);
		}
	}
	
	/**
	 * Tests deleting a book entry
	 * @depends testBookDetailsPage
	 */
    public function testDelete()
    {
		$testBookId = -1;
        $this->addBook($testBookId, 'Test book', 'Test author', 'Test description');
		$bookListLength = $this->getBookListLength();
		
		$this->deleteBook($testBookId);
		$this->session->visit($this->baseUrl);
        $page = $this->session->getPage();		

		// Verifying that book is removed from book list
		$this->assertEquals($bookListLength-1, $this->getBookListLength($page), 'testDelete: bookListLength');		
		$book = $page->find('xpath', 'body/table/tbody/tr[@id="book' . $testBookId . '"]');
		$this->assertNull($book, 'testDelete: book not in book table');
    }
	
	/**
	 * Tests modifying book date using various test cases
	 * @see generateTestCases()
	 * @depends testBookDetailsPage
	 */
    public function testModify()
    {
		$testBookId = -1;
		$bookListLength = $this->getBookListLength();
				
        $this->addBook($testBookId, 'Test book for modify', 'Test author for modify', 'Test description for modify');
		
		$bookListLength = $this->getBookListLength();
				
		foreach ($this->generateTestCases() as $testCase)
		{
			$this->modifyBook($testBookId, $testCase['title'], $testCase['author'], $testCase['description']);

			// Verifying book content in book list and on book details page
			$this->assertEquals($bookListLength, $this->getBookListLength(), 'testModify: bookListLength');		
			$this->assertBookListEntry($testBookId, $testCase['title'], $testCase['author'], $testCase['description']);
		}
    }
	
	/**
	 * Tests if book details page is open for SQL injection
	 * @depends testBookDetailsPage
	 */
	public function testSqlInjection()
	{
		$testBookId = -1;
        $this->addBook($testBookId, 'Test book', 'Test author', 'Test description');
		
		$this->session->visit($this->baseUrl . '?id=' . $testBookId . "'; drop table books;--");
        $page = $this->session->getPage();

		// Verifying that id containing injection code was rejected	
        $title = $page->find('xpath', 'head/title');
        $this->assertEquals('Error Page', $title->getText(), 'testSqlInjection: page title');
	}
}
?>