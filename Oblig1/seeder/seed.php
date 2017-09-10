<?php

require_once('Seeder/Seeder.php');

Seeder::configure(
    'localhost',
    'root',
    '',
    'oblig1'
);

Seeder::table(function($table) {
    $table->name = 'book';
    $table->primary('id')->autoIncrement();
    $table->field('title')->varchar(500)->notNull();
    $table->field('author')->varchar(250)->notNull();
    $table->field('description')->varchar(2000)->default('');
    return $table;
});


Seeder::seed('book', function($table) {
    $table->title('Jungle Book');
    $table->author('R. Kipling');
    $table->description('A classic book.');
    return $table;
});
Seeder::seed('book', function($table) {
    $table->title('Moonwalker');
    $table->author('J. Walker');
    $table->description('');
    return $table;
});
Seeder::seed('book', function($table) {
    $table->title('PHP & MySQL for Dummies');
    $table->author('J. Valade');
    $table->description('Written by some smart gal.');
    return $table;
});