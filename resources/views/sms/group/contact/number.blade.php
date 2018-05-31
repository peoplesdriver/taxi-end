@extends('layouts.app')

@section('content')



<ul class="breadcrumb">
    <li><a href="{{ url('home') }}">Home</a></li>
    <li><a href="{{ url('groups') }}">Contact Group</a></li>
    <li class="active">{{ $contact->group_name }}</li>
</ul>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">{{ $contact->group_name }}</h3>
    </div>
    <div class="panel-body">
        <div class="row">     
                <div class="col-md-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
        
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                
                                @endif
                            @endforeach
                        </div>
                    </div> 
                    <center>
                        <form class="form-inline" method="POST" action="{{ url()->current() }}/number">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="" for="email">Contact Name:</label>
                                <input type="name" name="name" class="form-control" id="email">
                            </div>
                            <div class="form-group">
                                <label class="" for="pwd">Contact Number:</label>
                                <input type="name" name="number" class="form-control" id="pwd">
                            </div>
                            <button type="submit" class="btn btn-default">Add Contact</button>
                        </form>     
                    </center>  
                <hr>           
            <div class="col-md-12">
                
                
                <table id="company" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contact->numbers as $number)
                        <tr>
                            <td>{{ $number->name }}</td>
                            <td>{{ $number->number }}</td>
                            <td>
                                <a style="margin:1px" class="btn btn-danger" href="{{ url()->current() }}/delete/" onclick="return confirm('Are you sure you would like to delete this? This process cannot be reversed.')">Delete</a>
                                <a style="margin:1px" class="btn btn-warning" href="{{ url()->current() }}/update/">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
        </div>
        
    </div>        
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        var dataSrc = [];

        $.fn.dataTable.ext.buttons.add = {
            text: 'Add',
            action: function () {
                window.location.href = './company/add';
            }
        };
        
        $('#company').DataTable({
            'initComplete': function(){
                var api = this.api();
                api.cells('tr', [0, 1]).every(function(){
                    var data = this.data();
                    if(dataSrc.indexOf(data) === -1){ dataSrc.push(data); }
            });
            $('.dataTables_filter input[type="search"]', api.table().container())
                .typeahead({
                    source: dataSrc,
                    afterSelect: function(value){
                        api.search(value).draw();
                    }
                }
                );
            },


            responsive: true,
          dom: 'Bfrtip',
            buttons: [
                    
                    {
                    extend: 'print',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                    {
                    extend: 'csv',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                    {
                    extend: 'excel',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                    {
                    extend: 'pdf',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                    {
                    extend: 'colvis',
                    className: 'btn btn-success',
                },
            ],
            columnDefs: [ {
                targets: false,
                visible: false
            } ]
        } );

    } );
</script>  
@endsection