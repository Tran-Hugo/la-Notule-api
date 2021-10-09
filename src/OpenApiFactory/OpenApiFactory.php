<?php

namespace App\OpenApi;

use ArrayObject;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
        
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        
        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['bearerAuth'] = new ArrayObject([
            'type'=>'http',
            'scheme'=>'bearer',
            'bearerFormat'=>'JWT'
        ]);
        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'john@doe.fr',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '0000'
                ]
            ]
        ]);
        $schemas['cartItem'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'cart' => [
                    'type' => 'string',
                    'example' => '/api/carts/1',
                ],
                'book' => [
                    'type' => 'string',
                    'example' => '/api/books/1'
                ],
                'quantity' => [
                    'type' => 'integer',
                    'example' => '1'
                ]
            ]
        ]);
        $pathItem = new PathItem(
            post: new Operation(
                operationId:'postApiLogin',
                tags: ['User'],
                requestBody: new RequestBody(
                    content: new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' => [
                        'description' => 'Utilisateur connecté',
                        'content' => [
                            'application/json' => [
                            ]
                        ],
                    ]
                ]
            )
                    );
            $openApi->getPaths()->addPath('/api/login',$pathItem);

        return $openApi;
    }

}