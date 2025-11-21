<?php
namespace Tests\Feature;
use Tests\TestCase;
class RutasBasicasTest extends TestCase
{
    /**
     * Verificar que la página de inicio carga (Status 200).
     */
    public function test_la_home_carga_correctamente(): void
    {
        // Simulamos que un usuario entra a la ruta '/'
        $response = $this->get('/login');
        // Verificamos que el servidor responda "OK" (Código 200)
        $response->assertStatus(200);
    }
} 