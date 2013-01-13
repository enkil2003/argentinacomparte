<?php
class My_Utils_Class_Info {
    public static function getClassInfo($object) {
        if(!is_object($object)) {
            throw new Exception("It's not an object");
        }
        echo "<pre>";
        echo "Class: ".get_class($object)."\n";
        echo "Methods:\n";
        print_r(get_class_methods($object)."\n");
        echo "Properties:\n";
        print_r(get_object_vars($object)."\n");
        die;
    }
}