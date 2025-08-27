<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    private function authHeader()
    {
        $token = base64_encode(env('BASIC_USER', 'user').':'.env('BASIC_PASS', 'password'));
        return ['Authorization' => "Basic $token"];
    }


    public function test_requiere_auth()
    {
        $res = $this->getJson('/api/tasks');
        $res->assertStatus(401);
    }

    public function test_crear_y_obtener_tarea()
    {
        $res = $this->postJson('/api/tasks',
            ['title' => 'Test'], $this->authHeader());
        $res->assertStatus(201)->assertJson([ 
            'error' => false,
            'message' => 'Tarea creada exitosamente',
            'data' => [
                'title' => 'Test']
            ]);

        $responseData = $res->json();
        $id = $responseData['data']['id']; 
        $get = $this->getJson("/api/tasks/$id", $this->authHeader());
        $get->assertOk()->assertJson([
            'error' => false,
            'message' => 'Tarea encontrada',
            'data' => [
                'id' => $id
            ]
        ]);
    }

    public function test_actualizar_y_eliminar_tarea()
    {
        $taskResponse = $this->postJson('/api/tasks', ['title' => 'x'], $this->authHeader())->json();
        $taskId = $taskResponse['data']['id']; 

        $update = $this->putJson("/api/tasks/{$taskId}", ['completed' => true], $this->authHeader());
        $update->assertStatus(201)->assertJson([
            'error' => false,
            'message' => 'Tarea actualizada exitosamente',
            'data' => [
                'completed' => true
            ]
        ]);

        $del = $this->deleteJson("/api/tasks/{$taskId}", [], $this->authHeader());
        $del->assertNoContent();
    }
}
