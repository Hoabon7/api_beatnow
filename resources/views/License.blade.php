@extends('layouts.admin')

@section('content')
    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Trang
                        chủ</a>
                    <span class="breadcrumb-item">Danh sách khách hàng
                    </span>

                </div>
            </div>

            <div class="header-elements">
                <a href="{{ route('license.create') }}" class="breadcrumb-elements-item text-success font-weight-bold">
                    <i class="icon-pen-plus mr-2"></i>Thêm mới
                </a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="col-lg-12">
                @if (Session::has('notifi'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>{{ Session::get('notifi') }} </strong>
                    </div>
                @endif
            </div>
            <div class="card-header header-elements-inline">
                <h5 class="card-title pb-3">Danh sách đặt mua tài khoản số đẹp </h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
            </div>
            <table class="table datatable-selection-single table-responsive-stack">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>License </th>
                        <th>Trạng thái</th>
                        <th>Thời gian tạo</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listLicense as $license)

                        <tr>
                            <td>{{ $license->id }}</td>
                            <td>{{ $license->code }}</td>
                            <td>
                                @if ($license->status == app\Models\License::ACTIVE)
                                    <span class="badge badge-primary">
                                        {{ $license->convertStatusLicense($license->status) }}
                                    </td>
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        {{ $license->convertStatusLicense($license->status) }}</td>
                                    </span>
                                @endif


                    <td>{{ $license->created_at }}</td>

                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                @if ($license->status == app\Models\License::ACTIVE)
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route('license.is_active', [$license->id, $license->status]) }}"
                                            class="dropdown-item">Vô hiệu hóa</a>

                                    </div>
                                @else
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route('license.is_active', [$license->id, $license->status]) }}"
                                            class="dropdown-item">Kích hoạt</a>

                                    </div>
                                @endif

                            </div>
                        </div>

                    </td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/backend/js/datatable.js') }}"></script>
    <script src="{{ asset('assets/backend/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugin/notifications/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugin/number/jquery.number.min.js') }}"></script>
    <script>
        < script >
            var DatatableAPI = function() {


                    //
                    // Setup module components
                    //

                    // Basic Datatable examples
                    var _componentDatatableAPI = function() {
                            if (!$().DataTable) {
                                console.warn('Warning - datatables.min.js is not loaded.');
                                return;
                            }

                            // Setting datatable defaults
                            $.extend($.fn.dataTable.defaults, {
                                autoWidth: false,
                                columnDefs: [{
                                    orderable: false,
                                    width: 150,
                                    targets: [2]
                                }],
                                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                                language: {
                                    search: '<span>Filter:</span> _INPUT_',
                                    searchPlaceholder: 'Type to filter...',
                                    lengthMenu: '<span>Show:</span> _MENU_',
                                    paginate: {
                                        'first': 'First',
                                        'last': 'Last',
                                        'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                                        'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                                    }
                                }
                            });


                            // Single row selection
                            var singleSelect = $('.datatable-selection-single').DataTable();

                            // Multiple rows selection
                            $('.datatable-selection-multiple').DataTable();


                            // Individual column searching with text inputs
                            $('.datatable-column-search-inputs tfoot td').not(':last-child').each(function() {
                                var title = $('.datatable-column-search-inputs thead th').eq($(this).index())
                                    .text();
                                $(this).html(
                                    '<input type="text" class="form-control input-sm" placeholder="Search ' +
                                    title + '" />');
                            });
                            var table = $('.datatable-column-search-inputs').DataTable();
                            table.columns().every(function() {
                                var that = this;
                                $('input', this.footer()).on('keyup change', function() {
                                    that.search(this.value).draw();
                                });
                            });


                            // Individual column searching with selects
                            $('.datatable-column-search-selects').DataTable({
                                    initComplete: function() {
                                        this.api().columns().every(function() {
                                                var column = this;
                                                var select = $(
                                                        '<select class="form-control filter-select" data-placeholder="Filter"><option value=""></option></select>'
                                                    )
                                                    .appendTo($(column.footer()).not(':last-child').empty())
                                                    .on('change', function() {
                                                        var val = $.fn.dataTable.util.escapeRegex(
                                                            $(this).val()
                                                        );

                                                        column
                                                            .search(val ? '^' + val + '$' : '', true, false)
                                                            .draw();
                                                    });

                                                column.data().unique().sort().each(function(d, j) {
                                                    select.append('<option value="' + d.replace(
                                                        /<(?:.|\n)*/ ** end_phptag **
                                                        //gm, '')+'">'+d.replace(/<(?:.|\n)*?>/gm, '')+'</option>')
                                                    });
                                                });
                                            }
                                        });
                                };

                                // Select2 for length menu styling
                                var _componentSelect2 = function() {
                                    if (!$().select2) {
                                        console.warn('Warning - select2.min.js is not loaded.');
                                        return;
                                    }

                                    // Initialize
                                    $('.dataTables_length select').select2({
                                        minimumResultsForSearch: Infinity,
                                        dropdownAutoWidth: true,
                                        width: 'auto'
                                    });

                                    // Enable Select2 select for individual column searching
                                    $('.filter-select').select2();
                                };


                                //
                                // Return objects assigned to module
                                //

                                return {
                                    init: function() {
                                        _componentDatatableAPI();
                                        _componentSelect2();
                                    }
                                }
                            }();


                            // Initialize module
                            // ------------------------------

                            document.addEventListener('DOMContentLoaded', function() {
                                DatatableAPI.init();
                            });


                            // Filter Ajax
                            $('#status2').on('click', function() {



                                $.ajax({
                                    url: '',
                                    type: 'get',

                                    // This is the important part!
                                    data: {
                                        marchi: marchi
                                    }
                                })
                            });

    </script>
@endsection
