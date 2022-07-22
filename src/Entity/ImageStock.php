<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'method' => 'GET',
            'openapi_context' => [
                'parameters' => [
                    [
                        'name' => 'resource_type',
                        'in' => 'query'
                    ]
                ]
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'openapi_context' => [
                "summary" => "hidden"
            ]
        ],
    ],
    paginationEnabled: false
)]
class ImageStock
{
    #[ApiProperty(
        description: 'Directory resource name',
        openapiContext: [
            'Available resources' => 'User'
        ],
    )]
    private string $resource;

    #[ApiProperty(
        description: 'Resource type'
    )]
    private string $resourceType;

    #[ApiProperty(
        identifier: true
    )]
    private string $id;


    /**
     * @param string $resource
     * @param string $resourceType
     */
    public function __construct(string $resource, string $resourceType )
    {
        $this->resource = $resource;
        $this->resourceType = $resourceType;
        $this->id = (string) uniqid();
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

}