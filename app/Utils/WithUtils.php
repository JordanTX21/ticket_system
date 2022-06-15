<?php


namespace App\Utils;

class WithUtils{
    
    public static function withStudent()
    {
        return array(
            'person'
        );
    }
    public static function withProfessor()
    {
        return array(
            'person'
        );
    }
    public static function withMenu()
    {
        return array(
            'typeMenu'
        );
    }
    public static function withTicket()
    {
        return array(
            'student',
            'professor',
            'menu'
        );
    }
}