<!DOCTYPE html>
<html lang="en">
<head>


    @include('layouts.header')


  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 550px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
        
    /* On small screens, set height to 'auto' for the grid */
    @media screen and (max-width: 767px) {
      .row.content {height: auto;} 
    }
  </style>
  <script>
    $(function() {
        $('#ticketlist').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('ticket.list') !!}',
            columns: [
                { data: 'ticket_number', name: 'ticket_number' },
                { data: 'status', name: 'status' },
                { data: 'user.name', name: 'user.name' },
                { data: 'problem_description', name: 'problem_description' },
                { data: 'received_date', name: 'received_date' },
                { data: 'comments', name: 'comments' },
                { data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
  </script>
</head>


<body>
<div class="container-fluid">
  <div class="row content">


    @include('layouts.sidebar')


    <div class="col-sm-9">
      <div class="well">
        
        <div class="row">
          <div class="col-lg-12 margin-tb">
              <div class="pull-left">
                  <h3> <b style="color: #337ab7;">New Ticket</b></h3>
              </div>
          </div>
        </div>


        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
        @endif

         
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
           
        <form action="/store-ticket" method="POST">
            @csrf
          
             <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Received Date:</strong>
                        <input type="date" name="received_date" id="received_date" class="form-control" required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Affected User:</strong>
                        <select id="user_id" name="user_id" class="form-control" required>

                          @if($users)
                          @foreach($users as $user)
                          <option value="{{$user->id}}">{{$user->name}}</option>
                          @endforeach
                          @else
                           <option value="">Not Available</option>
                          @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Problem Description:</strong>
                        <textarea class="form-control" style="height:100px" name="problem_description" placeholder="Description" required></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Additional Notes:</strong>
                        <textarea class="form-control" style="height:50px" name="additional_notes" placeholder="Notes"></textarea>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>


      </div>
      
      <div class="row">
      <div class="col-sm-12">
      <table class="table table-bordered data-table" id="ticketlist">
            <thead>
                <tr>
                    <th>Ticket Number</th>
                    <th>Ticket Status</th>
                    <th>User</th>
                    <th>Problem </th>
                    <th>Received Date</th>
                    <th>Total Comments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>
      </div>
    </div>


  </div>
</div>
</body>
</html>
