<?php

namespace App\Enums;

enum QuestionType: int
{
    case INPUT = 1;
    case RADIO = 2;
    case MULTISELECT = 3;
}
