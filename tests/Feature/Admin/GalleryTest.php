<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class GalleryTest extends TestCase
{

    public function test_gallery_route()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('admin/gallery');
        $response->assertStatus(200);
    }

//    public function test_gallery_create_method()
//    {
//        $this->withoutExceptionHandling();
//        $user = User::factory()->create();
//
//        $response = $this->actingAs($user)->get('admin/gallery/create');
//        $response->assertStatus(200);
//    }
//
//    public function test_gallery_store_method()
//    {
//        $this->withoutExceptionHandling();
//        $user = User::factory()->create();
//
//        $response = $this->actingAs($user)->post('admin/gallery', [
//            'status' => 0,
//            'name' => 'Testowa galeria',
//            'text' => '',
//            'slug' => ''
//        ]);
//        $response->assertRedirect('admin/gallery');
//    }

}
