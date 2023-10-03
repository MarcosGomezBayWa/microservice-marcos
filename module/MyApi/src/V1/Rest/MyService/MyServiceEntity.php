<?php

namespace MyApi\V1\Rest\MyService;

use OpenApi\Attributes as OA;
use Doctrine\ORM\Mapping as ORM;

#[OA\Schema]
#[ORM\Entity]
class MyServiceEntity
{
}
