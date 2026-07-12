<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as AnonymousResourceCollectionAlias;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollectionAlias
     */
    public function index()
    {
        return OrganizationResource::collection(Organization::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @return OrganizationResource
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage
     *
     * @param StoreOrganizationRequest $request
     * @param Organization $organization
     * @return OrganizationResource
     */
    public function update(
        StoreOrganizationRequest $request,
        Organization             $organization
    )
    {
        $organization->update($request->validated());

        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     * @return Response
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return response()->noContent();
    }
}
