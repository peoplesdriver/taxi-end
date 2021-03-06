@extends('layouts.app')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ url('home') }}">Home</a></li>
    <li><a href="#">Report</a></li>
    <li class="active">Driving School</li>
</ul>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Driving School History</h3>
    </div>
    <div class="panel-body">
        <div class="row">          
            <div class="col-md-12">
                <form class="form-inline" action="" method="GET">
                    <div class="form-group">
                        <label for="from">Date From</label>
                        <input type="date" class="form-control" id="from" name="from">
                    </div>
                    <div class="form-group">
                        <label for="to">Date To</label>
                        <input type="date" class="form-control" id="to" name="to">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>            
                <button class="btn btn-success" onclick="printDiv('printableArea')"><i class="fa fa-print" aria-hidden="true"></i> Print the Page</button>
                <button type="button" class="btn btn-warning" onclick="excelGen()">Export To Excel</button>          
            </div>
            <hr>
            <div class="col-md-12">
                @if(request()->exists('to') AND request()->exists('from'))
                <div id="printableArea">
                    <table id="printableArea" class="table table-striped">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Current Address</th>
                            <th>Permanent Address</th>
                            <th>ID Card</th>
                            <th>Phone</th>
                            <th>Category</th>
                            <th>Driving Test</th>
                            <th>Theory Test</th>
                            <th>Joined on</th>
                            <th>Registered By</th>
                            <th>Rate</th>
                            <th>Instructor</th>
                            <th>Remarks</th>
                        </thead>
                        <tbody>
                            <?php $i = 0 ?>
                            @foreach ($students as $student)
                            <?php $i++ ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $student->name }}</th>
                                    <td>{{ $student->c_address }}</td>
                                    <td>{{ $student->p_address }}</td>
                                    <td>{{ $student->id_card }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>{{ $student->category }}</td>
                                    <td>{{ $student->finisheddate }}</td>
                                    <td>{{ $student->theorydate }}</td>
                                    <td>{{ $student->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>MVR {{ $student->rate }}</td>
                                    <td>{{ $student->instructor }}</td>
                                    <td>{{ $student->remarks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div> 
        </div>
    </div>        
</div>
@endsection

@section('js')
        <script>
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
            
                document.body.innerHTML = printContents;
            
                window.print();
            
                document.body.innerHTML = originalContents;
            }

            function excelGen() {
                $("#printableArea").table2excel({
                    exclude: ".excludeThisClass",
                    name: "TDS RECORDS 2018 ",
                    filename: "TDS_RECORDSHEET_2018.xls" 
                });
            }
        </script>
        <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
@endsection