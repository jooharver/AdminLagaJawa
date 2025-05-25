<?php

namespace App\Http\Controllers\Api;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TestApiResource;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $tests = Test::latest()->paginate(5);

        return new TestApiResource(true, 'List Data Test', $tests);
    }

    public function show($id)
    {
        $test = Test::find($id);

        if (!$test) {
            return response()->json(['message' => 'Test not found'], 404);
        }

        return new TestApiResource(true, 'Detail Data Test', $test);
    }
}
