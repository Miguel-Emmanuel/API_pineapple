<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
  public function register(Request $r) {
    $r->validate([
      'name'=>'required|string|max:255',
      'email'=>'required|email|unique:users',
      'password'=>'required|string|min:6|confirmed'
    ]);
    $user = User::create([
      'name'=>$r->name,
      'email'=>$r->email,
      'password'=>Hash::make($r->password)
    ]);
    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['user'=>$user,'token'=>$token],201);
  }
  public function login(Request $r) {
    $r->validate(['email'=>'required|email','password'=>'required']);
    $user = User::where('email',$r->email)->first();
    if(!$user || !Hash::check($r->password,$user->password)){
      return response()->json(['message'=>'Invalid credentials'],401);
    }
    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['user'=>$user,'token'=>$token],200);
  }
  public function me(Request $r){
    return response()->json($r->user(),200);
  }
  public function logout(Request $r){
    $r->user()->currentAccessToken()->delete();
    return response()->noContent();
  }
}
