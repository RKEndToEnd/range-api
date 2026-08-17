<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Association\StoreAssociationRequest;
use App\Http\Requests\Association\UpdateAssociationRequest;
use App\Http\Resources\AssociationResource;
use App\Models\Association;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AssociationController extends Controller
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
    ): AnonymousResourceCollection {
        return AssociationResource::collection(
            $organization->associations()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAssociationRequest $request
     * @param Organization $organization
     *
     * @return JsonResponse
     */
    public function store(
        StoreAssociationRequest $request,
        Organization $organization
    ): JsonResponse {
        $association = $organization->associations()->create(
            $request->validated()
        );

        $association->refresh();

        return (new AssociationResource($association))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @param Association $association
     *
     * @return AssociationResource
     */
    public function show(
        Organization $organization,
        Association $association
    ): AssociationResource {
        return new AssociationResource($association);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAssociationRequest $request
     * @param Organization $organization
     * @param Association $association
     *
     * @return AssociationResource
     */
    public function update(
        UpdateAssociationRequest $request,
        Organization $organization,
        Association $association
    ): AssociationResource {
        $association->update(
            $request->validated()
        );

        return new AssociationResource($association);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     * @param Association $association
     *
     * @return Response
     */
    public function destroy(
        Organization $organization,
        Association $association
    ): Response {
        $association->delete();

        return response()->noContent();
    }
}
