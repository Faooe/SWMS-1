<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Platform\UpdateSubscriptionRequest;
use App\Http\Resources\Platform\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;

/**
 * Versi API dari App\Http\Controllers\Platform\PremiumController (web).
 * Dipakai oleh PremiumScreen di Flutter -- ganti plan & batalkan
 * subscription sebuah company.
 */
class PremiumController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    /**
     * Ubah plan subscription company (mis. dari Free ke Premium Go).
     */
    public function update(
        UpdateSubscriptionRequest $request,
        Company $company
    ): JsonResponse {

        $company = $this->companyService->updateSubscription(

            $company,

            $request->plan,

            $request->duration

        );

        return ResponseHelper::success(

            new CompanyResource($company),

            'Subscription berhasil diperbarui.'

        );
    }

    /**
     * Batalkan subscription -- company dikembalikan ke plan Free.
     */
    public function cancel(Company $company): JsonResponse
    {
        $company = $this->companyService->cancelSubscription(
            $company
        );

        return ResponseHelper::success(

            new CompanyResource($company),

            'Subscription dibatalkan, company dikembalikan ke Free plan.'

        );
    }
}
