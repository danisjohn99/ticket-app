<!DOCTYPE html>
<html lang="en">
<head>
  <title>Ticket Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js"></script>
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
// Get the modal
var modal = document.getElementById('delete{{$ticket->id}}');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

<script>
// Get the modal
var modal = document.getElementById('restore{{$ticket->id}}');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
</head>
<body>

<div class="container-fluid">
  <div class="row content">
    <br>
    
    <div class="col-sm-12">
      <div class="well">
        
        <div class="row">
          <div class="col-lg-12 margin-tb">
              <div class="pull-left">
                  <h3> <b style="color: #337ab7;">Ticket:&nbsp;{{$ticket->ticket_number}}</b> 
                </h3>
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
           
          
             <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Received Date: -&nbsp;</strong>{{$ticket->received_date}}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Affected User: -&nbsp;</strong>{{$ticket->user->name}}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Problem Description: -&nbsp;</strong>{{$ticket->problem_description}}
                    </div>
                </div>
                 <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Additional Notes: -&nbsp;</strong>{{$ticket->additional_notes}}
                    </div>
                </div>

                 <div class="col-xs-12 col-sm-12 col-md-12 text-left">
                 @if(Gate::check('isAdmin') || Gate::check('isTechnician'))

                    <i class="fa-solid fa-pen-to-square" data-toggle="modal" data-target="#editTicket{{$ticket->id}}" style="color:green;cursor: pointer;" title="Edit Ticket"></i>&nbsp;&nbsp;
                    @if($ticket->is_delete == 0)
                    <i class="fa-solid fa-trash" onclick="document.getElementById('delete{{$ticket->id}}').style.display='block'" style="color:red;cursor: pointer;" title="Delete Ticket"></i>&nbsp;&nbsp;
                    @elseif($ticket->is_delete == 1)
                   <a href="#"> <i onclick="document.getElementById('restore{{$ticket->id}}').style.display='block'" class="fa-solid fa-recycle" style="color: green;cursor: pointer;" title="Restore Ticket"></i></a>
                   <b style="color:red;">&nbsp;&nbsp; </b>
                    @endif

                  @endif

                    <a href="/home"><i class="fa-solid fa-arrow-left" title="Back to the dashboard"></i></a>
                </div>
            </div>
        
      </div>
    </div>

    <div class="col-sm-12">
      <div class="well">
        
        <div class="row">
          <div class="col-lg-12 margin-tb">
              <div class="pull-left">
                  <h3> <b style="color: #337ab7;">Comments</b></h3>
              </div>
          </div>
        </div>
          
             <div class="row">
                 <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                      <ul>
                      @if($ticketComments)
                      @foreach($ticketComments as $ticketComment)
                        <li>{{$ticketComment->comments}} -&nbsp;&nbsp;&nbsp;<b style="color:#337ab7;">Replied By {{ucfirst($ticketComment->user->role)}}</b></li>
                      @endforeach
                      @endif
                      </ul>
                    </div>
                </div>

            </div>
        
      </div>
    </div>

 @if(Gate::check('isAdmin') || Gate::check('isTechnician'))
    <div class="col-sm-12">
      <div class="well">
        
        <div class="row">
          <div class="col-lg-12 margin-tb">
              <div class="pull-left">
                  <h3> <b style="color: #337ab7;">Reply</b></h3>
              </div>
          </div>
        </div>
            <form action="/store-ticket-comment" method="POST">
              <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
            @csrf
             <div class="row">
                 <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <textarea class="form-control" style="height:50px" name="comments" placeholder="Type here"></textarea>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Reply</button>
                </div>
            </div>
          </form>
        
      </div>
    </div>
@endif



  </div>
</div>

<!-- Delete Ticket Model -->
<div class="modal" id="delete{{$ticket->id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      {{ Form::open(['route' => ['delete.ticket', $ticket->id], 'method' => 'delete','class'=>'modal-content']) }}
      <div class="modal-header">
        <h4 class="modal-title"><b>Delete Ticket</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('delete{{$ticket->id}}').style.display='none'">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete user ticket ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  onclick="document.getElementById('delete{{$ticket->id}}').style.display='none'" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </div>
    </form>
    </div>
</div>


<!-- Restore Ticket Model -->
<div class="modal" id="restore{{$ticket->id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    {{ Form::open(['route' => ['restore.ticket', $ticket->id], 'method' => 'patch','class'=>'modal-content']) }}
    <input type="hidden" name="restore_ticket_id" value="{{$ticket->id}}">
      <div class="modal-header">
        <h4 class="modal-title"><b>Restore Ticket</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="document.getElementById('restore{{$ticket->id}}').style.display='none'">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to restore user ticket?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="document.getElementById('restore{{$ticket->id}}').style.display='none'" class="cancelbtn">Close</button>
        <button type="submit" class="btn btn-success">Restore</button>
      </div>
    </div>
     </form>
  </div>
</div>

<!-- Edit Ticket Model -->
<div class="modal" id="editTicket{{$ticket->id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><b>Edit Ticket</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

         {{ Form::open(['route' => ['update.ticket', $ticket->id], 'method' => 'patch','class'=>'modal-content']) }}
         <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
               <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Received Date:</strong>
                        <input type="date" name="received_date" id="received_date" value="{{$ticket->received_date}}" class="form-control" required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Affected User:</strong>
                        <select id="user_id" name="user_id" class="form-control" required>
                          @if($users)
                          @foreach($users as $user)
                          <option value="{{$user->id}}" {{$ticket->user_id == $user->id ? 'selected':''}}>{{$user->name}}</option>
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
                        <textarea class="form-control" style="height:100px" name="problem_description" placeholder="Description" required>{{$ticket->problem_description}}</textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Additional Notes:</strong>
                        <textarea class="form-control" style="height:100px" name="additional_notes" placeholder="Notes">
                          {{$ticket->additional_notes}}
                        </textarea>
                    </div>
                </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
     {!! Form::close() !!}
    </div>
  </div>
</div>

</body>
</html>
