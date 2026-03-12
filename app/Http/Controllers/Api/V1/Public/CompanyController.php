<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Return all active (approved) companies for public display.
     */
    public function index()
    {
        $companies = Company::where('status', 'approved')
            ->select('id', 'company_name', 'logo')
            ->orderBy('company_name')
            ->get();

        return response()->json($companies);
    }
}
