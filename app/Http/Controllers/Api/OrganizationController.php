<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return OrganizationResource::collection(
            Organization::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrganizationRequest $request
     *
     * @return OrganizationResource
     */
    public function store(StoreOrganizationRequest $request): OrganizationResource
    {
        $organization = Organization::create(
            $request->validated()
        );

        $organization->refresh();

        return new OrganizationResource($organization);
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     *
     * @return OrganizationResource
     */
    public function show(Organization $organization): OrganizationResource
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrganizationRequest $request
     * @param Organization $organization
     *
     * @return OrganizationResource
     */
    public function update(
        UpdateOrganizationRequest $request,
        Organization              $organization
    ): OrganizationResource
    {
        $organization->update(
            $request->validated()
        );

        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     *
     * @return Response
     */
    public function destroy(Organization $organization): Response
    {
        $organization->delete();

        return response()->noContent();
    }
}
