Test code for assignment #1 i IMT2571 Data Modelling and Database Systems.

These are integration tests for the book collection in assignment 1. To run these tests, you need to install PHPUnit and the Mink source browser controller/emulator for web applications that is written in PHP. To install Mink, you also need the PHP Composer. 

How to set it all up:

1. Install PHPUnit as explained here: https://phpunit.de/manual/current/en/installation.html
2. Install the Composer as explained here: https://getcomposer.org/download/
3. Go to the tests directory (where this readme file is stored)
4. Install Mink and the Mink Goutte driver by using Composer
   composer require behat/mink behat/mink-goutte-driver --dev
   
... and you should be ready to go.

Run the tests by opening a shell/command window. Go to the tests directory. Run PHPUnit:
   phpunit Oblig1Test.php