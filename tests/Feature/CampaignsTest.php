<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;
use \Inertia\Testing\AssertableInertia;

class CampaignsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Campaign::factory()->createMany([
            [
                'title' => 'Campaign 1',
                'description' => 'Description 1',
                'start_date' => '2023-12-01',
                'end_date' =>  '2023-12-05',
                'amount' => 100,
            ], [
                'title' => 'Campaign 2',
                'description' => 'Description 2',
                'start_date' => '2023-12-02',
                'end_date' =>  '2023-12-06',
                'amount' => 200,
            ]
        ]);
    }

    public function test_can_view_campaigns()
    {
        $this->get('/campaigns')
            ->assertInertia(fn (AssertableInertia $assert) => $assert
                ->component('Campaigns/Index')
                ->has('campaigns.data', 2)
                ->has('campaigns.data.0', fn (AssertableInertia $assert) => $assert
                    ->where('id', 1)
                    ->where('title', 'Campaign 1')
                    ->where('description', 'Description 1')
                    ->where('start_date', '2023-12-01')
                    ->where('end_date', '2023-12-05')
                    ->where('amount', 100)
                    ->where('deleted_at', null)
                )
                ->has('campaigns.data.1', fn (AssertableInertia $assert) => $assert
                    ->where('id', 2)
                    ->where('title', 'Campaign 2')
                    ->where('description', 'Description 2')
                    ->where('start_date', '2023-12-02')
                    ->where('end_date', '2023-12-06')
                    ->where('amount', 200)
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_can_view_create_campaigns()
    {
        $this->get('/campaigns/create')
            ->assertInertia(fn (AssertableInertia $assert) => $assert
                ->component('Campaigns/Create')
            );
    }

    public function test_can_view_edit_campaigns()
    {
        $this->get('campaigns/1/edit')
            ->assertInertia(fn (AssertableInertia $assert) => $assert
                ->component('Campaigns/Edit')
                ->has('campaign', fn (AssertableInertia $assert) => $assert
                    ->where('id', 1)
                    ->where('title', 'Campaign 1')
                    ->where('description', 'Description 1')
                    ->where('start_date', '2023-12-01')
                    ->where('end_date', '2023-12-05')
                    ->where('amount', 100)
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_can_update_campaigns()
    {
        $updateData =  [
            'title' => 'Campaign 1 u',
            'description' => 'Description 1 u',
            'start_date' => '2023-12-03',
            'end_date' =>  '2023-12-07',
            'amount' => 101,
        ];
        $this->put('campaigns/1',$updateData)->assertRedirect('campaigns');
        $this->assertDatabaseHas('campaigns', [...$updateData, 'id' => 1]);
    }

    public function test_can_create_campaigns()
    {
        $createData =  [
            'title' => 'Campaign 3',
            'description' => 'Description 3',
            'start_date' => '2023-12-10',
            'end_date' =>  '2023-12-12',
            'amount' => 102,
        ];
        $this->post('campaigns',$createData)->assertRedirect('campaigns');
        $this->assertDatabaseHas('campaigns', $createData);
    }

    public function test_can_delete_campaigns()
    {
        $this->delete('campaigns/1')->assertRedirect('campaigns');
        $this->assertSoftDeleted('campaigns', ['id' => 1]);
    }
}
