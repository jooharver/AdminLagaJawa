<?php
namespace App\Http\Controllers\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourtApiResource;
use App\Models\Court;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourtController extends Controller
{
    public function index()
    {
        // Get all courts
        $courts = Court::latest()->paginate(10);

        // Return collection of courts as a resource
        return new CourtApiResource(true, 'List Data Courts', $courts);
    }

    public function show($id)
    {
        // Find court by ID
        $court = Court::find($id);

        // Check if court exists
        if ($court) {
            return new CourtApiResource(true, 'Court Found', $court);
        } else {
            return new CourtApiResource(false, 'Court Not Found', null);
        }
    }
}
