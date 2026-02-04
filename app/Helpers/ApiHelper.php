<?php

use App\Api\Response;
use App\Api\Request;
use App\Models\Api\UserFirebaseSessions;
use Kreait\Firebase\Messaging\CloudMessage;

function validateParam($request_input, $rules, $somethingElseIsInvalid = null)
{
    $request = new Request();
    return $request->validateParam($request_input, $rules, $somethingElseIsInvalid);
}

function apiResponse2($success, $status, $msg, $data = null, $title = null)
{
    $response = new Response();
    return $response->apiResponse2($success, $status, $msg, $data, $title);
}


function apiAuth()
{
    if (request()->input('test_auth_id')) {
        return App\Models\Api\User::find(request()->input('test_auth_id')) ?? die('test_auth_id not found');
    }
    return auth('api')->user();


}

function nicePrice($price)
{
    $nice = handlePrice($price, false);

    if (is_string($nice)) {
        $nice = (float)$nice;
    }

    return round($nice, 2);
}

function nicePriceWithTax($price)
{
    if (empty($price) or $price == 0) {
        return [
            "price" => 0,
            "tax" => 0
        ];
    }

    // return round(handlePrice($price, true,false,true), 2);
    $nice = handlePrice($price, false, false, true);

    if ($nice === 0) {
        return [
            "price" => 0,
            "tax" => 0
        ];
    }

    return $nice;
}


function handleSendFirebaseMessages($user_id, $group_id, $sender, $type, $title, $message)
{
    $fcmTokens = UserFirebaseSessions::where('user_id', $user_id)
        ->select('fcm_token')->get()->all();

    $deviceTokens = [];

    foreach ($fcmTokens as $fcmToken) {
        $deviceTokens[] = $fcmToken->fcm_token;
    }

    if (count($deviceTokens) > 0) {
        $messageFCM = app('firebase.messaging');

        foreach ($deviceTokens as $fcmToken) {
            $fcmMessage = CloudMessage::withTarget('token', $fcmToken);

            $fcmMessage = $fcmMessage->withNotification([
                'title' => $title,
                'body' => preg_replace('/<[^>]*>/', '', $message)
            ]);

            $fcmMessage = $fcmMessage->withData([
                'user_id' => $user_id,
                'group_id' => $group_id,
                'title' => $title,
                'message' => preg_replace('/<[^>]*>/', '', $message),
                'sender' => $sender,
                'type' => $type,
                'created_at' => time()
            ]);

            $fcmMessage = $fcmMessage->withAndroidConfig(\Kreait\Firebase\Messaging\AndroidConfig::fromArray([
                'ttl' => '3600s',
                'priority' => 'high',
                'notification' => [
                    'color' => '#f45342',
                    'sound' => 'default',
                ],
            ]));

            try {
                $messageFCM->send($fcmMessage);
            } catch (\Exception $exception) {

            }

        }

    }
}

/**
 * Create standardized pagination response for API
 * 
 * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Support\Collection $query Query builder or collection to paginate
 * @param \Illuminate\Http\Request $request Request object containing pagination parameters
 * @param callable|null $transformer Optional transformer function to format items
 * @param string $baseUrl Base URL for pagination links
 * @return array Standardized pagination response
 */
function apiPagination($query, $request, $transformer = null, $baseUrl = null)
{
    // Get pagination parameters
    $page = max(1, (int)$request->input('page', 1));
    $perPage = min(100, max(1, (int)$request->input('per_page', 10)));
    $sortBy = $request->input('sort_by', 'created_at');
    $sortOrder = in_array(strtolower($request->input('sort_order', 'desc')), ['asc', 'desc']) 
        ? $request->input('sort_order', 'desc') 
        : 'desc';
    
    // If query is a collection, convert to array for processing
    if ($query instanceof \Illuminate\Support\Collection) {
        $items = $query;
        $total = $items->count();
        
        // Manual pagination for collections
        $offset = ($page - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage)->values();
        
    } else {
        // Apply sorting if query builder
        if (method_exists($query, 'orderBy')) {
            $query = $query->orderBy($sortBy, $sortOrder);
        }
        
        // Get paginated results
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        $paginatedItems = collect($paginator->items());
        $total = $paginator->total();
    }
    
    // Apply transformer if provided
    if ($transformer && is_callable($transformer)) {
        $paginatedItems = $paginatedItems->map($transformer);
    }
    
    // Calculate pagination metadata
    $count = $paginatedItems->count();
    $totalPages = $perPage > 0 ? (int)ceil($total / $perPage) : 0;
    
    // Build base URL for links
    if (!$baseUrl) {
        $baseUrl = $request->url();
    }
    
    // Build query parameters for links
    $queryParams = $request->except(['page']);
    
    // Build pagination links
    $buildLink = function($pageNum) use ($baseUrl, $queryParams) {
        if ($pageNum === null) return null;
        $params = array_merge($queryParams, ['page' => $pageNum]);
        return $baseUrl . '?' . http_build_query($params);
    };
    
    return [
        'items' => $paginatedItems->values()->all(),
        'pagination' => [
            'total' => (int)$total,
            'count' => (int)$count,
            'per_page' => (int)$perPage,
            'current_page' => (int)$page,
            'total_pages' => (int)$totalPages,
            'links' => [
                'first' => $buildLink(1),
                'prev' => $page > 1 ? $buildLink($page - 1) : null,
                'next' => $page < $totalPages ? $buildLink($page + 1) : null,
                'last' => $buildLink($totalPages),
            ]
        ]
    ];
}
