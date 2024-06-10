<?php

namespace Hoang\Mvcoop\Controllers\Admin;

use Hoang\Mvcoop\Commons\Controller;
use Hoang\Mvcoop\Models\Admin\CommentModel;

class CommentController extends Controller
{
    private CommentModel $comment;
    public function __construct()
    {
        $this->comment = new CommentModel();
    }
    public function listComment()
    {

        return $this->renderViewAdmin('comments.listComment',
            [
                'CommentByPost' => $this->comment->getPostByComment()
            ]
        );
    }

    public function commentDetail($id)
    {
        return $this->renderViewAdmin('comments.comment',
            [
                'listCommentByIdPost' => $this->comment->getCommentByIdPost($id)
            ]
        );
    }
}