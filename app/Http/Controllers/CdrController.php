<?php

namespace App\Http\Controllers;

use App\Builders\CdrBuilder;
use App\Http\Requests\StoreCdrRequest;
use App\Http\Resources\CdrResource;
use App\Models\Cdr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CdrController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCdrRequest $request): Response|JsonResponse
    {
        $validated = $request->validated();
        $evse = $request->user()->evses()->whereRef($validated['evse_uid'])->first();
        if (!$evse) {
            return new Response(null, 404);
        }
        $cdr = $evse->cdrs()->whereRef($validated['id'])->firstOrNew();
        $cdr->evse_id = $evse->id;
        $cdr->ref = $validated['id'];
        $cdr->start_datetime = $validated['start_datetime'];
        $cdr->end_datetime = $validated['end_datetime'];
        $cdr->total_energy = $validated['total_energy'];
        $cdr->total_cost = $validated['total_cost'];
        $cdr->save();

        return new JsonResponse(new CdrResource($cdr));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $ref): Response|JsonResponse
    {
        $cdr = Cdr::query()
            ->tap(fn (CdrBuilder $b) => $b
                ->whereRef($ref)
                ->whereOperatorIs($request->user())
            )
            ->first();
        if ($cdr) {
            return new JsonResponse(new CdrResource($cdr));
        }

        return new Response(null, 401);
    }
}
