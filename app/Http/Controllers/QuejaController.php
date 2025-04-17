<?php

namespace App\Http\Controllers;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class QuejaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Solicitud::with('departamento')
        ->when($request->has('departamento_id') && $request->departamento_id !== null, function ($q) use ($request) {
            $q->where('departamento_id', $request->departamento_id);
        })
        ->orderBy('created_at', 'desc');

    $solicitudes = $query->paginate(5);

    return response()->json($solicitudes);
}

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'tipo' => 'required|in:queja,sugerencia',
        'descripcion' => 'required|string',
        'departamento_id' => 'required|exists:departamentos,id',
        'nombre' => 'required|string',
        'email' => 'required|email',
        'telefono' => 'required|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();

    try {
        // Generar un folio único
        do {
            $folio = strtoupper(Str::random(8));
        } while (Solicitud::where('folio', $folio)->exists());

        $solicitud = Solicitud::create([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'departamento_id' => $request->departamento_id,
            'creador' => [
                'nombre' => $request->nombre,
                'email' => $request->email,
                'telefono' => $request->telefono,
            ],
            'fecha_creacion' => Carbon::now(),
            'folio' => $folio,
        ]);

        DB::commit();

        return response()->json([
            'message' => 'Solicitud creada exitosamente',
            'folio' => $solicitud->folio,
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Error al crear la solicitud',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show($folio)
    {
        $solicitud = Solicitud::with("departamento")->where('folio', $folio)->first();

        if (!$solicitud) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }

        return response()->json($solicitud);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    public function reasignarDepartamento(Request $request, $folio)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $solicitud = Solicitud::where('folio', $folio)->first();

        if (!$solicitud) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }

        $nuevoHistorial = [
            'fecha' => Carbon::now()->toDateTimeString(),
            'accion' => 'Reasignar',
            'usuario' => auth()->user()->name,
        ];

        $historialActual = $solicitud->historial ?? [];

        if (is_string($historialActual)) {
            $historialActual = json_decode($historialActual, true);
        }

        array_unshift($historialActual, $nuevoHistorial);

        $solicitud->departamento_id = $request->departamento_id;
        $solicitud->historial = $historialActual;
        $solicitud->save();

        return response()->json([
            'message' => 'Departamento reasignado correctamente',
            'solicitud' => $solicitud
        ]);
    }
    
    public function cambiarEstado(Request $request, $folio)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,resuelto,rechazado',
        ]);

        $solicitud = Solicitud::where('folio', $folio)->first();

        if (!$solicitud) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }

        $nuevoHistorial = [
            'fecha' => Carbon::now()->toDateTimeString(),
            'accion' => 'Cambio de estado',
            'usuario' => auth()->user()->name,
        ];
    
        $historialActual = $solicitud->historial ?? [];
    
        if (is_string($historialActual)) {
            $historialActual = json_decode($historialActual, true);
        }
    
        array_unshift($historialActual, $nuevoHistorial);

        $solicitud->estado = $request->estado;
        $solicitud->historial = $historialActual;
        $solicitud->save();

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'solicitud' => $solicitud
        ]);
    }

    public function estadisticasPorDepartamento()
    {
        $estadisticas = DB::table('solicitudes')
            ->select('departamentos.nombre as departamento', DB::raw('count(*) as total'))
            ->join('departamentos', 'solicitudes.departamento_id', '=', 'departamentos.id')
            ->groupBy('departamentos.nombre')
            ->get();

        return response()->json($estadisticas);
    }
}
