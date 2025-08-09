<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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

  /**
   * Método específico para actualizar productos con FormData (POST + method spoofing)
   * Este método resuelve el problema de Laravel con PUT + multipart/form-data
   */
  public function updateWithFormData(Request $request, Product $producto)
  {
    // DEBUG: Log para ver qué datos llegan
    Log::info('UPDATE FORMDATA DEBUG:', [
      'method' => $request->method(),
      'content_type' => $request->header('Content-Type'),
      'all_data' => $request->all(),
      'files' => $request->allFiles(),
      'has_nombre' => $request->has('nombre'),
      'nombre_value' => $request->get('nombre'),
      'has_precio' => $request->has('precio'),
      'precio_value' => $request->get('precio'),
    ]);

    // Validación manual (más flexible que FormRequest para debugging)
    $validatedData = $request->validate([
      'nombre' => 'required|string|max:255',
      'precio' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
      'descripcion' => 'nullable|string|max:1000',
      'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    // Actualizar campos básicos
    $producto->nombre = $validatedData['nombre'];
    $producto->precio = $validatedData['precio'];
    $producto->stock = $validatedData['stock'];
    $producto->descripcion = $validatedData['descripcion'] ?? null;

    // Manejar imagen si existe
    if ($request->hasFile('imagen')) {
      // Eliminar imagen anterior si existe
      if ($producto->imagen) {
        Storage::disk(config('filesystems.default'))->delete($producto->imagen);
      }
      
      // Guardar nueva imagen
      $path = Storage::disk(config('filesystems.default'))->putFile('imagenes', $request->file('imagen'));
      $producto->imagen = $path;
    }

    $producto->save();

    return response()->json([
      'data' => new ProductResource($producto),
      'message' => 'Producto actualizado exitosamente con FormData'
    ]);
  }
}
