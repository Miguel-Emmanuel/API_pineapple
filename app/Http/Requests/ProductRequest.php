<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ProductRequest extends FormRequest {
  public function authorize(){ return true; }
  public function rules(){
    return [
      'nombre'=>'required|string|max:255',
      'precio'=>'required|numeric|min:0',
      'stock'=>'required|integer|min:0',
      'descripcion'=>'nullable|string|max:1000',
      'imagen'=>'nullable|image|mimes:jpeg,png,webp|max:2048'
    ];
  }
}
