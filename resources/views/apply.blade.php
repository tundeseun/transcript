@php
    $notify = DB::table('notify')
        ->where('matric', session('user')->matric)
        ->first();
@endphp

<x-app-layout :pageName="'Apply'">
    <style>
        #surname, #othernames, #maiden_name{
            color: #000000 !important;
        }
    </style>
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title">
            <div class="row w-full w-100">
                <!-- Title Start -->
                <div class="">
                    <h1 class="mb-0 pb-0 display-4" id="title">Dashboard</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    </nav>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <!-- Content Start -->
        <div class="card mb-2">
            <div class="card-body h-100">
                Welcome <h1>{{ session('user')->Surname }} {{ session('user')->Other_names }}</h1>
            </div>
        </div>
        <!-- Content End -->
        <div class="card mb-2 w-100">
            <div class="card-body h-100 w-100">
                <h1 class="mb-0 pb-0 display-4" id="title">Transcript Request</h1>
                <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                    <form id="transcriptForm"
                        action="{{ $notify ? route('dashboard.apply') : route('dashboard.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <h2 class="mt-4">Please provide details of your Transcript request and add to cart</h2>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="surname">Surname </label>
                                    <input type="text" id="surname" name="surname" class="form-control" readonly
                                        value="{{$surname}}">
                                </div>
                                <div class="col form-group">
                                    <label for="othernames">Other Names</label>
                                    <input type="text" id="othernames" name="othernames" class="form-control"
                                        readonly value="{{$othernames}}">
                                </div>
                                <div class="col form-group">
                                    <label for="maiden_name">Maiden Name</label>
                                    <input type="text" id="maiden_name" name="maiden_name" class="form-control"
                                    readonly value="{{$maidenname}}">
                                </div>
                            </div>
                        </div>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="faculty">Faculty </label>
                                    <select id="faculty" class="form-control" name="faculty">
                                        <option value="">Select Faculty</option>
                                        @foreach($faculties as $faculty)
                                            <option value="{{ $faculty->id }}">{{ $faculty->faculty }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col form-group">
                                    <label for="department">Department</label>
                                    <select id="department" class="form-control" name="department">
                                        <option value="">Select Department</option>
                                        
                                    </select>
                                </div>
                                <div class="col form-group">
                                    <label for="degree">Degree</label>
                                    <select id="degree" class="form-control" name="degree">
                                        <option value="">Select Degree</option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="field">Specialization</label>
                                    <select id="field" class="form-control" name="field">
                                        <option value="">Select Specialization</option>
                                       
                                    </select>

                                </div>
                                <div class="col form-group">
                                    <label for="session_of_entry">Session of Entry</label>
                                    <input type="text" id="session_of_entry" name="session_of_entry"
                                        class="form-control" required placeholder="Provide your Entry session">
                                </div>
                                <div class="col form-group">
                                    <label for="session_of_graduation">Session of Graduation</label>
                                    <input type="text" id="session_of_graduation" name="session_of_graduation"
                                        class="form-control" required placeholder="Provide your Graduation Session">
                                </div>
                            </div>
                        </div>
                        <div class="mt-10 col-md-12">
                           
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="transcript_type">Transcript Type</label>
                                    <select id="transcript_type" class="form-control" name="transcript_type"
                                        required>
                                        <option value="">Select Transcript Type</option>
                                        @foreach ($requestTypes as $type)
                                            <option value="{{ $type->requesttype }}">{{ $type->requesttype }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col form-group">
                                    <label for="number_of_copies">Number of Copies</label>
                                    <select id="number_of_copies" name="number_of_copies" class="form-control"
                                        required>
                                        <option value="">Select Number of Copies</option>
                                        <option value="1">One (1)</option>
                                        <option value="2">Two (2)</option>
                                        <option value="3">Three (3)</option>
                                        <option value="4">Four (4)</option>
                                        <option value="5">Five (5)</option>
                                    </select>
                                </div>

                            </div>                        </div>
                        <div class="mt-10 col-md-12">
                           
                            <button class="btn btn-primary" type="submit" id="addToCart">Add to Cart</button>
                        </div>
                    </form>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#faculty').on('change', function () {
                var facultyId = this.value;
                $('#department').html('<option value="">Select Department</option>');
                $('#degree').html('<option value="">Select Degree</option>');
                $('#field').html('<option value="">Select Specialization</option>');
                if (facultyId) {
                    $.ajax({
                        url: '/get-departments/' + facultyId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (key, value) {
                                $('#department').append('<option value="' + value.id + '">' + value.department + '</option>');
                            });
                        }
                    });
                }
            });

            $('#department').on('change', function () {
                var departmentId = this.value;
                $('#degree').html('<option value="">Select Degree</option>');
                $('#field').html('<option value="">Select Specialization</option>');
                if (departmentId) {
                    $.ajax({
                        url: '/get-degrees/' + departmentId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (key, value) {
                                $('#degree').append('<option value="' + value.id + '">' + value.degree + '</option>');
                            });
                        }
                    });
                }
            });

            $('#degree').on('change', function () {
                var degreeId = this.value;
                var departmentId = $('#department').val();
                $('#field').html('<option value="">Select Specialization</option>');
                if (degreeId && departmentId) {
                    $.ajax({
                        url: '/get-specializations/' + degreeId + '/' + departmentId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (key, value) {
                                $('#field').append('<option value="' + value.id + '">' + value.field_title + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>

</x-app-layout>
