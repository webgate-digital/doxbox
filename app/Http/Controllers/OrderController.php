<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Throwable;
use App\Repositories\TranslationRepository;

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
        $me = session()->get('me');

        return view('customer.orders.index', compact('orders', 'me'));
    }

    public function show($orderUuid, $token)
    {
        try {
            $response = $this->_orderService->getOrder($orderUuid, $token);
        } catch (Throwable $throwable) {
            abort(404);
        }

        $order = $response['item'];
        $order['packeta-selector-branch-name'] = $order['packeta-branch-name'];

        return view('customer.orders.show', compact('order'));
    }
}
