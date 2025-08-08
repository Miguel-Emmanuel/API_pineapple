<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Productos",
 *     description="API para gestionar productos"
 * )
 */
class ProductController extends Controller {

    /**
     * @OA\Get(
     *     path="/api/productos",
     *     summary="Listar productos",
     *     tags={"Productos"},
     *     @OA\Response(response=200, description="Lista de productos")
     * )
     */
  public function index(){ return ProductResource::collection(Product::paginate(12)); }
  public function show(Product $producto){ return new ProductResource($producto); }
  public function store(ProductRequest $r){
    $data = $r->validated();
    if($r->hasFile('imagen')){
      $path = Storage::disk(config('filesystems.default'))->putFile('imagenes', $r->file('imagen'));
      $data['imagen'] = $path;
    }
    $product = Product::create($data);
    return (new ProductResource($product))->response()->setStatusCode(201);
  }
  public function update(ProductRequest $r, Product $producto){
    $data = $r->validated();
    if($r->hasFile('imagen')){
      if($producto->imagen) Storage::disk(config('filesystems.default'))->delete($producto->imagen);
      $path = Storage::disk(config('filesystems.default'))->putFile('imagenes', $r->file('imagen'));
      $data['imagen'] = $path;
    }
    $producto->update($data);
    return new ProductResource($producto);
  }
  public function destroy(Product $producto){
    if($producto->imagen) Storage::disk(config('filesystems.default'))->delete($producto->imagen);
    $producto->delete();
    return response()->noContent();
  }
}
