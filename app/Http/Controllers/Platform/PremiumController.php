<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $companies = Company::query()

            ->orderByDesc('subscription_plan')

            ->orderBy('name')

            ->paginate(15);

        return view(

            'platform.premium.index',

            [

                'companies' => $companies,

                'plans' => config('plans'),

            ]

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Update Subscription
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Company $company
    ) {

        $request->validate([

            'plan' => [

                'required',

                'in:Premium Go,Premium Plus',

            ],

            'duration' => [

                'required',

                'in:1_month,3_months,12_months',

            ],

        ]);

        $this->companyService->updateSubscription(

            $company,

            $request->plan,

            $request->duration

        );

        return back()->with(

            'success',

            'Subscription berhasil diperbarui.'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Subscription
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Company $company
    ) {

        $this->companyService->cancelSubscription(

            $company

        );

        return back()->with(

            'success',

            'Subscription dibatalkan, company dikembalikan ke Free plan.'

        );

    }
}