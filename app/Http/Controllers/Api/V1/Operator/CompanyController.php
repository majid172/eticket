<?php

namespace App\Http\Controllers\Api\V1\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\StoreCompanyRequest;
use App\Http\Requests\Operator\UpdateCompanyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // GET /api/operator/company
    public function show(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        if (!$company) {
            return response()->json(['data' => null, 'has_company' => false]);
        }

        return response()->json(['data' => $company, 'has_company' => true]);
    }

    // POST /api/operator/company
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $request->user()->company()->create($request->validated());

        return response()->json([
            'message' => 'Company profile created successfully.',
            'data'    => $company,
        ], 201);
    }

    // PUT /api/operator/company
    public function update(UpdateCompanyRequest $request): JsonResponse
    {
        $company = $request->user()->company;

        $company->update($request->validated());

        return response()->json([
            'message' => 'Company profile updated successfully.',
            'data'    => $company->fresh(),
        ]);
    }
}
