<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\CustomField;
use App\Models\User;

class CustomFieldsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->withoutExceptionHandling();
    }

    /**
     * Index method test for custom_fields table
     *
     * @return void
     */
    public function test_custom_fields_index_method()
    {
        $response = $this->actingAs($this->user)->get(route('admin.crm.custom-fields.index'));
        $response->assertStatus(200);
    }

    /**
     * Store method test for custom_fields table
     *
     * @return void
     */
    public function test_custom_fields_store_method()
    {
        DB::table('custom_fields')->truncate();

        $response = $this->actingAs($this->user)->post(route('admin.crm.custom-fields.store'), [
            'value' => 'Warszawa',
            'group_id' => 1
        ]);

        $response->assertStatus(200);
    }

    /**
     * Destroy method test for custom_fields table
     *
     * @return void
     */
    public function test_custom_fields_destroy_method()
    {
        $entry = CustomField::first();

        $response = $this->actingAs($this->user)->delete(route('admin.crm.custom-fields.destroy', $entry));
        $response->assertStatus(201)->assertExactJson(['status' => 'deleted']);
    }
}
