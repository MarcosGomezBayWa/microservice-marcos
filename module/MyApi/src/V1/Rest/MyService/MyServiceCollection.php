<?php

namespace MyApi\V1\Rest\MyService;

use Laminas\Paginator\Paginator;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/HalCollectionLinks"),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="_embedded",
 *                 type="object",
 *                 @OA\Property(
 *                     property="myService",
 *                     type="array",
 *                     @OA\Items(ref="#/components/schemas/MyServiceEntity")
 *                 )
 *             )
 *         ),
 *         @OA\Schema(ref="#/components/schemas/HalCollectionCounts")
 *     }
 * )
 */
class MyServiceCollection extends Paginator
{
}
