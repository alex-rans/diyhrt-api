<?php
//
//namespace App\Tests;
//
//use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
//
//class ProductTest extends ApiTestCase
//{
////    /** @test */
////    public function Product_GetAllProducts_StatusCode200(): void
////    {
////        $response = static::createClient()->request('GET', '/api/v1/product');
////        $this->assertResponseStatusCodeSame('200', $response->getStatusCode());
////    }
////
////    /** @test */
////    public function Product_GetAllProductsCorrectType_StatusCode200(): void
////    {
////        $response = static::createClient()->request('GET', '/api/v1/product?type=Estradiol Pills');
////        $this->assertResponseStatusCodeSame('200', $response->getStatusCode());
////    }
////
////    /** @test */
////    public function Product_GetAllProductsIncorrectType_StatusCode400(): void
////    {
////        $response = static::createClient()->request('GET', '/api/v1/product?type=gibberish');
////        $this->assertResponseStatusCodeSame('400', $response->getStatusCode());
////    }
////
////    /** @test */
////    public function Product_GetOneProduct_StatusCode200(): void
////    {
////        $response = static::createClient()->request('GET', '/api/v1/product/1');
////        $this->assertResponseStatusCodeSame('200', $response->getStatusCode());
////    }
////
////    /** @test */
////    public function Product_GetOneProductNotExisting_StatusCode404(): void
////    {
////        $response = static::createClient()->request('GET', '/api/v1/product/9999999999');
////        $this->assertResponseStatusCodeSame('404', $response->getStatusCode());
////    }
////
////    /** @test */
////    public function Product_InsertProductWrongAuthenticaion_StatusCode401(): void
////    {
////        //no api token
////        $response = static::createClient()->request(
////            'POST',
////            '/api/v1/product',
////            ['body' => [
////                'name' => 'Test',
////                'url' => 'Test',
////                'type' => 'Estradiol Pills',
////                'supplierId' => 1
////            ]
////            ]);
////        $this->assertResponseStatusCodeSame('401', $response->getStatusCode());
////
////        //wrong api token
////        $response = static::createClient()->request(
////            'POST',
////            '/api/v1/product',
////            [
////                'headers' => [
////                    'X-AUTH-TOKEN' => 'random bullshit go'
////                ],
////                'body' => [
////                    'name' => 'Test',
////                    'url' => 'Test',
////                    'type' => 'Estradiol Pills',
////                    'supplierId' => 1
////                ]
////            ]);
////        $this->assertResponseStatusCodeSame('401', $response->getStatusCode());
////    }
////
//    /** @test */
//    public function Product_InsertProductMissingFieldName_StatusCode400(): void
//    {
//        $response = static::createClient()->request(
//            'POST',
//            '/api/v1/product',
//            [
//                'headers' => [
//                    'X-AUTH-TOKEN' => 'hWbwJvFt11SYgUlQwSkGCQI9xyqOoMZ54BFpNzAQG7d2cl4OMgK8GZssLduhCVez'
//                ],
//                'body' => [
//                    'url' => 'Test',
//                    'type' => 'Estradiol Pills',
//                    'supplierId' => 1
//                ]
//            ]);
//        $this->assertResponseStatusCodeSame('400', $response->getStatusCode());
//    }
//
//    /** @test */
//    public function Product_InsertProductMissingUrl_StatusCode400(): void
//    {
//        //no url
//        $response = static::createClient()->request(
//            'POST',
//            '/api/v1/product',
//            [
//                'headers' => [
//                    'X-AUTH-TOKEN' => 'hWbwJvFt11SYgUlQwSkGCQI9xyqOoMZ54BFpNzAQG7d2cl4OMgK8GZssLduhCVez'
//                ],
//                'body' => [
//                    'name' => 'Test',
//                    'type' => 'Estradiol Pills',
//                    'supplierId' => 1
//                ]
//            ]);
//        $this->assertResponseStatusCodeSame('400', $response->getStatusCode());
//    }
//
//    /** @test */
//    public function Product_InsertProductMissingType_StatusCode200(): void
//    {
//        //no type
//        $response = static::createClient()->request(
//            'POST',
//            '/api/v1/product',
//            [
//                'headers' => [
//                    'X-AUTH-TOKEN' => 'hWbwJvFt11SYgUlQwSkGCQI9xyqOoMZ54BFpNzAQG7d2cl4OMgK8GZssLduhCVez'
//                ],
//                'body' => [
//                    'name' => 'Test',
//                    'url' => 'Test',
//                    'supplierId' => 1
//                ]
//            ]);
//        $this->assertResponseStatusCodeSame('400', $response->getStatusCode());
//    }
//
//    /** @test */
//    public function Product_InsertProductMissingSupplierId_StatusCode400(): void
//    {
//        //no supplierid
//        $response = static::createClient()->request(
//            'POST',
//            '/api/v1/product',
//            [
//                'headers' => [
//                    'X-AUTH-TOKEN' => 'hWbwJvFt11SYgUlQwSkGCQI9xyqOoMZ54BFpNzAQG7d2cl4OMgK8GZssLduhCVez'
//                ],
//                'body' => [
//                    'name' => 'Test',
//                    'url' => 'Test',
//                    'type' => 'Estradiol Pills',
//                ]
//            ]);
//        $this->assertResponseStatusCodeSame('400', $response->getStatusCode());
//    }
//
//    /** @test */
//    public function Product_InsertProductWrongTYpe_StatusCode400(): void
//    {
//        //wrong type
//        $response = static::createClient()->request(
//            'POST',
//            '/api/v1/product',
//            [
//                'headers' => [
//                    'X-AUTH-TOKEN' => 'hWbwJvFt11SYgUlQwSkGCQI9xyqOoMZ54BFpNzAQG7d2cl4OMgK8GZssLduhCVez'
//                ],
//                'body' => [
//                    'name' => 'Test',
//                    'url' => 'Test',
//                    'type' => 'random bullshit go',
//                    'supplierId' => 1
//                ]
//            ]);
//        $this->assertResponseStatusCodeSame('400', $response->getStatusCode());
//    }
//
//    /** @test */
//    public function Product_InserProduct_StatusCode200(): void
//    {
//        $response = static::createClient()->request(
//            'POST',
//            '/api/v1/product',
//            [
//                'headers' => [
//                    'X-AUTH-TOKEN' => 'hWbwJvFt11SYgUlQwSkGCQI9xyqOoMZ54BFpNzAQG7d2cl4OMgK8GZssLduhCVez',
//                    'Content-Type' => 'application/x-www-form-urlencoded',
//                ],
//                'body' => ['parameter1' => 'value1'],
//            ]);
//        $response->cancel();
//        $this->assertResponseIsSuccessful();
//    }
//
//}
// you know what fuck unit testing
