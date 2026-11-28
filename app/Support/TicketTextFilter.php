<?php

namespace App\Support;

class TicketTextFilter
{
    /**
     * Return true when the text contains a configured banned word or phrase.
     * Whole-word matching avoids blocking innocent words that merely contain
     * a banned character sequence (for example, "class").
     */
    public function containsBannedWords(?string $text): bool
    {
        if ($text === null || trim($text) === '') {
            return false;
        }

        $terms = config('ticketing.banned_words', []);
        $text = mb_strtolower($text, 'UTF-8');

        foreach ($terms as $term) {
            $term = trim(mb_strtolower((string) $term, 'UTF-8'));
            if ($term === '') {
                continue;
            }

            $pattern = '/(?<![\p{L}\p{N}])' . preg_quote($term, '/') . '(?![\p{L}\p{N}])/iu';
            if (preg_match($pattern, $text) === 1) {
                return true;
            }
        }

        return false;
    }
}
