<?php

namespace App\Http\Controllers\Api\V1\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\StoreBusRequest;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BusController extends Controller
{
    // ─── Helper: resolve the authenticated operator's company ──────────────────
    private function operatorCompany(Request $request)
    {
        $company = $request->user()->company;

        if (!$company) {
            abort(403, 'You do not have a registered company. Please set up your company profile first.');
        }

        return $company;
    }

    // ─── GET /api/operator/buses ───────────────────────────────────────────────
    /**
     * List all buses belonging to the operator's company.
     */
    public function index(Request $request): JsonResponse
    {
        $company = $this->operatorCompany($request);

        $buses = $company->buses()
            ->latest()
            ->get();

        return response()->json([
            'data' => $buses,
        ]);
    }

    // ─── POST /api/operator/buses ──────────────────────────────────────────────
    /**
     * Add a new bus to the operator's company fleet.
     */
    public function store(StoreBusRequest $request): JsonResponse
    {
        $company = $this->operatorCompany($request);

        $bus = $company->buses()->create([
            'bus_name'    => $request->bus_name,
            'bus_number'  => $request->bus_number,
            'bus_type'    => $request->bus_type,
            'total_seats' => $request->total_seats,
            'status'      => $request->input('status', 'active'),
        ]);

        return response()->json([
            'message' => 'Bus added successfully.',
            'data'    => $bus,
        ], 201);
    }

    // ─── GET /api/operator/buses/{id} ─────────────────────────────────────────
    /**
     * Show a single bus — only if it belongs to the operator's company.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $bus = $this->operatorCompany($request)
            ->buses()
            ->findOrFail($id);

        return response()->json(['data' => $bus]);
    }

    // ─── PUT /api/operator/buses/{id} ─────────────────────────────────────────
    /**
     * Update a bus — operator can only update their own buses.
     */
    public function update(StoreBusRequest $request, int $id): JsonResponse
    {
        $bus = $this->operatorCompany($request)
            ->buses()
            ->findOrFail($id);

        $bus->update([
            'bus_name'    => $request->bus_name,
            'bus_number'  => $request->bus_number,
            'bus_type'    => $request->bus_type,
            'total_seats' => $request->total_seats,
            'status'      => $request->input('status', $bus->status),
        ]);

        return response()->json([
            'message' => 'Bus updated successfully.',
            'data'    => $bus->fresh(),
        ]);
    }

    // ─── DELETE /api/operator/buses/{id} ──────────────────────────────────────
    /**
     * Delete a bus — operator can only delete their own buses.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $bus = $this->operatorCompany($request)
            ->buses()
            ->findOrFail($id);

        $bus->delete();

        return response()->json([
            'message' => 'Bus deleted successfully.',
        ]);
    }
}
