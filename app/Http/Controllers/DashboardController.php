<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DegreeNew;
use App\Models\DeptNew;
use App\Models\FacNew;
use App\Models\FieldNew;
use App\Models\NewRecord;
use App\Models\Biodata;
use App\Models\StudentRecord;
use App\Models\RequestType;
use App\Models\ResultOld;
use App\Models\TransDetailsNew;
use App\Models\TransDetailsFiles;
use App\Models\User;
use App\Models\Result2018;
use App\Models\Result2023;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;



class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matric = session('matric');

        $users = TransDetailsNew::where('matric', $matric)
            ->where('status', 0)
            ->select('faculty', 'department', 'degree', 'feildofinterest')
            ->distinct()
            ->get();


        return view('dashboard', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $matric = $user->matric;

        $user = User::where('matric', $matric)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $surname = $user->Surname;
        $othernames = $user->Othernames;
        $maidenname = ' ';
        $title = $user->title;
        $sex = $user->sex;

        // Get the query parameters passed from the link, if they exist
        $faculty = $request->get('faculty') ?? 'Select Faculty';
        $department = $request->get('department') ?? 'Select Department';
        $degree = $request->get('degree') ?? 'Select Degree';
        $field = $request->get('field') ?? 'Select Specialization';

        $requestTypes = RequestType::all();
        $faculties = FacNew::orderBy('faculty', 'asc')->get();

        return view('apply', compact('requestTypes', 'faculties', 'surname', 'othernames', 'maidenname', 'title', 'sex', 'faculty', 'department', 'degree', 'field'));
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

        $matric = auth()->user()->matric;

        $user = User::where('matric', $matric)->first();

        $validatedData = $request->validate([
            'transcript_type' => 'required',
            'number_of_copies' => 'required|numeric|min:1',
            'faculty' => 'sometimes|required',
            'department' => 'sometimes|required',
            'degree' => 'sometimes|required',
            'field' => 'sometimes|required',
            'title' => 'sometimes|required',
            'sex' => 'sometimes|required',
            'surname' => 'sometimes|required',
            'othernames' => 'sometimes|required',
            'maiden' => 'sometimes',
            'session_of_entry' => 'sometimes|required',
            'session_of_graduation' => 'sometimes|required',
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $fac = FacNew::where('id', $validatedData['faculty'])
            ->orWhere('faculty', $validatedData['faculty'])
            ->first();

        if (!$fac) {
            return redirect()->back()->with('error', 'Faculty not found');
        }

        $dept = DeptNew::where('id', $validatedData['department'])
            ->orWhere('department', $validatedData['department'])
            ->first();

        if (!$dept) {
            return redirect()->back()->with('error', 'Department not found');
        }

        $degrees = DegreeNew::where('id', $validatedData['degree'])
            ->orWhere('degree', $validatedData['degree'])
            ->first();

        if (!$degrees) {
            return redirect()->back()->with('error', 'Degree not found');
        }

        $specializations = FieldNew::where('id', $validatedData['field'])
            ->orWhere('field_title', $validatedData['field'])
            ->first();

        if (!$specializations) {
            return redirect()->back()->with('error', 'Specialization not found');
        }

        $facName = $fac->faculty;
        $deptName = $dept->department;
        $degreeName = $degrees->degree;
        $specializationName = $specializations->field_title;




        $transcriptAmount = RequestType::where('requesttype', $request->transcript_type)->first();
        if (!$transcriptAmount) {
            return redirect()->back()->with('error', 'Invalid transcript type');
        }

        $cartItem = [
            'matric' => $matric,
            'request' => $validatedData["transcript_type"],
            'num_copies' => $validatedData["number_of_copies"],
            'fee' => $transcriptAmount['amount'],
            'degree' => $degreeName,
        ];

        Cart::create($cartItem);

        // Prepare the data for storage
        $transDetailsItems = [
            'matric' => $matric, // Ensure this is provided in the request
            'Surname' => $validatedData['surname'],
            'Othernames' => $validatedData['othernames'],
            'maiden' => $validatedData['maiden'] ?? '',
            'sex' => $validatedData['sex'],
            'tittle' => $validatedData['title'], // Make sure 'tittle' matches your database column name
            'degree' => $degreeName,
            'sessionadmin' => $validatedData['session_of_entry'], // Rename as necessary
            'sessiongrad' => $validatedData['session_of_graduation'], // Duplicate key corrected below
            'faculty' => $facName,
            'department' => $deptName,
            'feildofinterest' => $specializationName,
            'award' => null,
            'programme' => null,
            'date_requested' => now(),
        ];


        // Store the data
        $transDetails = TransDetailsNew::create($transDetailsItems);

        // Store uploaded file path separately
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('uploads/notification', 'public');

            TransDetailsFiles::create([
                'trans_details_id' => $transDetails->id,
                'file_path' => $filePath,
            ]);
        } else {
            return redirect()->back()->with('error', 'File Upload Failed');
        }


        session()->push('cart', $cartItem);

        return redirect()->to('cart');
    }

    public function adminDashboard()
    {
        $records = TransDetailsNew::where('status', 0)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth'); // Select only necessary fields
                    }
                ])
            ->get();



        return view('admin.dashboard', ['records' => $records]); // Adjust the view path as necessary
    }
    public function processRecord(Request $request)
    {

        $matric = $request->input('matric');
        $sessionAdmin = $request->input('sessionadmin');

        Log::info("Processing record for matric: $matric, sessionAdmin: $sessionAdmin");

        if (preg_match('/^(\d{4})\/(\d{4})$/', $sessionAdmin, $matches)) {
            $startYear = intval($matches[1]);
            $endYear = intval($matches[2]);

            if ($startYear >= 2023) {
                // **2023/2024 and above: Check Result2023 first, fallback to Result2018**
                $biodata = StudentRecord::where('matric', $matric)->first();
                if (!$biodata) {
                    Log::error('No StudentRecord found for matric: ' . $matric);
                }
                $normalizedSecAdmin = preg_replace('/\/20(\d{2})$/', '/$1', $sessionAdmin);

                $transDetails = TransDetailsNew::where('matric', $matric)->first();
                Log::info('biodata: ' . $biodata);
                Log::info('transDetails: ' . $transDetails);
                $gender = $biodata->sex ?? $transDetails->sex ?? 'N/A';


                $results = Result2023::with('course') // Eager load the 'course' relationship
                ->with(['faculty', 'department'])
                    ->select('*') // Select all columns
                    ->where('matric', $matric)
                    ->where('yr_of_entry', $normalizedSecAdmin)
                    ->get()
                    ->makeHidden(['status']); // Hide the 'status' column


                Log::info('results: ' . $results);


                if ($results->isEmpty()) {
                    // If Result2023 is empty, fallback to Result2018
                    $biodata = Biodata::where('matric', $matric)->first();
                    

                    // Try fetching from Result2018 first
                    $results = Result2018::with('course')
                        ->where('stud_id', $matric)
                        ->where('sec', $sessionAdmin)
                        ->get();
                }

                return view('admin.transcript', compact('biodata', 'results', 'gender'));


            } elseif ($startYear >= 2018 && $startYear <= 2022) {
                // **2018/2019 and above: Use Result2018**
                $biodata = Biodata::where('matric', $matric)->first();
                
                $results = Result2018::with('course')
                    ->where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->get();

                return view('admin.transcript', compact('biodata', 'results'));


            } elseif ($startYear >= 2013 && $startYear <= 2017) {
                // **2013/2014 to 2016/2017: Check Result2018 first, fallback to ResultOld**
                $biodata = Biodata::where('matric', $matric)->first();
                
                $results = Result2018::where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();


                if ($results->isEmpty() || !$biodata) {
                    // If Result2018 is empty, fallback to ResultOld
                    $records = TransDetailsNew::where('matric', $matric)
                        ->where('sessionadmin', $sessionAdmin)
                        ->first();

                    $results = ResultOld::where('matno', $matric)
                        ->where('sec', $sessionAdmin)
                        ->with('course')
                        ->get();

                    return view('admin.transcript_old', compact('records', 'results'));
                }
                return view('admin.transcript', compact('biodata', 'results'));


            } else {
                // **Older than 2013: Use ResultOld**
                $records = TransDetailsNew::where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $results = ResultOld::where('matno', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();

                return view('admin.transcript_old', compact('records', 'results'));
            }

            // // **CGPA Calculation (for 2013 and above)**
            // $totalGradePoints = 0;
            // $totalUnits = 0;

            // foreach ($results as $result) {
            //     $score = $result->score;
            //     $courseUnit = $result->c_unit ?? 3;

            //     // Assign grade points
            //     $point = match (true) {
            //         $score <= 39 => 0,
            //         $score >= 40 && $score < 45 => 1,
            //         $score >= 45 && $score < 50 => 2,
            //         $score >= 50 && $score < 55 => 3,
            //         $score >= 55 && $score < 60 => 4,
            //         $score >= 60 && $score < 65 => 5,
            //         $score >= 65 && $score < 70 => 6,
            //         $score >= 70 && $score <= 100 => 7,
            //         default => 0
            //     };

            //     $totalGradePoints += $point * $courseUnit;
            //     $totalUnits += $courseUnit;
            // }

            // $cgpa = ($totalUnits > 0) ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

            // Log::info("Results found: ", $results->toArray());

            // return view('admin.transcript', compact('biodata', 'results'));
        }






        // if (
        //     preg_match('/^(\d{4})\/(\d{4})$/', $sessionAdmin, $matches)
        // ) {
        //     $startYear = intval($matches[1]);
        //     $endYear = intval($matches[2]);

        //     if ($startYear >= 2018 || ($endYear == 18 && $startYear >= 2017)) {
        //         // 2018/2019 and above
        //         $biodata = Biodata::where('matric', $matric)->first();
        //         $yearOfEntry = $biodata->yr_of_entry;
        //         $results = Result2018::with('course')
        //             ->where('stud_id', $matric)
        //             ->where('sec2', $yearOfEntry)
        //             ->get();

        //         $totalGradePoints = 0;
        //         $totalUnits = 0;

        //         foreach ($results as $result) {
        //             $score = $result->score;
        //             $courseUnit = $result->c_unit ?? 3;

        //             // Assign grade points
        //             $point = match (true) {
        //                 $score <= 39 => 0,
        //                 $score >= 40 && $score < 45 => 1,
        //                 $score >= 45 && $score < 50 => 2,
        //                 $score >= 50 && $score < 55 => 3,
        //                 $score >= 55 && $score < 60 => 4,
        //                 $score >= 60 && $score < 65 => 5,
        //                 $score >= 65 && $score < 70 => 6,
        //                 $score >= 70 && $score <= 100 => 7,
        //                 default => 0
        //             };

        //             $totalGradePoints += $point * $courseUnit;
        //             $totalUnits += $courseUnit;
        //         }

        //         $cgpa = ($totalUnits > 0) ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

        //         Log::info("Results found: ", $results->toArray());

        //         return view('admin.transcript', compact('biodata', 'results', 'cgpa'));

        //     } elseif ($startYear >= 2013 && $startYear <= 2017) {
        //         // Handle 2013/2014 to 2016/2017 sessions
        //         $results = Result2018::where('stud_id', $matric)
        //             ->where('sec2', $sessionAdmin)
        //             ->with('course')
        //             ->get();
        //         $biodata = Biodata::where('matric', $matric)->first();

        //         $totalGradePoints = 0;
        //         $totalUnits = 0;

        //         foreach ($results as $result) {
        //             $score = $result->score;
        //             $courseUnit = $result->c_unit ?? 3;

        //             // Assign grade points
        //             $point = match (true) {
        //                 $score <= 39 => 0,
        //                 $score >= 40 && $score < 45 => 1,
        //                 $score >= 45 && $score < 50 => 2,
        //                 $score >= 50 && $score < 55 => 3,
        //                 $score >= 55 && $score < 60 => 4,
        //                 $score >= 60 && $score < 65 => 5,
        //                 $score >= 65 && $score < 70 => 6,
        //                 $score >= 70 && $score <= 100 => 7,
        //                 default => 0
        //             };

        //             $totalGradePoints += $point * $courseUnit;
        //             $totalUnits += $courseUnit;
        //         }

        //         $cgpa = ($totalUnits > 0) ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

        //         Log::info("Results found: ", $results->toArray());

        //         return view('admin.transcript', compact('biodata', 'results', 'cgpa'));


        //         if ($results->isEmpty()) {
        //             // If no results in Result2018, check in ResultOld
        //             $records = TransDetailsNew::where('matric', $matric)
        //             ->where('sessionadmin', $sessionAdmin)
        //             ->first();

        //         $results = ResultOld::where('matno', $matric)
        //             ->where('sec', $sessionAdmin)
        //             ->with('course')
        //             ->get();

        //         Log::error("Invalid sessionadmin value: $sessionAdmin");
        //         return view('admin.transcript_old', compact('records', 'results'));

        //         }



        //     } else {
        //         // Sessions before 2013 or unrecognized formats
        //         $records = TransDetailsNew::where('matric', $matric)
        //             ->where('sessionadmin', $sessionAdmin)
        //             ->first();

        //         $results = ResultOld::where('matno', $matric)
        //             ->where('sec', $sessionAdmin)
        //             ->with('course')
        //             ->get();

        //         Log::error("Invalid sessionadmin value: $sessionAdmin");
        //         return view('admin.transcript_old', compact('records', 'results'));
        //     }
        // }

    }

    public function submitForApproval(Request $request)
    {
        try {
            Log::info('Request Method: ' . $request->method());
            Log::info('Request Data:', $request->all());
    
            // Validate input
            $request->validate([
                'matric' => 'required',
                'secAdmin' => 'required',
                'cgpa' => 'required|numeric|min:0|max:10',
                'degreeAward' => 'required|string|max:255',
            ]);
    
            // Normalize session admin value
            $normalizedSecAdmin = preg_replace('/\/(\d{2})$/', '/20$1', $request->secAdmin);
    
            // Retrieve transcript record
            $transcript = TransDetailsNew::where('matric', $request->matric)
                ->where('sessionadmin', $normalizedSecAdmin)
                ->first();
    
            if (!$transcript) {
                return back()->with('error', 'Transcript record not found.');
            }
    
            // Update transcript record
            $transcript->update([
                'award' => $request->cgpa,
                'programme' => $request->degreeAward,
                'status' => 1,
            ]);
    
            return redirect()->route('admin.dashboard')->with('success', 'Transcript submitted for approval.');
        } catch (\Exception $e) {
            Log::error('Error submitting transcript: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting the transcript. Please try again.');
        }
    }
    public function approve(Request $request)
    {
        try {
            Log::info('approve Request Method: ' . $request->method());
            Log::info('approve Request Data:', $request->all());
    
            // Validate input
            $request->validate([
                'matric' => 'required',
                'sessionadmin' => 'required',
                
            ]);
    
    
            // Retrieve transcript record
            $transcript = TransDetailsNew::where('matric', $request->matric)
                ->where('sessionadmin', $request->sessionadmin)
                ->first();
    
            if (!$transcript) {
                return back()->with('error', 'Transcript record not found.');
            }
    
            // Update transcript record
            $transcript->update([
                'status' => 2,
            ]);
    
            return redirect()->route('admin.recordProcessed')->with('success', 'Transcript Approved Successfully.');
        } catch (\Exception $e) {
            Log::error('Error submitting approve transcript: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting the transcript. Please try again.');
        }
    }
    public function reject(Request $request)
    {
        try {
            Log::info('reject Request Method: ' . $request->method());
            Log::info('reject Request Data:', $request->all());
    
            // Validate input
            $request->validate([
                'matric' => 'required',
                'sessionadmin' => 'required',
                
            ]);
    
            // Normalize session admin value
    
            // Retrieve transcript record
            $transcript = TransDetailsNew::where('matric', $request->matric)
                ->where('sessionadmin', $request->sessionadmin)
                ->first();
    
            if (!$transcript) {
                return back()->with('error', 'Transcript record not found.');
            }
    
            // Update transcript record
            $transcript->update([
                'status' => 0,
            ]);
    
            return redirect()->route('admin.recordProcessed')->with('success', 'Transcript Rejected Successfully.');
        } catch (\Exception $e) {
            Log::error('Error submitting reject transcript: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting the transcript. Please try again.');
        }
    }
    

    public function recordProcessed()
    {
        $records = TransDetailsNew::where('status', 1)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth'); // Select only necessary fields
                    }
                ])
            ->get();



        return view('admin.recordProcessed', ['records' => $records]); // Adjust the view path as necessary
    }
    public function recordApproved()
    {
        $records = TransDetailsNew::where('status', 2)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth'); // Select only necessary fields
                    }
                ])
            ->get();



        return view('admin.recordApproved', ['records' => $records]); // Adjust the view path as necessary
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
