<?php
namespace sjr;

interface FormDataInterface
{
    public function getID() : int;
    public function getUserID() : int ;
    public function getData() : array;
    // return old data
    public function setData(array $data) : array;
    public function save() : boolean;
}

