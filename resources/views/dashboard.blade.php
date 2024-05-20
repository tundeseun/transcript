<x-app-layout :pageName="'Dashboard'">
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
                Welcome <h1> {{ session('user')->id }}{{ session('user')->Surname }} {{ session('user')->Other_names }}
                </h1>
            </div>
        </div>
        <!-- Content End -->
        <div class="card mb-2 w-100">
            <div class="card-body h-100 w-100">
                <h1 class="mb-0 pb-0 display-4" id="title">Programme Details</h1>
                <br>
                <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Faculty</th>
                                    <th>Department</th>
                                    <th>Degree</th>
                                    <th>Specialization</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->faculty_name }}</td>
                                    <td>{{ $user->department_name }}</td>
                                    <td>{{ $user->degree_name }}</td>
                                    <td>{{ $user->specialization_name }}</td>                                        <td>
                                            <a href="{{ route('dashboard.apply') }}" class="btn btn-info">Apply</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                </nav>
            </div>
            </table>
        </div>
    </div>

</x-app-layout>
