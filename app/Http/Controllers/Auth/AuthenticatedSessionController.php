<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
       
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

       
        $throttleKey = strtolower($request->input('email')).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json(['message' => 'Too many login attempts. Try again later.'], 429);
        }

        
        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($throttleKey); 
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        
        RateLimiter::clear($throttleKey);

        
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'status' => 'Login successful',
        ], 200);
    }

    public function test_users_can_logout(): void
    {
        // Create a user and generate a Sanctum token
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
       
        // Act as the user with the token and make the logout request
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token])->post('/logout');
    
        // Check for successful response and ensure user is logged out
        $response->assertStatus(200); // Expecting 200 for a successful API response
        $this->assertGuest($guard = null); // Check if the user is no longer authenticated
    }
    
    

    
    public function destroyAll(Request $request)
    {
        
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout from all devices successful'], 200);
    }
}
