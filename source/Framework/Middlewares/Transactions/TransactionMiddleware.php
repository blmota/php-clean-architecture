<?php

namespace Source\Framework\Middlewares\Transactions;

use CoffeeCode\Router\Router;
use Source\Framework\Core\Transaction;

class TransactionMiddleware
{
    public function handle(Router $router): bool
    {
        Transaction::open();
        return true;
    }
}