<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DegreeNew;
use App\Models\DeptNew;
use App\Models\FacNew;
use App\Models\FieldNew;
use App\Models\FieldofInterest5;
use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user_id = session('user')->id;

    $users = DB::table('zmain_app')
        ->join('fac_new', 'fac_new.id', '=', 'zmain_app.faculty')
        ->join('dept_new', 'dept_new.id', '=', 'zmain_app.department')
        ->join('degree_new', 'degree_new.id', '=', 'zmain_app.degree')
        ->join('field_new', 'field_new.id', '=', 'zmain_app.field_of_interest')
        ->select('zmain_app.*', 'fac_new.faculty as faculty_name', 'dept_new.department as department_name', 'degree_new.degree as degree_name', 'field_new.field_title as specialization_name')
        ->where('zmain_app.user_id', $user_id)
        ->get();

    return view('dashboard', ['users' => $users]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $matric = session('user')->matric;

        $requestTypes = RequestType::all();

        $fieldOfInterests = FieldofInterest5::all();

        $facData = [];
        $deptData = [];
        $degreeData = [];
        $fieldData = [];

        $fieldOfInterestsByFaculty = $fieldOfInterests->groupBy('fac');

        foreach ($fieldOfInterestsByFaculty as $facultyId => $fieldOfInterests) {
            $fac = FacNew::find($facultyId)->faculty;

            foreach ($fieldOfInterests as $fieldOfInterest) {
                $deptData[$facultyId][$fieldOfInterest->id] = DeptNew::find($fieldOfInterest->dept)->department ?? null;

                $degreeData[$facultyId][$fieldOfInterest->id] = DegreeNew::find($fieldOfInterest->degree)->degree ?? null;

                $fieldData[$facultyId][$fieldOfInterest->id] = FieldNew::find($fieldOfInterest->field)->field_title ?? null;
            }

            $facData[$facultyId] = $fac;
        }

        return view('apply', compact('requestTypes', 'facData', 'deptData', 'degreeData', 'fieldData', 'matric'));
    }

    public function apply()
    {
        $requestTypes = RequestType::all();
        return view('apply2', compact('requestTypes'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'transcript_type' => 'required',
            'number_of_copies' => 'required|numeric|min:1',
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $cartItem = [
            'matric' => auth()->user()->matric,
            'request' => $request->transcript_type,
            'num_copies' => $request->number_of_copies,
            'fee' => 0, 
        ];

        Cart::create($cartItem);

        $request->file->store('transcript_files');
        session()->push('cart', $cartItem);

        return redirect()->route('dashboard.cart');
    }

    public function cart()
    {
        $user = Auth::user();
        $cartItems = Cart::where('matric', $user->matric)->get();

        return view('cart', compact('cartItems'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
