<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Ticket;
use App\Http\Resources\TicketResource;
use App\Http\Requests\TicketRequest;
use App\Enums\UserRoleEnum;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if (Gate::check('is_not_admin_permission')) {
            return TicketResource::collection(
                Ticket::where('user_id','=', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get()
            );
        }

        return TicketResource::collection(Ticket::orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        if (Gate::check('is_admin_permission')) {
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $created_ticket = Ticket::create($request->validated());

        return new TicketResource($created_ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        if (Gate::check('owner_or_admin_ticket_permission', $ticket)) {
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $request, Ticket $ticket)
    {        
        if (Gate::check('owner_or_admin_ticket_permission', $ticket)) {
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $ticket->update($request->validated());

        return new TicketResource($ticket);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {        
        if (Gate::check('is_not_admin_permission')){
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $ticket->delete();

        return response(null, Response::HTTP_NOT_FOUND);
    }
}
