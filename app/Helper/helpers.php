<?php

use Illuminate\Support\Facades\Log;

function sendNotification($data)
{
    $title = $data['title'];
    $body = $data['body'];
    $tokens = isset($data['tokens']) ? $data['tokens'] : [];

    if(isset($data['admin'])) {
        $adminTokens = \App\Models\User::whereRole('admin')->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
        $tokens = array_merge($adminTokens, $tokens);
    }

    $FIREBASE_API_KEY = env('FIREBASE_API_KEY');
    $url = 'https://fcm.googleapis.com/fcm/send';

    $data = [
        'registration_ids'  => $tokens,
        'notification'  => [
            'title' => $title,
            'body'  => $body,
            'sound' => 'sound',
//                'badge' => '1'
        ]
    ];

    $dataString = json_encode($data);

    $headers = array (
        'Authorization: key=' . $FIREBASE_API_KEY,
        'Content-Type: application/json'
    );

    try {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $dataString );

        curl_exec ( $ch );
        curl_close ( $ch );
    } catch (\Exception $e) {
        Log::error("Notification can't send");
    }

    return true;
}
