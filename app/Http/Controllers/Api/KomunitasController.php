<?php

namespace App\Http\Controllers\Api;

use App\Models\Komunitas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KomunitasApiResource;
class KomunitasController extends Controller
{
    public function index()
    {
        //get all komunitass
        $komunitass = Komunitas::latest()->paginate(5);

        //return collection of komunitass as a resource
        return new KomunitasApiResource(true, 'List Data Komunitas', $komunitass);
    }

    public function show($id)
    {
        $komunitas = Komunitas::find($id);

        if (!$komunitas) {
            return response()->json(['message' => 'Komunitas not found'], 404);
        }
        return new KomunitasApiResource(true, 'Detail Data Komunitas', $komunitas);
    }
}
