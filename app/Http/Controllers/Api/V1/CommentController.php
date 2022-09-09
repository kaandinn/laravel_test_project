<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Comment;
use App\Models\User;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentRequest;
use App\Enums\UserRoleEnum;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::check('is_not_admin_permission')) {
            return CommentResource::collection(
                Comment::where('user_id','=', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get()
            );
        }

        return CommentResource::collection(Comment::orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        if (Gate::check('protected-post-comment', $request)){
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $created_comment = Comment::create($request->validated());

        return new CommentResource($created_comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        if (Gate::check('owner_or_admin_comment_permission', $comment)) {
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        if (Gate::check('is_not_owner_permission', $comment)) {
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $comment->update($request->validated());

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if (Gate::check('is_not_admin_permission')){
            return response(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $comment->delete();

        return response(null, Response::HTTP_NOT_FOUND);
    }
}
