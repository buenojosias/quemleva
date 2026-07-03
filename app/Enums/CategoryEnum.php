<?php

namespace App\Enums;

enum CategoryEnum: string
{
    case FOOD = 'Comidas';
    case DRINKS = 'Bebidas';
    case DISPOSABLES = 'Descartáveis';
    case HYGIENE = 'Higiene';
    case CLEANING = 'Limpeza';
    case DECORATION = 'Decoração';
    case MONEY = 'Dinheiro';
    case OTHERS = 'Outros';
}
