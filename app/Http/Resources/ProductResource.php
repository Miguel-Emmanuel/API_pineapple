<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class ProductResource extends JsonResource {
  public function toArray($request){
    return [
      'id'=>$this->id,
      'nombre'=>$this->nombre,
      'precio'=> (string)$this->precio,
      'stock'=>$this->stock,
      'descripcion'=>$this->descripcion,
      'imagen'=>$this->imagen,
      'imagen_url'=>$this->imagen ? Storage::disk(config('filesystems.default'))->url($this->imagen) : null,
      'created_at'=>$this->created_at,
      'updated_at'=>$this->updated_at,
    ];
  }
}
