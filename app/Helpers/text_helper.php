<?php
if (!function_exists('truncateText')) {
    function truncateText($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength);
            $lastSpace = strrpos($text, ' ');
            if ($lastSpace !== false) {
                $text = substr($text, 0, $lastSpace);
            }
            $text .= '...';
        }
        return $text;
    }
}
