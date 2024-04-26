<?php

namespace App\Http\Controllers;

use App\Models\DegreeNew;
use App\Models\DeptNew;
use App\Models\FacNew;
use App\Models\FieldNew;
use App\Models\FieldofInterest5;
use App\Models\RequestType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $matric = session('user')->matric;

    // Fetch all request types
    $requestTypes = RequestType::all();

    // Fetch all field of interest data
    $fieldOfInterests = FieldofInterest5::all();

    // Initialize arrays to hold related data
    $facData = [];
    $deptData = [];
    $degreeData = [];
    $fieldData = [];

    // Group field of interests by faculty
    $fieldOfInterestsByFaculty = $fieldOfInterests->groupBy('fac');

    // Retrieve related data based on faculty
    foreach ($fieldOfInterestsByFaculty as $facultyId => $fieldOfInterests) {
        $fac = FacNew::find($facultyId)->faculty;

        foreach ($fieldOfInterests as $fieldOfInterest) {
            // Fetch department data
            $deptData[$facultyId][$fieldOfInterest->id] = DeptNew::find($fieldOfInterest->dept)->department ?? null;

            // Fetch degree data
            $degreeData[$facultyId][$fieldOfInterest->id] = DegreeNew::find($fieldOfInterest->degree)->degree ?? null;

            // Fetch field data
            $fieldData[$facultyId][$fieldOfInterest->id] = FieldNew::find($fieldOfInterest->field)->field_title ?? null;
        }

        $facData[$facultyId] = $fac;
    }

    return view('apply', compact('requestTypes', 'facData', 'deptData', 'degreeData', 'fieldData', 'matric'));
}

    public function apply()
    {
        // Fetch all request types
        $requestTypes = RequestType::all();

       

        return view('apply2', compact('requestTypes'));
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
