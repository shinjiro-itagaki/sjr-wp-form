<?php
namespace sjr;

interface PageInterface
{
    public function getID();
    public function getContent();
    public function getType();
    public function getURL();
}
