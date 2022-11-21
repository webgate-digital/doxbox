<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    public function __construct(private OrderService $_orderService)
    {}

    public function index()
    {
        try {
            $response = $this->_orderService->getOrders();
        } catch (Throwable $exception) {
            abort(404);
        }

        $orders = $response['item'];

        return view('customer.orders.index', compact('orders'));
    }

    public function show($orderUuid, $token)
    {
        try {
            $response = $this->_orderService->getOrder($orderUuid, $token);
        } catch (Throwable $throwable) {
            abort(404);
        }

        $order = $response['item'];

        return view('customer.orders.show', compact('order'));
    }
}
