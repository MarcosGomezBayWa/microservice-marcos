<?php

namespace MyApi\V1\Rest\MyService;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\Paginator\Adapter\ArrayAdapter;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "My Service",
    description: "My Service Description"
)]
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
     * @param  array $params
     * @return ApiProblem|mixed
     */
    #[OA\Get(
        path: "/my-service",
        summary: "My Service description.",
        tags: ["My Service"],
        responses: [
            new OA\Response(
                response: "200",
                description: "The list of My Service.",
                content: new OA\MediaType(
                    mediaType: "application/hal+json",
                    schema: new OA\Schema(ref: "#/components/schemas/MyServiceCollection")
                )
            ),
            new OA\Response(
                response: "401",
                description: "The user isn't authorized to access this resource (invalid or expired access token).",
                content: new OA\MediaType(
                    mediaType: "application/problem+json"
                )
            ),
            new OA\Response(
                response: "403",
                description: "The user doesn't have the permission to access this resource.",
                content: new OA\MediaType(
                    mediaType: "application/problem+json"
                )
            ),
            new OA\Response(
                response: "406",
                description: "Content not acceptable.",
                content: new OA\MediaType(
                    mediaType: "application/problem+json"
                )
            ),
            new OA\Response(
                response: "415",
                description: "Media Type unsupported.",
                content: new OA\MediaType(
                    mediaType: "application/problem+json"
                )
            ),
            new OA\Response(
                response: "500",
                description: "Internal server error.",
                content: new OA\MediaType(
                    mediaType: "application/problem+json"
                )
            )
        ]
    )]
    public function fetchAll($params = [])
    {
        return new MyServiceCollection(new ArrayAdapter([]));
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
