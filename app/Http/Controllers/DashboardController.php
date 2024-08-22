<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\FacNew;
use App\Models\NewRecord;
use App\Models\PrevApp;
use App\Models\RequestType;
use App\Models\ZmainApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;



class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matric = session('matric');

        $users = DB::table('zmain_app')
            ->join('fac_new', 'fac_new.id', '=', 'zmain_app.faculty')
            ->join('dept_new', 'dept_new.id', '=', 'zmain_app.department')
            ->join('degree_new', 'degree_new.id', '=', 'zmain_app.degree')
            ->join('field_new', 'field_new.id', '=', 'zmain_app.field_of_interest')
            ->join('prev_app', 'prev_app.user_id', '=', 'zmain_app.user_id')
            ->select('zmain_app.*', 'fac_new.faculty as faculty_name', 'dept_new.department as department_name', 'degree_new.degree as degree_name', 'field_new.field_title as specialization_name')
            ->where('prev_app.matric', $matric)
            ->get();

        return view('dashboard', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $matric = $user->matric;

        $prevApp = PrevApp::where('matric', $matric)->first();
        if (!$prevApp) {
            return response()->json(['error' => 'User not found in prev_app table.'], 404);
        }
        $newRecord = NewRecord::find($prevApp->user_id);

        if (!$newRecord) {
            return response()->json(['error' => 'User not found in new table.'], 404);
        }
        $surname = $newRecord->Surname;
        $othernames = $newRecord->Other_names;
        $maidenname = ' ';

        $requestTypes = RequestType::all();
        $faculties = FacNew::orderBy('faculty', 'asc')->get();

        return view('apply', compact('requestTypes', 'faculties', 'surname', 'othernames', 'maidenname'));
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
        'faculty' => 'sometimes|required',
        'department' => 'sometimes|required',
        'degree' => 'sometimes|required',
        'field' => 'sometimes|required',
    ]);

    $transcriptAmount = RequestType::where('requesttype', $request->transcript_type)->first();
    if (!$transcriptAmount) {
        return redirect()->back()->with('error', 'Invalid transcript type');
    }

    $cartItem = [
        'matric' => auth()->user()->matric,
        'request' => $request->transcript_type,
        'num_copies' => $request->number_of_copies,
        'fee' => $transcriptAmount['amount'],
        'degree' => $request->degree,
    ];

    Cart::create($cartItem);

    $prevApp = PrevApp::where('matric', auth()->user()->matric)->first();
    if (!$prevApp) {
        return response()->json(['error' => 'User not found in prev_app table.'], 404);
    }
    $user_id = $prevApp->user_id;
    $zmain = ZmainApp::where('user_id', $user_id)->first();
    //|| $request->has(['faculty','department','degree','field'])
    if (!$zmain) {
        $zmainData = [
            'user_id' => $user_id,
            'faculty' => $request->faculty,
            'department' => $request->department,
            'degree' => $request->degree,
            'field_of_interest' => $request->field,
        ];

        ZmainApp::create($zmainData);
    }

    session()->push('cart', $cartItem);

    return redirect()->to('cart');
}

    // public function cart()
    // {
    //     $user = Auth::user();
    //     $cartItems = Cart::where('matric', $user->matric)->get();

    //     return view('cart', compact('cartItems'));
    // }

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
