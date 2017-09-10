<?php


class TableSeeder {
    private $name = '';
    private $data = [];
    public function __construct($name) {
        $this->name = $name;
    }
    public function __call($methodName, $args) {
        $this->data[$methodName] = $args[0];
        return $this;
    }
    public function print() {
        echo $this->build();
    }
    public function build() {
        $str = 'INSERT INTO `' . $this->name . '` (';
        $str .= implode(', ', array_keys($this->data));
        $str .= ') VALUES(\'' . implode('\', \'', array_values($this->data)) . '\')';
        return $str;
    }
}