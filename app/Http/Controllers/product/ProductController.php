<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use Validator;
use Redirect;
use Auth;
use Session;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->product  = new Product();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{           
            $validator = Validator::make($request->all(), [
                'Name'  => 'required',
                'Price' => 'required',
                'UPC'   => 'required',
                //'Image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            $uuid = Str::uuid()->toString();      
            $input = [];             
            $input['uuid']      = $uuid;
            $input['Name']      = $request->Name;
            $input['Price']     = $request->Price;
            $input['UPC']       = $request->UPC;
              
            if ($files = $request->file('Image')) {
                $fileName = "";
                $fileName =  "product".time().'.'.$request->Image->getClientOriginalExtension();
                $request->Image->move(public_path('product_images'), $fileName);               
                $input['Image']    = $fileName;                
            }

            $product = $this->product->addProduct($input);
            if($product){
                Session::flash('message', "Product added successfully");
                return redirect()->route('product\product.index');
            }else{
                Session::flash('message', "Product not added ");
                return redirect()->route('product\product.index');
            } 
        } catch (\Exception $e){
            return response()->json(["status" => 400, "message" => $e->getMessage()]); 
        }  

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{           
            $validator = Validator::make($request->all(), [
                'Name'  => 'required',
                'Price' => 'required',
                'UPC'   => 'required',
                 //'Image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            
            $input = []; 
            $input['Name']      = $request->Name;
            $input['Price']     = $request->Price;
            $input['UPC']       = $request->UPC;
            $input['uuid']      = $request->uuid;

            if ($request->file('Image')) {
                $fileName = "";
                $fileName =  "product".time().'.'.$request->Image->getClientOriginalExtension();
                $request->Image->move(public_path('product_images'), $fileName);               
                $input['Image']    = $fileName;                
            }
            
            $product = $this->product->updateProduct($input, $input['uuid']);
            if($product){
                Session::flash('message', "Product updated successfully");
                return redirect()->route('product\product.index');
            }else{
                Session::flash('message', "Product not updated ");
                return redirect()->route('product\product.index');
            } 
        } catch (\Exception $e){
            return response()->json(["status" => 400, "message" => $e->getMessage()]); 
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, $uuid)
    {
        
        try{
            $result = $this->product->deleteProduct($uuid);            
            if($result){
                Session::flash('message', "Product deleted successfully");
                return redirect()->route('product\product.index');
            }else{
                Session::flash('message', "Product not deleted");
                return redirect()->route('product\product.index');
            }   
        } catch (\Exception $e){
            return response()->json(["status" => 400, "message" => $e->getMessage()]); 
        } 
    }

    public function deleteAll(Request $request)
    {
        try{
            $ids = $request->ids;         
            $result = $this->product->deleteAllProducts($ids);
            return response()->json(['success'=>"Products Deleted successfully."]);
        } catch (\Exception $e){
            return response()->json(["status" => 400, "message" => $e->getMessage()]); 
        }     
    }

}
