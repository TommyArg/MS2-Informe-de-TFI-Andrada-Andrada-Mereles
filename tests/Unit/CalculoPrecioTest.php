<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;
class CalculoPrecioTest extends TestCase
{
    /*Un test para verificar que el IVA se calcula bien.*/
    public function test_calculo_de_iva_es_correcto(): void
    {
        // 1. Preparación (Arrange)
        $precioNeto = 100;
        $porcentajeIva = 0.21; // 21%
        // 2. Acción (Act)
        $precioFinal = $precioNeto * (1 + $porcentajeIva);
        // 3. Verificación (Assert)
        $this->assertEquals(121, $precioFinal);
    }
}