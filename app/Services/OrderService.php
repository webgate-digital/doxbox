<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Throwable;

class OrderService
{
    public function __construct(public OrderRepository $_orderRepository){}

    public function getOrders()
    {
        try {
            return $this->_orderRepository->getOrders();
        } catch (Throwable $throwable){
            return false;
        }
    }

    public function getOrder($uuid, $token)
    {
        try {
            return $this->_orderRepository->getOrder($uuid, $token);
        } catch (Throwable $throwable){
            return false;
        }
    }
}
