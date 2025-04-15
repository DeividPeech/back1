<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['queja', 'sugerencia']);
            $table->string('folio')->unique(); 

            $table->text('descripcion'); 
            $table->dateTime('fecha_creacion')->default(now());
            $table->enum('estado', ['pendiente', 'en_proceso', 'resuelto', 'rechazado'])->default('pendiente'); 

            $table->foreignId('departamento_id')->nullable()->references('id')->on('departamentos');


            
            $table->json('creador');

           
            $table->json('historial')->nullable();

            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
