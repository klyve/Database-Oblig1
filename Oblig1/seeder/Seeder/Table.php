<?php

class Table {
    private $props = [];
    private $primaryKey;

    public $name;
    public $charset = 'utf8';
    

    public function __construct() {
    }
    private function addItem($name) {
        $this->props[] = new TableProp($name);
        return $this->props[count($this->props)-1];
    }

    public function primary($name) {
        $this->primaryKey = $name;
        return $this->addItem($name)->int(11)->notNull()->unsigned();
    }
    public function field($name) {
        return $this->addItem($name);
    }
    public function build() {
        $str = 'CREATE TABLE IF NOT EXISTS `'.$this->name.'` (';
        for($i = 0; $i < count($this->props); ++$i) {
            $str .= $this->props[$i]->build();
        }
        $str .= 'PRIMARY KEY (`'.$this->primaryKey.'`)';
        $str .= ') ENGINE=InnoDB DEFAULT CHARSET='.$this->charset.';';
        return $str;
    }
 
}


class TableProp {
    private $fieldName;
    private $type;
    private $length = 0;

    private $allowNull = true;
    private $default = 'NULL';

    private $isZerofill = false;
    private $isUnsigned = false;
    private $isBinary = false;

    private $increment = false;
    private $isUnique = false;

    public function __construct($name) {
        $this->fieldName = $name;
    }

    public function build() {
        $str = '`' . $this->fieldName . '` ';
        $str .= $this->type . (($this->length > 0) ? '(' . $this->length . ')' : '');
        $str .= (($this->isUnsigned) ? ' UNSIGNED' : '');
        $str .= (($this->allowNull) ? '' : ' NOT NULL');
        $str .= (($this->allowNull) ? " DEFAULT'" . $this->default . "'" : '');
        $str .= (($this->increment) ? ' AUTO_INCREMENT' : '');
        $str .= ',';
        return $str;
    }
    public function print() {
        echo $this->fieldName . " ";
        echo $this->type . " ";
        echo (($this->allowNull) ? "True " : "False ");
        echo $this->default . " ";
        echo $this->length . "\n";
        return $this;
    }
    public function __call($methodName, $args) {
        $this->type = $methodName;
        if(isset($args[0]))
            $this->length = $args[0];
        return $this;
     }

    public function autoIncrement() {
        $this->increment = true;
        return $this;
    }
    public function unique() {
        $this->isUnique = true;
        return $this;
    }
    public function notNull() {
        $this->allowNull = false;
        return $this;
    }
    public function default($value) {
        $this->default = $value;
        return $this;
    }
    public function unsigned() {
        $this->isUnsigned = true;
        return $this;
    }
    public function zerofill() {
        $this->isZerofill = true;
        return $this;
    }
    public function binary() {
        $this->isBinary = true;
        return $this;
    }
    public function extra($value) {
        $this->extra = $value;
        return $this;
    }

}