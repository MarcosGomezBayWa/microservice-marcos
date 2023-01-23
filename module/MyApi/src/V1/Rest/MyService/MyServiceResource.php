<?php

namespace MyApi\V1\Rest\MyService;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\Stdlib\Parameters;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="My Service",
 *     description="My Service Description"
 * )
 */
class MyServiceResource extends AbstractResourceListener
{
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array|Parameters $params
     * @return ApiProblem|mixed
     *
     * @OA\Get(
     *     path="/my-service",
     *     tags={"My Service"},
     *     summary="My Service description.",
     *     @OA\Response(
     *         response="200",
     *         description="The list of My Service.",
     *         @OA\MediaType(
     *             mediaType="application/hal+json",
     *             @OA\Schema(ref="#/components/schemas/MyServiceCollection")
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description=
     *         "The user isn't authorized to access this resource (invalid or expired access token).",
     *         @OA\MediaType(mediaType="application/problem+json")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="The user doesn't have the permission to access this resource.",
     *         @OA\MediaType(mediaType="application/problem+json")
     *     ),
     *     @OA\Response(
     *         response="406",
     *         description="Content not acceptable.",
     *         @OA\MediaType(mediaType="application/problem+json")
     *     ),
     *     @OA\Response(
     *         response="415",
     *         description="Media Type unsupported.",
     *         @OA\MediaType(mediaType="application/problem+json")
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error.",
     *         @OA\MediaType(mediaType="application/problem+json")
     *     )
     * )
     */
    public function fetchAll($params = [])
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
