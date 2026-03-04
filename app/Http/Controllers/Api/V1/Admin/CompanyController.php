<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\UpdateCompanyStatusRequest;

class CompanyController extends Controller
{
    /**
     * Get all companies.
     */
    public function index()
    {
        $companies = Company::with('user')->latest()->paginate(15);
        
        return response()->json($companies);
    }

    /**
     * Approve or block a company.
     */
    public function updateStatus(UpdateCompanyStatusRequest $request, string $id)
    {
        $company = Company::findOrFail($id);
        
        $validated = $request->validated();
        $company->update([
            'status' => $validated['status']
        ]);
        
        return response()->json([
            'message' => 'Company status updated',
            'company' => $company
        ]);
    }
}
