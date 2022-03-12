<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Product;

class ProductTest extends TestCase
{
    
    protected function authenticate()
    {
        $user = User::create([
            'name' => 'test',
            'email' => rand(12345,678910).'test@gmail.com',
            'password' => \Hash::make('secret9874'),
        ]);

        if (!auth()->attempt(['email'=>$user->email, 'password'=>'secret9874'])) {
            return response(['message' => 'Login credentials are invaild']);
        }

        return $accessToken = auth()->user()->createToken('authToken')->accessToken;
    }

    public function test_create_product()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders(['Authorization' => 'Bearer '. $token,])->json('POST','api/product',[
            'name' => 'Test product',
            'sku' => 'test-sku',
            'price' => '200'
        ]);

        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(201);
    }

    public function test_update_product()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders(['Authorization' => 'Bearer '. $token,])->json('PUT','api/product/9',[
            'name' => 'Test product111',
            'sku' => 'test-sku',
            'price' => '300'
        ]);

        \Log::info(1, [$response->getContent()]);
        $response->assertStatus(200);
    }

    public function test_find_product()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','api/product/9');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    public function test_get_all_product()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','api/product');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    public function test_delete_product()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('DELETE','api/product/9');

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
}
