<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tariff::class);
        return Tariff::paginate($request->page_size);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Tariff::class);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|min:0|numeric',
            'threshold' => 'required|min:0|numeric|integer',
        ]);
        $tariff = Tariff::create($validatedData);

        return $tariff;
    }

    /**
     * Display the specified resource.
     */
    public function show(Tariff $tariff)
    {
        $this->authorize('view', $tariff);
        return $tariff;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tariff $tariff)
    {
        $this->authorize('update', $tariff);
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|min:0|numeric',
            'threshold' => 'sometimes|required|min:0|numeric|integer',
        ]);
        $tariff->update($validatedData);

        return $tariff;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tariff $tariff)
    {
        $this->authorize('delete', $tariff);
        $tariff->delete();
        return response()->json(null, 204);
    }
}
