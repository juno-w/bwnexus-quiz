<?php

/**
 * Extract the five most frequent words from a text.
 * - Case-insensitive
 * - Punctuation excluded
 * - Sorted by frequency desc, then alphabetically asc
 * - Unicode-aware for multilingual text
 *
 * @param string $text
 * @return array<string,int> Associative array [word => count]
 */
function findTopFiveFrequentWords($text)
{
    if (!is_string($text) || $text === '') {
        return [];
    }

    // Normalize and clean input
    $text = strip_tags($text);                    // Remove HTML tags if any
    $text = mb_strtolower($text, 'UTF-8');        // Case-insensitive

    // Extract words: letters followed by letters/numbers (Unicode-aware)
    // This excludes punctuation and symbols while supporting multilingual text
    if (!preg_match_all('/\p{L}[\p{L}\p{N}]*/u', $text, $matches)) {
        return [];
    }
    $words = $matches[0];
    if (empty($words)) {
        return [];
    }

    // Count frequencies efficiently
    $counts = [];
    foreach ($words as $w) {
        // Using isset is faster than array_key_exists in hot paths
        if (isset($counts[$w])) {
            $counts[$w]++;
        } else {
            $counts[$w] = 1;
        }
    }

    // Sort by frequency desc, then alphabetically asc
    uksort($counts, function ($a, $b) use ($counts) {
        $ca = $counts[$a];
        $cb = $counts[$b];
        if ($cb === $ca) {
            return $a <=> $b; // alphabetically asc when counts tie
        }
        return $cb <=> $ca;   // frequency desc
    });

    // Keep only the top 5 while preserving keys
    return array_slice($counts, 0, 5, true);
}

$result = findTopFiveFrequentWords(
    text: "Bonjour le monde! Hola mundo. Hello world. Bonjour tout le monde. Hello everyone."
);

print_r($result);
