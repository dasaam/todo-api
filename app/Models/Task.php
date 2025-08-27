<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     required={"title"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID único de la tarea"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Título de la tarea"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descripción detallada de la tarea",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="completed",
 *         type="boolean",
 *         description="Indica si la tarea está completada",
 *         default=false
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de elimiminacion (soft delete)"
 *     )
 * )
 */
class Task extends Model
{
    use HasFactory;
    use SoftDeletes; 

    protected $fillable = ['title', 'description', 'completed'];
}
