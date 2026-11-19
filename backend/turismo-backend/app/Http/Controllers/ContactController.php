<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/contacto",
     *     summary="Obtener la información de contacto global",
     *     tags={"Contacto"},
     *     @OA\Response(
     *         response=200,
     *         description="Datos de contacto obtenidos exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="Telefono", type="string", example="+54 9 2262 123456"),
     *                 @OA\Property(property="Whatsapp", type="string", example="+54 9 2262 123456"),
     *                 @OA\Property(property="Email", type="string", example="contacto@turismo.com"),
     *                 @OA\Property(property="Web", type="string", example="https://turismo.com"),
     *                 @OA\Property(property="Instagram", type="string", example="@turismo_oficial"),
     *                 @OA\Property(property="Facebook", type="string", example="facebook.com/turismo"),
     *                 @OA\Property(property="Youtube", type="string", example="youtube.com/@turismo"),
     *                 @OA\Property(property="Tiktok", type="string", example="@turismo_tiktok"),
     *                 @OA\Property(property="Direccion", type="string", example="Av. 2 4321, Necochea")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Contacto no encontrado")
     * )
     */
    public function show(): JsonResponse
    {
        $contact = Contact::first();

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'No hay información de contacto registrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $contact
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/contacto",
     *     summary="Actualizar la información de contacto global",
     *     tags={"Contacto"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Telefono", type="string", example="+54 9 2262 123456"),
     *             @OA\Property(property="Whatsapp", type="string", example="+54 9 2262 123456"),
     *             @OA\Property(property="Email", type="string", example="contacto@turismo.com"),
     *             @OA\Property(property="Web", type="string", example="https://turismo.com"),
     *             @OA\Property(property="Instagram", type="string", example="@turismo_oficial"),
     *             @OA\Property(property="Facebook", type="string", example="facebook.com/turismo"),
     *             @OA\Property(property="Youtube", type="string", example="youtube.com/@turismo"),
     *             @OA\Property(property="Tiktok", type="string", example="@turismo_tiktok"),
     *             @OA\Property(property="Direccion", type="string", example="Av. 2 4321, Necochea")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Datos de contacto actualizados exitosamente")
     * )
     */
    public function update(Request $request): JsonResponse
    {
        $contact = Contact::first() ?? new Contact();

        $validated = $request->validate([
            'telefono' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'web' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $contact->fill($validated);
        $contact->save();

        return response()->json([
            'success' => true,
            'message' => 'Datos de contacto actualizados',
            'data' => $contact
        ]);
    }
}
