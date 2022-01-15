@extends('layouts.app')
 
@section('content')
    <div class="container">
        
        <div class="row">           
            <div class="col-md-5 pull-left">
              <button style="margin-bottom: 10px" class="btn btn-primary deleteAll" >Delete All Selected</button>
            </div>
            <div class="col-md-5 pull-left"><h2>Products</h2> </div>
            <div class="col-md-2 pull-right">
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#addProduct"> Add New Product</a>
            </div>
        </div> 

        <!-- Add Product -->
        <div id="addProduct" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                
                <h4 class="modal-title ">Add Product Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <form role="form" action="{{route('product\product.store')}}" method="post" enctype="multipart/form-data">
                  @csrf
                    <div class="box-body">
                      <div class="form-group">
                        <label for="title">Product Name </label>
                        <input type="text" class="form-control" name="Name" placeholder="Product Name">
                        
                        @if ($errors->has('Name'))
                          <span class="text-danger">Please enter product name </span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="price">Product Price</label>
                        <input type="text" class="form-control" name="Price" placeholder="Product Price"> 
                        @if ($errors->has('Price'))
                          <span class="text-danger">Please enter product price</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="UPC">UPC</label>
                        <input type="text" class="form-control" name="UPC" placeholder="UPC"> 
                        @if ($errors->has('UPC'))
                          <span class="text-danger">Please enter UPC</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="Image">Image</label>
                        <input type="file" class="form-control" name="Image" >                         
                      </div>
                    </div>            
                    <div class="box-footer">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>            

        <!-- Edit Product -->
        <div id="editProduct" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">            
                <h4 class="modal-title">Edit Product Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <form role="form" action="{{route('product\product.update')}}" method="post" enctype="multipart/form-data">
                  @csrf
                    <div class="box-body">                                        
                      <div class="form-group">
                        <label for="Name">Product Name </label>
                        <input type="text" class="form-control" id="Name" name="Name" >                        
                        @if ($errors->has('Name'))
                          <span class="text-danger">Please enter product name </span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="Price">Product Price</label>
                        <input type="text" class="form-control" id="Price" name="Price" > 
                        <input type="hidden" name="uuid" id="uuid" >
                        @if ($errors->has('Price'))
                          <span class="text-danger">Please enter product price</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="UPC">UPC</label>
                        <input type="text" class="form-control" id="UPC" name="UPC" > 
                        @if ($errors->has('UPC'))
                          <span class="text-danger">Please enter UPC</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="Image">Image</label>
                        <input type="file" class="form-control" name="Image" >                         
                      </div>
                    </div>            
                    <div class="box-footer">
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table table-bordered">
          <tr>
              <th width="50px"><input type="checkbox" id="master"></th>
              <th>#</th>
              <th>Name</th>
              <th>Price</th>
              <th>UPC</th>
              <th>Image</th>
              <th width="280px">Action</th>
          </tr>
          <tbody>
              @if(!$products->isEmpty())  
                <?php $c=1;?>
              @foreach($products as $product)
            <tr id="tr_{{$product->id}}">
              <td><input type="checkbox" class="sub_chk" data-id="{{$product->id}}" value="{{$product->id}}"></td>
              <td>{{$c}}</td>
              <td>{{$product->Name}}</td>
              <td>{{$product->Price}}</td>
              <td>{{$product->UPC}}</td>
              <td>
                  @if($product->Image != NULL) 
                    <img src="{{url('product_images/'.$product->Image)}}" alt="preview image" style="max-height: 100px;text-align:center;">
                  @else
                    <p>Image Not Uploaded </p> 
                  @endif                          
              </td> 
              <td>                                       
                  <button 
                    id          ="updateProduct"                   
                    data-uuid   ="{{$product->uuid  }}"  
                    data-Name   ="{{ $product->Name}}"
                    data-Price  ="{{ $product->Price}}"
                    data-UPC    ="{{ $product->UPC}}"
                    class="btn btn-primary" >Edit</button>
                          @csrf                           
                  <button 
                    id="deleteProduct"  
                    data-uuid="{{$product->uuid  }}" 
                    class="btn btn-danger">Delete</button>                                                                                     
                </td>
                       <!-- Delete confirm popup -->
                       <div class="modal fade bd-example-modal-sm" id="productDeleteConfirm" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">                                 
                                  <h6 class="modal-title text-center">Are you sure want to delete this product?</h6>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="box-footer">
                                  <a  type="submit" class="btn btn-primary " data-dismiss="modal">Cancel</button>
                                  <a href="" class="btn btn-danger" id="deleteProdBtn" style="float: right;">Delete </a>
                                </div>  
                            </div>
                          </div>
                        </div>
              </tr>
          <?php $c++;?>
                  @endforeach 
                @endif
            </tbody>
        </table>

    </div>
   
<script>

  // Update Product
  $(document).on('click','#updateProduct',function(e){
    uuid  = $(this).data("uuid");    
    Name  = $(this).data("name");
    Price = $(this).data("price");
    UPC   = $(this).data("upc");
    $("#editProduct").modal('show');
    $("#Name").val(Name);
    $("#Price").val(Price);
    $("#UPC").val(UPC);
    $("#uuid").val(uuid);
  });

  // Delete Product
  $(document).on('click','#deleteProduct',function(e){
    uuid = $(this).data("uuid");
    deleteRout = "deleteprod/"+uuid;
    $("#productDeleteConfirm").modal('show');
    $("#deleteProdBtn").attr("href", deleteRout);
  });

  $(document).ready(function () {

    // Check/Uncheck products
    $('#master').on('click', function(e) {
         if($(this).is(':checked',true)) {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
    });

    // Delete selected products
    $('.deleteAll').on('click', function(e) {
        e.preventDefault();
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).val());
            });  
            if(allVals.length <=0) {  
                alert("Please select product to delete");  
            } else {  
                var check = confirm("Are you sure you want to delete selected product?");  
                if(check == true){                     
                    $.ajax({
                        url: "{{route('product\product.deleteAll')}}",                        
                        type: 'DELETE',                        
                        data: {
                                _token  : $("input[name=_token]").val(),
                                ids     : allVals
                              },
                        success: function (response) {
                            $.each(allVals, function(key,val) {  
                                $("#tr_"+val).remove();
                            });                                                      
                        }                        
                    });
                }  
            }  
    });

  });

</script>
  
@endsection