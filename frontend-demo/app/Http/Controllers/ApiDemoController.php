<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiDemoController extends Controller
{
    private $apiBaseUrl = 'http://localhost:8000/api';

    public function index()
    {
        return view('api-demo');
    }

    public function register(Request $request)
    {
        try {
            $response = Http::post($this->apiBaseUrl . '/auth/register', [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

            return response()->json([
                'status' => $response->status(),
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $response = Http::post($this->apiBaseUrl . '/auth/login', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            return response()->json([
                'status' => $response->status(),
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function me(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $request->token,
            ])->get($this->apiBaseUrl . '/auth/me');

            return response()->json([
                'status' => $response->status(),
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function protected(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $request->token,
            ])->get($this->apiBaseUrl . '/protected');

            return response()->json([
                'status' => $response->status(),
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $request->token,
            ])->post($this->apiBaseUrl . '/auth/refresh');

            return response()->json([
                'status' => $response->status(),
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $request->token,
            ])->post($this->apiBaseUrl . '/auth/logout');

            return response()->json([
                'status' => $response->status(),
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ]);
        }
    }
}