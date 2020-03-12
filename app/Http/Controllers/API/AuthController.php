<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        // validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirmPassword' => 'required|same:password'
        ]);

        // check for validation failure
        if($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => $validator->errors(),
            ];

            // return response with 422 error
            return response()->json($response, 422);
        }

        // get input
        $input = $request->all();
        // encrypt the users password
        $input['password'] = bcrypt($input['password']);

        // get the user details
        $user = User::create($input);

        // $return response
        $response = [
            'success' => true,
            'message' => 'Registration successful'
        ];

        return response()->json(
            $response, 201
        );
    }

    public function login(Request $request) {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('LaravelWithCrudPassport');
        $token = $tokenResult->token;

        // add token expiry
        $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 200);
    }

    public function user(Request $request) {
        return response()->json(
            [
                'success' => true,
                'message' => $request->user()
            ], 200
        );
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ], 200);
    }
}
