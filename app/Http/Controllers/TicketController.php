<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use DataTables;

//MODEL
use App\Models\Ticket;
use App\Models\TicketComments;
use App\Models\User;


    /**
     * TicketController
     * This controlleris used to interact ticket operations.
     *
     * @author :  <danisjohn99@gmail.com>
     */
    class TicketController extends Controller
    {
       
            /**
             * Store Ticket.
             *
             * @param  Request  $request
             */
            public function storeTicket(Request $request)
            {   
                try{
                    $validator = Validator::make($request->all(), [
                        'received_date' => 'date|before_or_equal:today',
                        'user_id' => 'required',
                        'problem_description' => 'required',
                        'additional_notes' => 'max:128',
                    ]);
                    if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                    }
                    $lastTicket   = Ticket::orderBy('id', 'DESC')->first();
                    $ticketNumber = $lastTicket ? 'TKT000'.++$lastTicket->id  :'TKT0001' ;
                    $userId = $request->user_id;
                    
                    $ticketData = ['ticket_number'=>$ticketNumber,'user_id'=>$userId,'problem_description'=>$request->problem_description,'additional_notes'=>$request->additional_notes,'received_date'=>$request->received_date];
                    Ticket::create($ticketData);
                    return redirect()->route('home')->with('success','Ticket Submitted Successfully'); 

                }catch(\Exception $e){
                    return redirect()->back()->with('error', $e->getMessage());   
                }    
            }


            /**
             * Update Ticket.
             *
             * @param  Request  $request
             */
            public function updateTicket(Request $request)
            {
                try{
                    $validator = Validator::make($request->all(), [
                        'received_date' => 'date|before_or_equal:today',
                        'user_id' => 'required',
                        'problem_description' => 'required',
                        'additional_notes' => 'max:128',
                    ]);
                    if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                    }
                    $ticketId = $request->ticket_id;
                    $updateData = ['user_id'=>$request->user_id,'problem_description'=>$request->problem_description,
                                   'additional_notes'=>$request->additional_notes,'received_date'=>$request->received_date];
                    Ticket::where('id',$ticketId)->update($updateData);
                    return redirect()->route('home')->with('success','Ticket Updated Successfully');  

                }catch(\Exception $e){
                    return redirect()->back()->with('error', $e->getMessage());   
                }         
            }
            

            /**
             * Ticket List
             *
             * @param  Request  $request
             */
            public function ticketList(Request $request)
            {
                    $data = Ticket::with('user','comments')->latest('created_at')->get();
                    return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('status', function($row){
                                $deleted = $row->is_delete ? '<span style="color:red;">Deleted</span>' :'<span style="color:green;">Not Deleted</span>';
                                return $deleted;
                            })
                            ->addColumn('comments', function($row){
                                $commentsCount = $row->comments ? count($row->comments) : '';
                               return $commentsCount;
                            })
                            ->addColumn('action', function($row){
                                $view = "<a href='".url('view/ticket/'.$row->id)."'><i class='fa-solid fa-eye'></i></a>";
                                return $view;
                            })
                            ->rawColumns(['action','status'])
                            ->make(true);     
            }

            
            /**
             * View Ticket.
             */
            public function viewTicket($ticketId)
            {
                $ticket = Ticket::with('user')->where('id',$ticketId)->first();
                $ticketComments = TicketComments::with(['user','ticket'])->where('ticket_id',$ticketId)->latest()->get();
                $users = User::where('role','user')->get();
                if(Auth::user()->role =="user"){
                 $users = User::where('id',Auth::user()->id)->get();
                }
                return view('view_ticket',compact('ticket','ticketComments','users')); 
            }

            
            /**
             * Store Ticket Comment.
             */
            public function storeTicketComment(Request $request)
            {
                try{
                    $validator = Validator::make($request->all(), [
                        'ticket_id' => 'required',
                        'comments' => 'required|max:255',
                    ]);
                    if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                    }

                    $userId = Auth::user()->id;
                    $commentData = ['user_id'=>$userId,'ticket_id'=>$request->ticket_id,'comments'=>$request->comments];
                    TicketComments::create($commentData);
                    return redirect()->back()->with('success','Submitted Successfully');
                }catch(\Exception $e){
                    return redirect()->back()->with('error', $e->getMessage());   
                }        
            }


            /**
             * Delete Ticket.
             */
            public function deleteTicket($ticketId)
            {   
                try{
                    $ticket = Ticket::where('id',$ticketId)->update(['is_delete'=>1]);
                    return redirect()->route('home')->with('success','Ticket Deleted Successfully');   
                }catch(\Exception $e){
                    return redirect()->back()->with('error', $e->getMessage());   
                }    
            }


            /**
             * Restore Ticket.
             */
            public function restoreTicket(Request $request)
            {   
                try{
                    $ticketId = $request->restore_ticket_id;
                    Ticket::where('id',$ticketId)->update(['is_delete'=>0]);
                    return redirect()->route('home')->with('success','Ticket Restored Successfully');
                }catch(\Exception $e){
                    return redirect()->back()->with('error', $e->getMessage());   
                }      
            }

    }
