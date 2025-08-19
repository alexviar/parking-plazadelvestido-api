<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Ticket::class);

        $query = Ticket::query();

        $request->whenFilled('search', function ($search) use ($query) {
            $query->where('folio', $search);
        });

        $request->whenFilled('filter.date', function ($date) use ($query) {
            $query->whereDate('exit_time', $date);
        });

        return $query->paginate($request->page_size);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);

        $payload = $request->validate([
            'code' => 'required|string', //|unique:tickets,code',
            'entry_time' => 'required|date',
            'exit_time' => 'nullable|date',
            'duration' => 'nullable|integer',
            'amount' => 'nullable|numeric',
            'folio' => 'nullable|string'
        ]);

        $ticket = Ticket::create($payload);
        return $ticket;
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        return $ticket;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $payload = $request->validate([
            'code' => 'sometimes|string', //|unique:tickets,code,' . $ticket->id,
            'entry_time' => 'sometimes|date',
            'exit_time' => 'nullable|date',
            'duration' => 'nullable|integer',
            'amount' => 'nullable|numeric',
            'folio' => 'nullable|string',
        ]);

        $ticket->update($payload);
        return $ticket;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $ticket->delete();
        return response()->noContent();
    }
}
