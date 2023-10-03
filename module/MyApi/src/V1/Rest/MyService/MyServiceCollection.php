<?php

namespace MyApi\V1\Rest\MyService;

use Laminas\Paginator\Paginator;
use OpenApi\Attributes as OA;

#[OA\Schema(
    allOf: [
        new OA\Schema(ref: "#/components/schemas/HalCollectionLinks"),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: "_embedded",
                    properties: [
                        new OA\Property(
                            property: "myService",
                            type: "array",
                            items: new OA\Items("#/components/schemas/MyServiceEntity")
                        )
                    ],
                    type: "object"
                )
            ]
        ),
        new OA\Schema(ref: "#/components/schemas/HalCollectionCounts"),
    ]
)]
class MyServiceCollection extends Paginator
{
}
