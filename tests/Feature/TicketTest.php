<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

//Models
use App\Models\User;
use App\Models\Ticket;


class TicketTest extends TestCase
{
    
    //CREATE TICKET
    public function test_create_ticket()
    {
        //create a user for ticket
        $user = User::factory()->create([
            'role' => 'user',
            'password' => '$2y$10$5Fd0LQA.9BmpNhW0ZwKoIurqnaJwPAUhSXwdbbnxIrQk/.WAS1ZAS']);
        
        //create ticket
        $response = $this->post('/store-ticket', [
            'received_date' => '2023-01-22',
            'user_id' => $user->id,
            'problem_description' => 'Unit test data',
            'additional_notes' => 'Unit test data',
        ]);

        $this->assertDatabaseHas('ticket', [
            'received_date' => '2023-01-22',
            'user_id' => $user->id,
            'problem_description' => 'Unit test data',
            'additional_notes' => 'Unit test data',
        ]);
        $response->assertRedirect('home');
    }

    //CREATE TICKET WITH WRONG INPUT 1
    public function test_create_ticket_with_future_received_date()
    {
        //create a user for ticket
        $user = User::factory()->create([
            'role' => 'user',
            'password' => '$2y$10$5Fd0LQA.9BmpNhW0ZwKoIurqnaJwPAUhSXwdbbnxIrQk/.WAS1ZAS']);
        
        //create ticket
        $response = $this->post('/store-ticket', [
            'received_date' => '2090-01-22',
            'user_id' => $user->id,
            'problem_description' => 'Unit test data',
            'additional_notes' => 'Unit test data',
        ]);
       $response->assertSessionHasErrors('received_date');
    }

    //CREATE TICKET WITH WRONG INPUT 2
    public function test_create_ticket_without_user_id()
    {
        //create a user for ticket
        $user = User::factory()->create([
            'role' => 'user',
            'password' => '$2y$10$5Fd0LQA.9BmpNhW0ZwKoIurqnaJwPAUhSXwdbbnxIrQk/.WAS1ZAS']);
        
        //create ticket
        $response = $this->post('/store-ticket', [
            'received_date' => '2023-01-22',
            'problem_description' => 'Unit test data',
            'additional_notes' => 'Unit test data',
        ]);
       $response->assertSessionHasErrors('user_id');
    }

    //CREATE TICKET WITH WRONG INPUT 3
    public function test_create_ticket_without_problem_description()
    {
        //create a user for ticket
        $user = User::factory()->create([
            'role' => 'user',
            'password' => '$2y$10$5Fd0LQA.9BmpNhW0ZwKoIurqnaJwPAUhSXwdbbnxIrQk/.WAS1ZAS']);
        
        //create ticket
        $response = $this->post('/store-ticket', [
            'user_id' => $user->id,
            'received_date' => '2023-01-22',
            'additional_notes' => 'Unit test data',
        ]);
       $response->assertSessionHasErrors('problem_description');
    }

    //CREATE TICKET WITH WRONG INPUT 4
    public function test_create_ticket_with_more_additional_notes()
    {
        //create a user for ticket
        $user = User::factory()->create([
            'role' => 'user',
            'password' => '$2y$10$5Fd0LQA.9BmpNhW0ZwKoIurqnaJwPAUhSXwdbbnxIrQk/.WAS1ZAS']);

        $additionalNotes = Str::random(400);
        
        //create ticket
        $response = $this->post('/store-ticket', [
            'user_id' => $user->id,
            'received_date' => '2023-01-22',
            'additional_notes' => $additionalNotes,
            'problem_description' => 'Unit test data',
        ]);
       $response->assertSessionHasErrors('additional_notes');
    }


    //UPDATE TICKET
    public function test_update_ticket()
    {
        //create a user for ticket
        $user = User::factory()->create([
            'role' => 'user',
            'password' => '$2y$10$5Fd0LQA.9BmpNhW0ZwKoIurqnaJwPAUhSXwdbbnxIrQk/.WAS1ZAS']);
        
        //create ticket 
        $response = $this->post('/store-ticket', [
            'user_id' => $user->id,
            'received_date' => '2023-01-25',
            'additional_notes' => 'test-update',
            'problem_description' => 'Unit test data',
        ]);

        $findTicket = Ticket::where('user_id',$user->id)->first();
        //update ticket
        $response = $this->patch('/update/ticket', [
            'id'=>$findTicket->id,
            'user_id' => $user->id,
            'received_date' => '2023-01-25',
            'additional_notes' => 'Ticket is updated with test case',
            'problem_description' => 'Updated with test case',
        ]);
        $response->assertRedirect('home');

        $this->assertDatabaseHas('ticket', [
            'id'=>$findTicket->id,
            'user_id' => $user->id,
        ]);
        
    }

    //DELETE TICKET
    public function test_delete_ticket()
    {
        //create a user for ticket
        $user = User::factory()->create([
            'role' => 'user',
            'password' => '$2y$10$5Fd0LQA.9BmpNhW0ZwKoIurqnaJwPAUhSXwdbbnxIrQk/.WAS1ZAS']);
        
        //create ticket 
        $response = $this->post('/store-ticket', [
            'user_id' => $user->id,
            'received_date' => '2023-01-25',
            'additional_notes' => 'test-update',
            'problem_description' => 'Unit test data',
        ]);

        $findTicket = Ticket::where('user_id',$user->id)->first();
        //update ticket
        $response = $this->delete('/delete/ticket/'.$findTicket->id, []);
        $response->assertRedirect('home');

        $this->assertDatabaseHas('ticket', [
          'id' => $findTicket->id,
          'is_delete'=>1
        ]); 
    }

    ///etc all positive and negative scenarios....
    
}
