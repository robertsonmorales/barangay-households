@extends('layouts.app')
@section('title', $title)

@section('content')
@include('includes.alerts')

<div class="content mx-4">
    <!-- filter -->
    <div class="filters-section flex-column flex-md-row p-4">
        <div class="filters-child mb-3 mb-md-0">
            <label for="pageSize" class="mb-0 mr-2 font-size-sm">Show</label>
            <select name="pageSize" id="pageSize" class="custom-select mr-2 font-size-sm">
                @for($i=0;$i < count($pagesize); $i++)
                    <option value="{{ $pagesize[$i] }}">{{ $pagesize[$i] }}</option>
                @endfor
                
            </select>
            <label for="pageSize" class="mb-0 font-size-sm">entries</label>
        </div>
        <div class="filters-child">
            <div class="position-relative mr-2">
                <input type="text" name="search-filter" class="form-control font-size-sm" id="search-filter" placeholder="Search here..">
                <span class="position-absolute icon"><i data-feather="search"></i></span>
            </div>

            <div class="btn-group">
                <button class="btn text-dark btn-dropdown rounded d-flex align-items-center font-size-sm" data-toggle="dropdown">
                    <span>Actions</span>
                    <span class="ml-2"><i data-feather="chevron-down"></i></span>
                </button>

                <div class="dropdown-menu dropdown-menu-right mt-2 py-2">
                    <a href="{{ route($create) }}" class="dropdown-item py-2">
                        <span class="download-icon mr-2"><i data-feather="plus"></i></span>
                        <span>Add New Record</span>
                    </a>

                    <button class="dropdown-item py-2" id="btn-import">
                        <span class="download-icon mr-2"><i data-feather="upload"></i></span>
                        <span>Import CSV</span>
                    </button>

                    <button class="dropdown-item py-2" id="btn-export">
                        <span class="download-icon mr-2"><i data-feather="download"></i></span>
                        <span>Export as CSV</span>                        
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ends here -->

    <div id="myGrid" class="ag-theme-material"></div>
</div>

@include('includes.modal')

<br>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    var data = <?= $data ?>;
    var gridDiv = document.querySelector('#myGrid');

    var columnDefs = [];
    columnDefs = {
        headerName: 'CONTROLS',
        field: 'controls',
        sortable: false,
        filter: false,
        editable: false,
        pinned: 'left',
        cellRenderer: function(params){
            var edit_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';
            // var lock_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>';
            // var email_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
            var trash_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';

            var edit_url = '{{ route("user_accounts.edit", ":id") }}';
            edit_url = edit_url.replace(':id', params.data.id);

            var eDiv = document.createElement('div');
            eDiv.innerHTML = '';
            eDiv.innerHTML+='<button id="'+params.data.id+'" title="Edit User" class="btn btn-primary btn-edit ml-1">'+ edit_icon +'</button>&nbsp;';
            // eDiv.innerHTML+='<button id="'+params.data.id+'" title="Email User" class="btn btn-info btn-email text-white">'+ email_icon +'</button>&nbsp;';
            // eDiv.innerHTML+='<button id="'+params.data.id+'" title="Lock User" class="btn btn-secondary btn-lock text-white">'+ lock_icon +'</button>&nbsp;';
            eDiv.innerHTML+='<button id="'+params.data.id+'" title="Delete User" class="btn btn-danger btn-remove">'+ trash_icon +'</button>&nbsp;';

            var btn_edit = eDiv.querySelectorAll('.btn-edit')[0];
            var btn_remove = eDiv.querySelectorAll('.btn-remove')[0];

            btn_edit.addEventListener('click', function() {
                window.location.href = edit_url;
            });

            btn_remove.addEventListener('click', function() {
                var data_id = $(this).attr("id");
                $('#form-submit').attr('style', 'display: flex;');
                $('.modal-content').attr('id', params.data.id);
            });
            
            return eDiv;
        }
    };

    for (var i = data.column.length - 1; i >= 0; i--) {       
        if (data.column[i].field == "account_status") {
            data.column[i].cellRenderer = function display(params) {
                if (params.data.account_status == "Active") {
                    return '<div class="badge border border-success text-success p-1 font-size-sm">'+ params.data.account_status +'</div>';
                }else if (params.data.account_status == "Deactivated"){
                    return '<div class="badge border border-dark text-secondary p-1 font-size-sm">'+ params.data.account_status +'</div>';
                }else if (params.data.account_status == "Locked"){
                    return '<div class="badge border border-gray text-secondary p-1 font-size-sm">'+ params.data.account_status +'</div>';
                }
            }
        }

        if (data.column[i].field == "created_at") {
            data.column[i].cellRenderer = function display(params) {
                if (params.data.created_at) {
                    return getNewDateTime(params.data.created_at);
                }
            }
        }

        if (data.column[i].field == "updated_at") {
            data.column[i].cellRenderer = function display(params) {
                if (params.data.updated_at) {
                    return getNewDateTime(params.data.updated_at);
                }
            }
        }
    }

    function getNewDateTime(format){
        date = new Date(format); //'2013-08-0302:00:00Z'
        year = date.getFullYear();
        month = date.getMonth()+1;
        today = date.getDate();
        hours = date.getHours();
        minutes = date.getMinutes();
        seconds = date.getSeconds();

        if (month < 10) {month = '0' + month;}
        if (today < 10) {today = '0' + today;}
        if (hours < 10) {hours = '0' + hours;}
        if (minutes < 10) {minutes = '0' + minutes;}
        if (seconds < 10) {seconds = '0' + seconds;}
        return year + '-' + month + '-' + today + ' ' + hours + ':' + minutes + ':' + seconds;
    }

    data.column.push(columnDefs);

    var gridOptions = {
        defaultColDef: {
            sortingOrder: ['desc', 'asc'], // null
            resizable: true,
            sortable: true,
            filter: true,
            editable: false,
            flex: 1,
        },
        columnDefs: data.column,
        rowData: data.rows,
        groupSelectsChildren: true,
        suppressRowTransform: true,
        enableCellTextSelection: true,
        rowHeight: 48,
        animateRows: true,
        pagination: true,
        paginationPageSize: 25,
        pivotPanelShow: "always",
        colResizeDefault: "shift",
        rowSelection: "multiple",
        rowStyle: { 
            fontFamily: ['Poppins', 'Montserrat', 'Roboto', 'sans-serif'],
            fontWeight: 'normal',
            fontSize: '1em',
            color: '#777'
        },
        onGridReady: function () {
            autoSizeAll();
            // gridOptions.api.sizeColumnsToFit();
            gridOptions.columnApi.moveColumn('controls', 0);
        }
    };

    function autoSizeAll(skipHeader) {
        var allColumnIds = [];
        gridOptions.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });

        gridOptions.columnApi.autoSizeColumns(allColumnIds, skipHeader);
    }

    // EXPORT AS CSV
    $('#btn-export').on('click', function(){
        gridOptions.api.exportDataAsCsv();
    });
    // ENS HERE

    // SEARCH HERE
    function search(data) {
      gridOptions.api.setQuickFilter(data);
    }

    $("#search-filter").on("keyup", function() {
      search($(this).val());
    });
    // ENDS HERE

    // PAGE SIZE
    function pageSize(value){
        gridOptions.api.paginationSetPageSize(value);
    }

    $("#pageSize").change(function(){
        var size = $(this).val();
        pageSize(size);
    });
    // ENDS HERE

    // setup the grid after the page has finished loading
    new agGrid.Grid(gridDiv, gridOptions); 

    $("#btn-cancel").on('click', function(){
        $('.modal').hide();
    });

    $('#btn-remove').on('click', function(){
        var destroy = '{{ route("user_accounts.destroy", ":id") }}';
        url = destroy.replace(':id', $('.modal-content').attr('id'));

        $('#btn-cancel').prop('disabled', true);
        $('#btn-remove').prop('disabled', true);
        $('#btn-cancel').css('cursor', 'not-allowed');
        $('#btn-remove').css('cursor', 'not-allowed');

        $('#btn-remove').html("Removing...");

        document.getElementById("form-submit").action = url;
        document.getElementById("form-submit").submit();
    });
});
</script>
@endsection