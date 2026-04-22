<?php

namespace Tests\Feature;

use App\Models\FrameDesign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrameDesignControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_frames_index(): void
    {
        $response = $this->getJson(route('frames.index'));
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_frame(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('frames.store'), [
            'name' => 'My Test Frame',
            'design_json' => $this->validDesignJson(),
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('frame_designs', [
            'name' => 'My Test Frame',
            'user_id' => $user->id,
            'is_template' => false,
        ]);
    }

    public function test_user_can_update_own_frame(): void
    {
        $user = User::factory()->create();
        $frame = FrameDesign::create([
            'user_id' => $user->id,
            'name' => 'Old Name',
            'design_json' => $this->validDesignJson(),
            'is_template' => false,
        ]);

        $payload = $this->validDesignJson();
        $payload['layers'][] = [
            'type' => 'text',
            'x' => 100,
            'y' => 100,
            'text' => 'Updated',
            'opacity' => 1,
            'z_index' => 1,
        ];

        $response = $this->actingAs($user)->putJson(route('frames.update', $frame), [
            'name' => 'Updated Name',
            'design_json' => $payload,
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('frame_designs', [
            'id' => $frame->id,
            'name' => 'Updated Name',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_frame(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $frame = FrameDesign::create([
            'user_id' => $owner->id,
            'name' => 'Owners Frame',
            'design_json' => $this->validDesignJson(),
            'is_template' => false,
        ]);

        $response = $this->actingAs($other)->deleteJson(route('frames.destroy', $frame));
        $response->assertForbidden();

        $this->assertDatabaseHas('frame_designs', ['id' => $frame->id]);
    }

    public function test_template_frame_is_visible_to_authenticated_user(): void
    {
        $user = User::factory()->create();
        $template = FrameDesign::create([
            'user_id' => null,
            'name' => 'System Template',
            'design_json' => $this->validDesignJson(),
            'is_template' => true,
        ]);

        $response = $this->actingAs($user)->getJson(route('frames.show', $template));
        $response->assertOk()->assertJsonPath('id', $template->id);
    }

    public function test_user_can_delete_all_own_custom_frames_only(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $ownedFrame = FrameDesign::create([
            'user_id' => $user->id,
            'name' => 'Owned frame',
            'design_json' => $this->validDesignJson(),
            'is_template' => false,
        ]);

        $templateFrame = FrameDesign::create([
            'user_id' => null,
            'name' => 'Template frame',
            'design_json' => $this->validDesignJson(),
            'is_template' => true,
        ]);

        $otherUsersFrame = FrameDesign::create([
            'user_id' => $other->id,
            'name' => 'Other frame',
            'design_json' => $this->validDesignJson(),
            'is_template' => false,
        ]);

        $response = $this->actingAs($user)->deleteJson(route('frames.destroy-all'));

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('deleted_count', 1);

        $this->assertDatabaseMissing('frame_designs', ['id' => $ownedFrame->id]);
        $this->assertDatabaseHas('frame_designs', ['id' => $templateFrame->id]);
        $this->assertDatabaseHas('frame_designs', ['id' => $otherUsersFrame->id]);
    }

    private function validDesignJson(): array
    {
        return [
            'version' => 1,
            'canvas_width' => 400,
            'canvas_height' => 500,
            'background' => '#ffffff',
            'qr_zone' => [
                'x_pct' => 5,
                'y_pct' => 4,
                'w_pct' => 90,
                'h_pct' => 72,
            ],
            'layers' => [
                [
                    'type' => 'rect',
                    'x' => 20,
                    'y' => 20,
                    'width' => 360,
                    'height' => 360,
                    'opacity' => 1,
                    'z_index' => 0,
                ],
            ],
        ];
    }
}
