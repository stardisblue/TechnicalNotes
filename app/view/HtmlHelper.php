<?php

namespace techweb\app\view;

class HtmlHelper
{
    public static function displayComments(array $comments, $csrf, $user_id = null, $type = 'technote')
    {
        echo '<ul class="media-list">';
        foreach ($comments as $comment) {
            echo '<li class="media" id="' . $comment->id . '">';
            echo '<div class="media-left">';
            echo '</div>';
            echo '<div class="media-body">';
            echo '<p class="text-muted">';
            echo '<a href="' . WEB_ROOT . '/user/' . $comment->user_id . '">' . $comment->username . '</a>';
            echo ' <span class="glyphicon glyphicon-calendar"></span>' . date('d/m/Y H:i',
                    strtotime($comment->creation_date));
            if ($user_id === $comment->user_id) {
                echo '<form action="' . WEB_ROOT . '/' . $type . '/comment/delete" method="post">
    <input type="hidden" name="csrf" value="' . $csrf . '">
    <input type="hidden" name="comment_id" value="' . $comment->id . '">
    <button type="submit" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Supprimer
    </button>
</form>';
            }
            echo '</p>';
            echo '<p>' . $comment->content . '</p>';
            echo '<p class="text-muted comment" id="' . $comment->id . '">repondre</p>';
            if (isset($comment->items)) {
                self::displayComments($comment->items, $csrf, $user_id);
            }

            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
    }
}