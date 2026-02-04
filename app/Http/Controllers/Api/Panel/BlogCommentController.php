<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Api\Comment;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{

    public function index(Request $request)
    {
        $user = apiAuth();
        $posts = Blog::where('author_id', $user->id)->get();
        $blogIds = $posts->pluck('id')->toArray();
        
        $query = Comment::whereIn('blog_id', $blogIds)
            ->handleFilters();

        $blogId = $request->get('blog_id', null);
        $data = [];

        if (!empty($blogId) and is_numeric($blogId)) {
            $data['selectedPost'] = Blog::where('id', $blogId)
                ->where('author_id', $user->id)
                ->first();
        }

        $paginatedData = apiPagination(
            $query,
            $request,
            function ($comment) {
                return (new CommentResource($comment))->resolve();
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/blog/comments')
        );

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            array_merge($data, $paginatedData)
        );
    }
}
