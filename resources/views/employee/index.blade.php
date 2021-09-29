<div>

    <div class="col-md-6">

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Sorry!</strong> invalid input.<br><br>
            <ul style="list-style-type:none;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if($updateMode)
        @include('employee.update')
    @else
        @include('employee.create')
    @endif


    <table class="table table-striped" style="margin-top:20px;">
        <tr>
            <td>NAME</td>
            <td>ACTION</td>
        </tr>

        @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->banner_id }}</td>
                <td>
                    <button wire:click="edit({{$employee->id}})" class="btn btn-sm btn-outline-danger py-0">Edit</button>
                </td>
            </tr>
        @endforeach
    </table>
    </div>

</div>
