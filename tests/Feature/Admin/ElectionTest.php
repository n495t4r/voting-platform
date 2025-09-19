<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Election;
use App\Models\Voter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ElectionTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function an_admin_can_view_the_elections_list()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.elections.index'));

        $response->assertOk();
    }

    /** @test */
    public function an_admin_can_view_the_create_election_page()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.elections.create'));

        $response->assertOk();
    }

    /** @test */
    public function an_admin_can_create_a_new_election()
    {
        $electionData = Election::factory()->make()->toArray();

        $response = $this->actingAs($this->adminUser)->post(route('admin.elections.store'), $electionData);

        $response->assertRedirect(route('admin.elections.show', Election::first()));
        $this->assertDatabaseHas('elections', ['title' => $electionData['title']]);
    }

    /** @test */
    public function an_admin_can_view_an_election()
    {
        $election = Election::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.elections.show', $election));

        $response->assertOk();
        $response->assertViewHas('election', $election);
    }

    /** @test */
    public function an_admin_can_open_a_draft_election()
    {
        $election = Election::factory()->draft()->create();

        $response = $this->actingAs($this->adminUser)->post(route('admin.elections.open', $election));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'status' => 'open'
        ]);
    }

    /** @test */
    public function an_admin_can_close_an_open_election()
    {
        $election = Election::factory()->open()->create();

        $response = $this->actingAs($this->adminUser)->post(route('admin.elections.close', $election));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'status' => 'closed'
        ]);
    }

    /** @test */
    public function an_admin_can_view_results_for_a_closed_election()
    {
        $election = Election::factory()->closed()->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.elections.results', $election));

        $response->assertOk();
        $response->assertViewHas('election', $election);
        $response->assertViewHas('results');
    }

    /** @test */
    public function an_admin_can_export_results_as_a_csv()
    {
        $election = Election::factory()->closed()->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.elections.export-results', $election));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="election-'.$election->slug.'-results.csv"');
    }
}
