<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TacheRequest;
use App\Models\Tache;
use Illuminate\Http\Request;

class TacheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

/**
 * @OA\Get(
 *     path="/api/taches",
 *     tags={"Taches"},
 *     security={{"bearerAuth":{}}},
 *     summary="Liste des tâches",
 *     @OA\Response(
 *         response=405,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 * ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des tâches récupérée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="taches", type="array",
 *                 @OA\Items(type="object")
 *             )
 *         )
 *     )
 * )
 */
    public function index()
    {
        $user_id = Auth::id();
        if (!$user_id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ]);
        }

        $taches = Tache::all();
        return response()->json([
            'status' => 'success',
            'taches' => $taches
        ]);
    }

   /**
 * @OA\Post(
 *     path="/api/tache/create",
 *     tags={"Taches"},
 *     security={{"bearerAuth":{}}},
 *     summary="Créer une tâche",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="application/json",
 *         @OA\Schema(
 *            @OA\Property(property="title", type="string" , example="titre"),
 *            @OA\Property(property="description", type="string", example="description"),
 *            @OA\Property(property="datEcheance", type="string", format="date", example="date"),
 *            @OA\Property(property="status", type="integer", example="0"),
 *            @OA\Property(property="category_id", type="integer", example="1")
 *                 )
 *     )
 * ),
 *     @OA\Response(
 *         response=201,
 *         description="Tâche créée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Nouvelle tâche créée avec succès"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Utilisateur non authentifié",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Utilisateur non authentifié")
 *         )
 *     )
 * )
 */
    public function store(TacheRequest $request)
    {
        $user_id = Auth::id();
        if (!$user_id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ]);
        }

        $tache = Tache::create([
            'title' => $request->title,
            'description' => $request->description,
            'datEcheance' => $request->datEcheance,
            'status' => $request->input('status', 0),
            'user_id' => $user_id,
            'category_id' => $request->category_id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Nouvelle tache créee avec success',
            'tache' => $tache
        ]);
    }


     /**
     * @OA\Get(
     *     path="/api/taches/category",
     *     tags={"Taches"},
     *     security={{"bearerAuth":{}}},
     *     summary="Catégories de tâches",
     *     @OA\Response(
     *         response=200,
     *         description="Catégories récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="categories", type="array",
     *                 @OA\Items(type="object")
     *              )
     *         )
     * ),
     *     @OA\Response(
     *         response=405,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function show()
    {
        $user_id = Auth::id();
        if (!$user_id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ]);
        }

        $categories = Category::with('taches')->get();
        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ]);

    }

    /**
     * @OA\Put(
     *     path="/api/tache/{id}",
     *     tags={"Taches"},
     *     security={{"bearerAuth":{}}},
     *     summary="Mettre à jour une tâche",
     * @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *      )
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *              mediaType="application/json",
     *           @OA\Schema(
     *            @OA\Property(property="title", type="string" , example="titre"),
     *            @OA\Property(property="description", type="string", example="description"),
     *            @OA\Property(property="datEcheance", type="string", format="date", example="date"),
     *            @OA\Property(property="category_id", type="integer", example="1")
     *                 )
     *     )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Tâche mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Tache modifiée"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tâche non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Tâche non trouvée")
     *         )
     * ),
     * @OA\Response(
     *         response=405,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function update(TacheRequest $request, $id)
    {
        $user_id = Auth::id();
        if (!$user_id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ]);
        }
        $tache = Tache::find($id);
        if (!$tache) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tâche non trouvée'
            ]);
        }

        $tache->title = $request->title;
        $tache->description = $request->description;
        $tache->datEcheance = $request->datEcheance;
        $tache->category_id = $request->category_id;

        $tache->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Tache modifiée',
            'tache' => $tache
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/tache/{id}",
     *     tags={"Taches"},
     *     security={{"bearerAuth":{}}},
     *     summary="Supprimer une tâche",
     * @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *      )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Tâche supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Tache supprimée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tâche non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Tâche non trouvée")
     *         )
     *     ),
     * @OA\Response(
     *         response=405,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *      )
     * )
     */
    public function destroy($id)
    {
        $user_id = Auth::id();
        if (!$user_id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ]);
        }

        $tache = Tache::find($id);
        if (!$tache) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tâche non trouvée'
            ], 404);
        }
        $tache->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Tâche supprimée',
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/tache/{id}",
     *     tags={"Taches"},
     *     security={{"bearerAuth":{}}},
     *     summary="Tâches complétées",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *      )
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Tâches complétées récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Tâche complétée"),
     *             @OA\Property(property="tache", type="array",
     *                 @OA\Items(type="object")
     *             )
     * )
     *         ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune tâche complétée",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Tâche non complétée")
     *         )
     *     ),
     * @OA\Response(
     *         response=405,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *      )
     * )
     */
    public function completed($id)
    {
        $user_id = Auth::id();
        if (!$user_id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ]);
        }

        $tache = Tache::where('id', $id)->first();
        //dd($tache);
        if($tache->status == 1){
            return response()->json([
                'status' => 'success',
                'message' => 'Tache completée',
                'tache' => $tache
            ]);
        }else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tache non completée',
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/taches/{category_id}/{status}",
     *     tags={"Taches"},
     *     security={{"bearerAuth":{}}},
     *     summary="Filtrer les tâches",
     *   @OA\Parameter(
     *          name="category_id",
     *          in="path",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *      )
     * ),
     *     @OA\Parameter(
     *          name="status",
     *          in="path",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *      )
     * ),
     * @OA\Response(
     *         response=405,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *  ),
     *     @OA\Response(
     *         response=200,
     *         description="Tâches filtrées récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="taches", type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function filter(Request $request, $category_id, $status)
    {
        $user_id = Auth::id();
        if (!$user_id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié'
            ]);
        }

        /*$query = Tache::query();

        if ($request->has('category_id'))
        {
            $query->where('category_id', $category_id);
        }

        if ($request->has('status'))
        {
            $query->where('status', $status);
        }*/


        $taches = Tache::where('status', $status)
               ->where('category_id', $category_id)
               ->get();
        //dd($taches);
        return response()->json([
            'status' => 'success',
            'taches' => $taches
        ]);
    }
}
