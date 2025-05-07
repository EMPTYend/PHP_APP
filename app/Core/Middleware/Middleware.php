<?php

namespace app\Core\Middleware;

interface Middleware
{
    public function handle(callable $next);
}