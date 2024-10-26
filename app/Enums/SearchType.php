<?php

namespace App\Enums;

enum SearchType: string
{
    case EXACT = 'exact-match';
    case PARTIAL = 'partial-match';
    case PHONETIC = 'phonetic';
    case FULLTEXT = 'fulltext';
}
