<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'Name', 'Price', 'UPC', 'Image', 'uuid'
    ];
    protected $table = "products";

    /**
     * Function Param  : Product Name, Price, UPC, Product Image
     * Purpose         : Add Product
     */
    public function addProduct($input){
        $product = Product::create($input);
        return $product;
    } 
    
    /**
     * Function Param  :
     * Purpose         : Display product list
     */
    public function getProductsList(){
        $products = DB::table('products') 
        ->where('isDeleted','=', "0")       
        ->get();
        return $products;
    }

    /**
     * Function Param  : Product Name, Price, UPC, Product Image 
     * Purpose         : Update Product
     */
    public function updateProduct($input, $uuid){              
        $result = Product::where('uuid',$uuid)->update($input);       
        return $result;
    }

    /**
     * Function Param  : uuid
     * Purpose         : Delete Single Product (soft delete)
     * 
     */
    public function deleteProduct($uuid){        
        $result = Product::where('uuid',$uuid)->update(array('isDeleted'=> '1'));
        return $result;
    }

    /**
     * Function Param  : uuid
     * Purpose         : Delete All Products (soft delete)
     * 
     */
    public function deleteAllProducts($id){        
        $result = Product::whereIn('id',$id)->update(array('isDeleted'=> '1'));
        return $result;
    }

}
