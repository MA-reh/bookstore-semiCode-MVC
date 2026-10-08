<?php

interface Middleware
{
    public function handle(string ...$roles): void;
}
