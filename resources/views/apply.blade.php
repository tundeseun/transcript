@php
    $notify = DB::table('notify')
        ->where('matric', session('user')->matric)
        ->first();
@endphp

<x-app-layout :pageName="'Apply'">
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
                        action="{{ $notify ? route('dashboard.apply') : route('dashboard.create') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <h2 class="mt-4">Please provide details of your Transcript request and add to cart</h2>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="surname">Surname </label>
                                    <input type="text" id="surname" name="surname" class="form-control" required
                                        placeholder="Please enter your Surname">
                                </div>
                                <div class="col form-group">
                                    <label for="othernames">Other Names</label>
                                    <input type="text" id="othernames" name="othernames" class="form-control"
                                        required placeholder="Provide your other names">
                                </div>
                                <div class="col form-group">
                                    <label for="maiden_name">Maiden Name</label>
                                    <input type="text" id="maiden_name" name="maiden_name" class="form-control"
                                        placeholder="Provide your Maiden name, If applicable">
                                </div>
                            </div>
                        </div>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="faculty">Faculty </label>
                                    <select id="faculty" class="form-control" name="faculty">
                                        <option value="">Select Faculty</option>
                                        @foreach ($facData as $facultyId => $faculty)
                                            <option value="{{ $facultyId }}">{{ $faculty }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col form-group">
                                    <label for="department">Department</label>
                                    <select id="department" class="form-control" name="department">
                                        <option value="">Select Department</option>
                                        @if ($notify && isset($facultyId))
                                            @foreach ($deptData[$facultyId] as $deptId => $department)
                                                <option value="{{ $deptId }}">{{ $department }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col form-group">
                                    <label for="degree">Degree</label>
                                    <select id="degree" class="form-control" name="degree">
                                        <option value="">Select Degree</option>
                                        @if ($notify && isset($facultyId))
                                            @foreach ($degreeData[$facultyId] as $degreeId => $degree)
                                                <option value="{{ $degreeId }}">{{ $degree }}</option>
                                            @endforeach
                                        @endif
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
                                        @if ($notify && isset($facultyId))
                                            @foreach ($fieldData[$facultyId] as $fieldId => $field)
                                                <option value="{{ $fieldId }}">{{ $field }}</option>
                                            @endforeach
                                        @endif
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
                                    <select id="transcript_type" class="form-control" name="transcript_type">
                                        <option value="">Select Transcript Type</option> <!-- Default option -->
                                        @foreach ($requestTypes as $requestType)
                                            <option value="{{ $requestType->type_id }}">
                                                {{ $requestType->requesttype }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col form-group">
                                    <label for="number_of_copies">Number of Copies</label>
                                    <select id="number_of_copies" name="number_of_copies" class="form-control">
                                        <option value="">Select Number of Copies</option>
                                        <option value="1">One (1)</option>
                                        <option value="2">Two (2)</option>
                                        <option value="3">Three (3)</option>
                                        <option value="4">Four (4)</option>
                                        <option value="5">Five (5)</option>
                                    </select>
                                </div>

                            </div>

                            <div class="mt-4">
                                <div class="col form-group">
                                    <label for="file"><strong>Please Upload Notification of Result or
                                            Certificate</strong></label>
                                    @if ($notify)
                                        <input type="text" class="form-control"
                                            value="{{ $notify->Notification_Document }}" readonly>
                                    @else
                                        <input type="file" id="file" name="file" required
                                            class="form-control">
                                    @endif
                                </div>

                            </div>
                            <button class="btn btn-primary" type="submit" id="addToCart">Add to Cart</button>
                        </div>
                    </form>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#faculty').on('change', function() {
                var faculty_id = $(this).val();
                if (faculty_id) {
                    $.ajax({
                        url: '/dashboard/getDepartments',
                        type: 'GET',
                        data: {
                            faculty_id: faculty_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#department').empty();
                            $('#degree').empty();
                            $('#field').empty();
                            $('#department').append(
                                '<option value="">Select Department</option>');
                            $.each(data, function(key, value) {
                                $('#department').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#department').empty();
                    $('#degree').empty();
                    $('#field').empty();
                }
            });

            $('#department').on('change', function() {
                var department_id = $(this).val();
                if (department_id) {
                    $.ajax({
                        url: '/dashboard/getDegrees',
                        type: 'GET',
                        data: {
                            department_id: department_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#degree').empty();
                            $('#field').empty();
                            $('#degree').append('<option value="">Select Degree</option>');
                            $.each(data, function(key, value) {
                                $('#degree').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#degree').empty();
                    $('#field').empty();
                }
            });

            $('#degree').on('change', function() {
                var degree_id = $(this).val();
                if (degree_id) {
                    $.ajax({
                        url: '/dashboard/getFields',
                        type: 'GET',
                        data: {
                            degree_id: degree_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#field').empty();
                            $('#field').append(
                                '<option value="">Select Specialization</option>');
                            $.each(data, function(key, value) {
                                $('#field').append('<option value="' + value.id + '">' +
                                    value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#field').empty();
                }
            });

            // Submit form using AJAX
            $('#transcriptForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        window.location.href = "{{ route('dashboard.cart') }}";
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

</x-app-layout>
