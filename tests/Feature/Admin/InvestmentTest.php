<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Models\Investment;
use App\Models\User;

class InvestmentTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.developro.investment.index'));
        $response->assertStatus(200);
    }

    public function test_investment_create_method()
    {
        $response = $this->actingAs($this->user)->get(route('admin.developro.investment.create'));
        $response->assertStatus(200);
    }

    public function test_investment_store_method()
    {
        DB::table('investments')->truncate();

        $response = $this->actingAs($this->user)->post(route('admin.developro.investment.store'), [
            'status' => 1,
            'type' => 2,
            'name' => 'Testowa inwestycja'
        ]);

        $response->assertRedirect('admin/developro/investment');
    }

    public function test_investment_edit_method()
    {
        $investment = Investment::first();

        $response = $this->actingAs($this->user)->get(route('admin.developro.investment.edit', $investment->id));
        $response->assertStatus(200);
    }

    public function test_investment_update_method()
    {
        $investment = Investment::first();

        $response = $this->actingAs($this->user)->put(
        route('admin.developro.investment.update', $investment),
        [
            'name' => 'Testowa inwestycja 1.0',
        ]);

        $investment->refresh();

        $this->assertEquals('Testowa inwestycja 1.0', $investment->name);
        $response->assertRedirect('admin/developro/investment');
    }

    public function test_investment_destroy_method()
    {
        $investment = Investment::first();

        $response = $this->actingAs($this->user)->delete(route('admin.developro.investment.destroy', $investment));
        $response->assertStatus(201)->assertExactJson(['status' => 'deleted']);
    }
}
