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
    public function it_can_upload_an_image()
    {
        Storage::fake('public');

        $response = $this->post('/images/upload', [
            'image' => UploadedFile::fake()->image('logo.jpg', 100, 100)
        ]);


        $response->assertStatus(200);
        Storage::disk('public')->assertExists($response->json()['path']);
    } 

    /** @test */
    public function the_image_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->postJson('/images/upload', []);
        
        $response->assertStatus(422);
    }

    /** @test */
    public function the_image_must_be_bigger_than_the_minimum_requirements()
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
    public function the_file_must_be_an_image()
    {
        $this->withExceptionHandling();

        $response = $this->postJson('/images/upload', [
            'image' => UploadedFile::fake()->create('logo.pdf')
        ]);

        $response->assertStatus(422);
    }

}
