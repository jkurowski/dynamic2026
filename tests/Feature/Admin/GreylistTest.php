<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\User;

class GreylistTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->withoutExceptionHandling();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_investment_index_method()
    {
        $response = $this->actingAs($this->user)->get(route('admin.developro.greylist.index'));
        $response->assertStatus(200);
    }

}
