<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClubRequest;
use App\Http\Requests\UpdateClubRequest;
use App\Http\Resources\ClubResource;
use App\Models\Club;
use App\Models\Organization;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Organization $organization
     *
     * @return AnonymousResourceCollection
     */
    public function index(
        Organization $organization
    ): AnonymousResourceCollection
    {
        return ClubResource::collection(
            $organization->clubs()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClubRequest $request
     * @param Organization $organization
     *
     * @return ClubResource
     */
    public function store(
        StoreClubRequest $request,
        Organization     $organization
    ): ClubResource
    {
        $club = $organization->clubs()->create(
            $request->validated()
        );

        $club->refresh();

        return new ClubResource($club);
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @param Club $club
     *
     * @return ClubResource
     */
    public function show(
        Organization $organization,
        Club         $club
    ): ClubResource
    {
        return new ClubResource($club);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClubRequest $request
     * @param Organization $organization
     * @param Club $club
     *
     * @return ClubResource
     */
    public function update(
        UpdateClubRequest $request,
        Organization      $organization,
        Club              $club
    ): ClubResource
    {
        $club->update(
            $request->validated()
        );

        return new ClubResource($club);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     * @param Club $club
     *
     * @return Response
     */
    public function destroy(
        Organization $organization,
        Club         $club
    ): Response
    {
        $club->delete();

        return response()->noContent();
    }
}
