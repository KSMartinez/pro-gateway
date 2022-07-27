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
        identifier: true
    )]
    private string $id;

    #[ApiProperty(
        description: 'Directory resource name',
        openapiContext: [
            'Available resources' => 'User'
        ],
    )]
    private string $resourcePath;

    #[ApiProperty(
        description: 'Resource type'
    )]
    private string $resourceType;

    #[ApiProperty(
        description: 'File name'
    )]
    private string $filename;


    /**
     * @param string $filename
     * @param string $resourcePath
     * @param string $resourceType
     */
    public function __construct(string $filename, string $resourcePath, string $resourceType)
    {
        $this->resourcePath = $resourcePath;
        $this->resourceType = $resourceType;
        $this->filename = $filename;
        $this->id = (string) hash('md5', $this->filename);
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

    /**
     * @return string
     */
    public function getResourceUrl(): string
    {
        return $this->getResourcePath() . $this->getFilename();
    }

    /**
     * @return string
     */
    public function getResourcePath(): string
    {
        return $this->resourcePath;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

}