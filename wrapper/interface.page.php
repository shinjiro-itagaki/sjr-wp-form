<?php
namespace sjr;

interface PageInterface
{
    public function getID() : int;
    public function getContent() : string;
    public function getType() : string;
    public function getURL() : string;
}
