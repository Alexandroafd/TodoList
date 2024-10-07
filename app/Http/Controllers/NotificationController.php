<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Tache;
use App\Notifications\TacheRemenber;
use Illuminate\Http\Request;

 /**
 * @OA\Tag(
 *     name="Notification",
 *     description="Opérations sur les tâches"
 * )
 */
class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

 public function index()
    {

        $notif = Notification::all();
        return response()->json([
            'status' => 'success',
            'notif' => $notif
        ]);
    }

    public function sendNotification()
    {
        $taches = Tache::where('status', 0)
            ->where('datEcheance', '<=', now()->addDays(1))
            ->get();

        foreach ($taches as $tache) {
            $user = $tache->user;
            if ($user) {
                Notification::send($user, new TacheRemenber($tache));
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification envoyée avec succès'
        ]);
    }
}
