<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadCompanyLogoTest extends TestCase
{
    use RefreshDatabase;

    
    /** @test */
    public function a_user_can_upload_a_company_logo()
    {
        Storage::fake('public');

        $response = $this->post('/images/upload', [
            'image' => UploadedFile::fake()->image('logo.jpg', 100, 100)
        ]);


        $response->assertStatus(200);
        Storage::disk('public')->assertExists($response->json()['path']);
    } 

    /** @test */
    public function a_user_cannot_upload_an_empty_logo()
    {
        $this->withExceptionHandling();

        $response = $this->postJson('/images/upload', []);
        
        $response->assertStatus(422);
    }

    /** @test */
    public function a_user_cannot_upload_an_image_that_is_too_small()
    {
        $this->withExceptionHandling();

        Storage::fake('public');

        $image = UploadedFile::fake()->image('logo.jpg', 10, 10);
        $response = $this->postJson('/images/upload', [
            'image' => $image
        ]);

        $response->assertStatus(422);
        Storage::disk('public')->assertMissing("images/{$image->hashName()}");
    }

    /** @test */
    public function a_user_cannot_upload_a_non_image_file()
    {
        $this->withExceptionHandling();

        Storage::fake('public');

        $response = $this->postJson('/images/upload', [
            'image' => UploadedFile::fake()->create('logo.pdf')
        ]);

        $response->assertStatus(422);
    }

}
