<?php

namespace techweb\app\view;

class HtmlHelper
{
    public static function displayComments(array $comments)
    {
        echo '<ul class="media-list">';
        foreach ($comments as $comment) {
            echo '<li class="media" id="' . $comment->id . '">';
            echo '<div class="media-left">';
            echo '</div>';
            echo '<div class="media-body">';
            echo '<p class="text-muted">';
            echo '<a href="' . WEB_ROOT . '/user/' . $comment->user_id . '">' . $comment->username . '</a>';
            echo '<span class="glyphicon glyphicon-calendar"></span>' . date('d/m/Y H:i',
                    strtotime($comment->creation_date));
            echo '</p>';
            echo '<p>' . $comment->content . '</p>';
            echo '<p class="text-muted answer" id="<?=$comment->id?>">repondre</p>';
            if (isset($comment->items)) {
                self::displayComments($comment->items);
            }

            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
    }
}