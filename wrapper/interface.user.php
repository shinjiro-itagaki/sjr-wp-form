<?php
namespace sjr;

interface CreateUserResult
{
    public function isSuccess() : bool;
    public function userID() : int;
    public function errorMessage() : string;
}

