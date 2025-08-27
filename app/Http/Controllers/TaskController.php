<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Validation\ValidationException;
use Exception;
/**
* @OA\Info(title="API tareas", version="1.0")
*
* @OA\Server(url="http://todo-api")
*/
class TaskController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/tasks",
 *     summary="Obtener lista de tareas paginadas",
 *     tags={"Tasks"},
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Número de página",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Items por página",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de tareas obtenida exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=false
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tareas obtenidas exitosamente"
 *             ),
 *             @OA\Property(
 *                     property="data",
 *                     type="object",
 *                     @OA\Property(property="current_page", type="integer", example=2),
 *                     @OA\Property(property="from", type="integer", example=11),
 *                     @OA\Property(property="last_page", type="integer", example=5),
 *                     @OA\Property(property="path", type="string", example="http://todo-api/api/tasks"),
 *                     @OA\Property(property="per_page", type="integer", example=10),
 *                     @OA\Property(property="to", type="integer", example=20),
 *                     @OA\Property(property="total", type="integer", example=50),
 *                     @OA\Property(
 *                     property="data",
 *                      type="array",
 *                      @OA\Items(ref="#/components/schemas/Task")
 *                     ),
 *                 
 *             )
 *         )
 *     )
 * )
 */
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $tasks = Task::orderByDesc('id')->simplePaginate($perPage);
         return response()->json([
            'error' => false,
            'message' => 'Tareas obtenidas exitosamente',
            'data' => $tasks
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
 * @OA\Post(
 *     path="/api/tasks",
 *     summary="Crear una nueva tarea",
 *     description="Crea una nueva tarea con los datos proporcionados. Valida los campos requeridos y retorna la tarea creada.",
 *     tags={"Tasks"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Datos de la tarea a crear",
 *         @OA\JsonContent(
 *             required={"title"},
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 description="Título de la tarea (requerido)",
 *                 example="Comprar víveres"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 description="Descripción detallada de la tarea",
 *                 example="Comprar leche, huevos y pan en el supermercado",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="completed",
 *                 type="boolean",
 *                 description="Indica si la tarea está completada",
 *                 example=false,
 *                 default=false
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tarea creada exitosamente",
 *         @OA\JsonContent(
 *              @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=false
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tarea creada exitosamente"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Comprar víveres"),
 *                 @OA\Property(property="description", type="string", example="Comprar leche, huevos y pan en el supermercado"),
 *                 @OA\Property(property="completed", type="boolean", example=false),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15 10:30:00"),
 *                @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15 10:30:00")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de validación",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="object",
 *                 example={
 *                     "title": {"The title field is required."},
 *                     "completed": {"The completed field must be true or false."}
 *                 }
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Error inesperado en el servidor"
 *             )
 *         )
 *     )
 * )
 */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'completed' => 'boolean'
            ]);

            $task = Task::create($validated);
            return response()->json([
                'error' => false,
                'message' => 'Tarea creada exitosamente',
                'data' => $task
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->errors(),
                'data' => null
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Error interno del servidor",
                'data' => null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
 * @OA\Get(
 *     path="/api/tasks/{id}",
 *     summary="Obtener una tarea específica por ID",
 *     description="Retorna los detalles completos de una tarea específica. Si la tarea no existe, retorna un error 404.",
 *     operationId="getTaskById",
 *     tags={"Tasks"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID numérico de la tarea",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *             minimum=1,
 *             example=3
 *         )
 *     ),
 *      @OA\Response(
 *         response=200,
 *         description="Tarea encontrada exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=false
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tarea encontrada"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=20),
 *                 @OA\Property(property="title", type="string", example="Comprar víveres"),
 *                 @OA\Property(property="description", type="string", example="Comprar leche, huevos y pan en el supermercado"),
 *                 @OA\Property(property="completed", type="integer", example=0),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-27T05:28:40.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-27T05:28:40.000000Z"),
 *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tarea no encontrada",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tarea no encontrada"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 nullable=true,
 *                 example=null
 *             )
 *         )
 *     )
 * )
 */
    public function show(string $id)
    {
        
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => true, 'message' => 'Tarea no encontrada', 'data' => null], 404);
        }
        return response()->json(['error' => false, 'message' => 'Tarea encontrada', 'data' => $task], 200);
        
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * @OA\Put(
 *     path="/api/tasks/{id}",
 *     summary="Actualizar una tarea existente",
 *     description="Actualiza una tarea existente. Se pueden actualizar uno o varios campos. Se debe enviar al menos un campo para actualizar.",
 *     tags={"Tasks"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la tarea a actualizar",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *             example=3
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Campos a actualizar (al menos uno requerido)",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 description="Nuevo título de la tarea",
 *                 example="Título actualizado"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 description="Nueva descripción de la tarea",
 *                 example="Descripción actualizada",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="completed",
 *                 type="boolean",
 *                 description="Estado de completado de la tarea",
 *                 example=true
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tarea actualizada exitosamente",
 *         @OA\JsonContent(
 *              @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=false
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tarea actualizada exitosamente"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=3),
 *                 @OA\Property(property="title", type="string", example="Título actualizado"),
 *                 @OA\Property(property="description", type="string", example="Descripción actualizada"),
 *                 @OA\Property(property="completed", type="boolean", example=true),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15 10:30:00"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-16 15:45:00")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de validación o datos vacíos",
 *         @OA\JsonContent(
 *              @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             oneOf={
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="message",
 *                         type="string",
 *                         example="Se debe de mandar al menos el titulo para actualizar"
 *                     )
 *                 ),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="errors",
 *                         type="object",
 *                         example={
 *                             "title": {"The title field must be a string."},
 *                             "completed": {"The completed field must be true or false."}
 *                         }
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tarea no encontrada",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tarea no encontrada"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Error inesperado en el servidor"
 *             )
 *         )
 *     )
 * )
 */
    public function update(Request $request, string $id)
    {
        try {
            $task = Task::find($id);
            if (!$task) {
                return response()->json(['error' => true, 'message' => 'Tarea no encontrada'], 404);
            }

            $validated = $request->validate([
                'title' => 'sometimes|string',
                'description' => 'sometimes|nullable|string',
                'completed' => 'sometimes|boolean'
            ]);

            if (empty($validated)) {
                return response()->json([ 'error' => true, 'message' => 'Se debe de mandar al menos el titulo para actualizar'], 400);
            }

            $task->update($validated);

            return response()->json([
                'error' => false,
                'message' => 'Tarea actualizada exitosamente',
                'data' => $task
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->errors()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Error inesperado en el servidor"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
 * @OA\Delete(
 *     path="/api/tasks/{id}",
 *     summary="Eliminar una tarea",
 *     description="Elimina permanentemente una tarea específica basado en su ID. Retorna un código 204 sin contenido si la eliminación es exitosa.",
 *     tags={"Tasks"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la tarea a eliminar",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *             minimum=1,
 *             example=4
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Tarea eliminada exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             nullable=true,
 *             example={}
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tarea no encontrada",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example="true"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tarea no encontrada"
 *             )
 *         )
 *     )
 * )
 */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => true, 'message' => 'Tarea no encontrada'], 404);
        }
        $task->delete();
        return response()->noContent();
    }
}
