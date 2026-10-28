<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_move_task_from_pending_to_in_progress(): void
    {
        $user = User::factory()->create();


        $project = $user->projects()->create([
            'name' => 'Proyek Tes',
            'description' => 'Proyek untuk pengujian',
        ]);

        $user->projects()->save($project);

        $task = $project->tasks()->create([
            'title' => 'Membuat halaman login',
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('tasks.updateStatus', $task), [
                'status' => 'in_progress',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'TASK_STATUS_UPDATED',
            'subject_id' => $task->id,
        ]);
    }


    public function test_user_cannot_skip_from_pending_to_completed(): void
    {
        $user = User::factory()->create();

        $project = $user->projects()->create([
            'name' => 'Proyek Tes',
            'description' => 'Proyek untuk pengujian',
        ]);

        $task = $project->tasks()->create([
            'title' => 'Membuat halaman login',
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('tasks.updateStatus', $task), [
                'status' => 'completed',
            ]);

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'TASK_STATUS_UPDATED',
            'subject_id' => $task->id,
        ]);
    }


    public function test_user_cannot_update_another_users_task(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = $owner->projects()->create([
            'name' => 'Proyek Milik Owner',
            'description' => 'Proyek pribadi',
        ]);

        $task = $project->tasks()->create([
            'title' => 'Task Rahasia',
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->patch(route('tasks.updateStatus', $task), [
                'status' => 'in_progress',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'TASK_STATUS_UPDATED',
            'subject_id' => $task->id,
        ]);
    }


    public function test_status_change_is_rolled_back_if_activity_log_fails(): void
    {
        $user = User::factory()->create();

        $project = $user->projects()->create([
            'name' => 'Proyek Tes',
            'description' => 'Tes rollback',
        ]);

        $task = $project->tasks()->create([
            'title' => 'Task Tes',
            'status' => 'pending',
        ]);

        DB::statement("
        CREATE TRIGGER fail_activity_log
        BEFORE INSERT ON activity_logs
        BEGIN
            SELECT RAISE(ABORT, 'Simulated activity log failure');
        END;
    ");

        $response = $this
            ->actingAs($user)
            ->patch(route('tasks.updateStatus', $task), [
                'status' => 'in_progress',
            ]);

        $response->assertServerError();

        // Status harus kembali ke nilai awal.
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);

        // Tidak boleh ada log perubahan status yang berhasil tersimpan.
        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'TASK_STATUS_UPDATED',
            'subject_id' => $task->id,
        ]);
    }


    public function test_json_endpoint_rejects_skipping_task_status(): void
    {
        $user = User::factory()->create();

        $project = $user->projects()->create([
            'name' => 'Proyek API',
            'description' => 'Tes endpoint JSON',
        ]);

        $task = $project->tasks()->create([
            'title' => 'Task API',
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson(route('api.tasks.updateStatus', $task), [
                'status' => 'completed',
            ]);

        $response->assertUnprocessable();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'TASK_STATUS_UPDATED',
            'subject_id' => $task->id,
        ]);
    }


    public function test_json_endpoint_forbids_updating_another_users_task(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = $owner->projects()->create([
            'name' => 'Proyek Pemilik',
            'description' => 'Tes keamanan API',
        ]);

        $task = $project->tasks()->create([
            'title' => 'Task Pemilik',
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->patchJson(route('api.tasks.updateStatus', $task), [
                'status' => 'in_progress',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'TASK_STATUS_UPDATED',
            'subject_id' => $task->id,
        ]);
    }
}
