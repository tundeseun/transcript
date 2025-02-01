<x-admin-layout :pageName="'Approved Transcript Page'">
    <style>
        .cardEmpty {
            display: flex !important;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('../img/no-invoice.png') center center no-repeat;
            justify-content: center !important;
            align-items: center !important;
            height: 50vh !important;


        }

        .card .empty {
            font-size: 2rem;

        }

        .program {
            display: flex;
            justify-content: space-between;
        }

        /* Table styling */
        #recordsTable {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        #recordsTable thead {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center !important;
        }

        #recordsTable tbody tr {
            transition: background-color 0.3s ease;
        }

        #recordsTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Column Separator */
        #recordsTable th,
        #recordsTable td {
            border-right: 1px solid #ddd;
            /* Add border to separate columns */
        }

        #recordsTable th:last-child,
        #recordsTable td:last-child {
            border-right: none;
            /* Remove border for the last column */
        }


        /* Styling for buttons */
        .btn {
            border-radius: 5px;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .d-flex button {
            width: 100px;
        }

        /* Hover effect for buttons */
        .btn-primary:hover {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-success:hover {
            background-color: #28a745;
            border-color: #28a745;
        }

        /* Responsive table */
        @media (max-width: 768px) {

            #recordsTable td,
            #recordsTable th {
                font-size: 0.85rem;
            }

            .d-flex button {
                width: auto;
            }
        }

        .alert {
            z-index: 9999 !important;
            margin-top: 5rem !important;
            background: #198754 !important;
            color: #fff !important;
        }
    </style>
    <div class="container">
        <!-- Title and Top Buttons Start -->

        <div class="page-title">
            <div class="row w-full w-100">
                <!-- Title Start -->
                <div class="">
                    <h1 class="mb-0 pb-0 display-4" id="title">Approved Transcript Page</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    </nav>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        @if(session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" role="alert" style="opacity: 0; transform: translateX(100%); transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out; z-index: 1050;">
            <strong>Success:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    


        <!-- Content Start -->
        <div class="card mb-2">
            <div class="card-body h-100">
                {{-- @dd(session('user')); --}}
                Welcome <h1> {{ session('admin_user')->fullname }}
                </h1>
            </div>
        </div>
        <!-- Content End -->
        <div class="card mb-2 w-100">
            @if ($records->isEmpty())
                <div class="card cardEmpty mb-2">
                    <p class="empty">No Record found</p>


                </div>
            @else
                <div class="card-body h-100 w-100">
                    <div class="program">
                        <h1 class="mb-0 pb-0 display-4" id="title">Transcript Request</h1>
                    </div>
                    <br>
                    <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                        <div class="table-responsive">
                            <table id="recordsTable" class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Matric</th>
                                        <th>Name</th>
                                        <th>Faculty</th>
                                        <th>Department</th>
                                        <th>Degree</th>
                                        <th>Field of Interest</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $index => $record)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $record->matric }}</td>
                                            <td>{{ $record->Surname . ' ' . $record->Othernames }}</td>
                                            <td>{{ $record->faculty }}</td>
                                            <td>{{ $record->department }}</td>
                                            <td>{{ $record->degree }}</td>
                                            <td>{{ $record->feildofinterest }}</td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                   
                                                    <button class="btn btn-success btn-sm w-auto text-nowrap"
                                                        data-matric="{{ $record->matric }}"
                                                        data-sessionadmin="{{ $record->sessionadmin }}"
                                                        onclick="processRecord(this,'{{ $record->matric }}', '{{ $record->sessionadmin }}')">
                                                        View Transcript
                                                    </button>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>



                        </div>
                    </nav>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="viewModalLabel"><strong> Student Transcript Details
                                    </strong></h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Matric:</strong> <span id="modal-matric"></span></p>
                                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                                <p><strong>Faculty:</strong> <span id="modal-faculty"></span></p>
                                <p><strong>Department:</strong> <span id="modal-department"></span></p>
                                <p><strong>Degree:</strong> <span id="modal-degree"></span></p>
                                <p><strong>Field of Interest:</strong> <span id="modal-fieldofinterest"></span></p>
                                <p><strong>Session Admitted:</strong> <span id="modal-sec"></span></p>
                                <p><strong>Purpose:</strong> <span id="modal-purpose"></span></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-labelledby="viewDocumentModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDocumentModalLabel">View Uploaded Document</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Placeholder for the document -->
                                <div id="documentContainer" class="text-center">
                                    <p>Loading...</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>





            @endif
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#recordsTable').DataTable({
                responsive: true, // Enables responsive layout
                paging: true, // Enables pagination
                searching: true, // Enables searching
                ordering: true // Enables column sorting
            });
        });
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        let alert = document.getElementById('success-alert');

        if (alert) {
            // Fade in and Slide in from right
            setTimeout(() => {
                alert.style.opacity = 1;
                alert.style.transform = 'translateX(0)';
            }, 100); // Slight delay for smooth effect

            // Fade out and Slide out to the right after 5 seconds
            setTimeout(() => {
                alert.style.opacity = 0;
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 500); // Remove after fade-out and slide-out
            }, 5000);
        }
    });
</script>




</x-admin-layout>
