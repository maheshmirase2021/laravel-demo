<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->product  = new Product();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try{
            $products = $this->product->getProductsList();          
            return view('products.index', compact('products'));
        } catch (\Exception $e){
            return response()->json(["status" => 400, "message" => $e->getMessage()]); 
        } 
    }
}
