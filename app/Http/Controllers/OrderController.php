<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['customer', 'details'])->latest('id')->paginate(5);

        return view('admin.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $images = [];

        try {
            DB::transaction(function () use ($request, &$images) {
                $customer = Customer::create($request->customer);
                $supplier = Supplier::create($request->supplier);
                $orderDetails = [];
                $totalAmount = 0;

                foreach ($request->products as $key => $product) {

                    $product['supplier_id'] = $supplier->id;


                    if ($request->hasFile("products.$key.image")) {
                        $imagePath = Storage::put('products', $request->file("products.$key.image"));
                        $images[] = $imagePath;
                        $product['img'] = $imagePath;
                    }


                    $productModel = Product::create($product);

                    $quantity = $request->order_details[$key]['quantity'];
                    $price = $productModel->price;
                    $orderDetails[$productModel->id] = ['quantity' => $quantity, 'price' => $price];
                    $totalAmount += $quantity * $price;
                }

                $order = Order::create([
                    'customer_id' => $customer->id,
                    'total' => $totalAmount
                ]);

                $order->details()->attach($orderDetails);
            }, 3);

            return redirect()->route('orders.index')->with('success', 'Thao tác thành công!');
        } catch (Exception $exception) {
            foreach ($images as $image) {
                if (Storage::exists($image)) {
                    Storage::delete($image);
                }
            }

            return back()->with('error', $exception->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['customer', 'details']);

        return view('admin.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            DB::transaction(function () use ($order, $request) {
                $order->details()->sync($request->order_details);

                $orderDetail = array_map(function ($item) {
                    return $item['price'] * $item['quantity'];
                }, $request->order_details);

                $totalAmount = array_sum($orderDetail);

                $order->update([
                    'total' => $totalAmount
                ]);
            }, 3);

            return back()->with('success', 'Thao tác thành công!');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $order->details()->sync([]);

                $order->delete();
            }, 3);

            return back()->with('success', 'Thao tác thành công!');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
