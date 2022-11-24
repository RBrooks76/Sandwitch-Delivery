<?php
namespace App\Http\Controllers;

use App\Order;
use App\OrderProducts;
use App\OrderProductsFeature;
use Illuminate\Http\Request;

use PDF;

class OrderController extends Controller{
    public function viewPrintOrder(Request $request){
        $order = Order::where("id", $request->ID)->first();
        $products = OrderProducts::with('Products')->with("OrderProductsFeature.ProductsFeature")->where("order_id", $request->ID)->get();

        $sub_total = floatval($order->total);
        $delivery_fee = floatval($order->restaurant->user->fees);
        if (!$order->is_pickup) $sub_total -= $delivery_fee;

        $sub_total = sprintf("%.2f", $sub_total);
        $delivery_fee = sprintf("%.2f", $delivery_fee);

        foreach ($products as &$item) {
            $addon_ids = OrderProductsFeature::with("ProductsFeature")->where('order_products_id', $item->id)->get();
            $item->product_addons = $addon_ids;
        }
        
        $filename = "InvoicePDF/".$request->ID.".pdf";
        
        $pdf = PDF::loadView('print', compact('order', 'products', 'sub_total', 'delivery_fee'));
        return $pdf->stream();
    }
}