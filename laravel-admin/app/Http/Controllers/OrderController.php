<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        Gate::authorize('view', 'orders');
        $orders =   Order::paginate();

        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        Gate::authorize('view', 'orders');
        return new OrderResource(Order::find($id));
    }

    public function export()
    {

        Gate::authorize('view', 'orders');
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=orders.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",

        ];
        $callback = function () {
            $orders = Order::all();
            $file = fopen('php://output', 'w');
            // header
            fputcsv($file, ['ID', 'Name', 'Email', 'Product Title', 'Price', 'Quantity']);
            //body

            foreach ($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);

                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }
}
