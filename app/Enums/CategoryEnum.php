<?php

namespace App\Enums;

enum CategoryEnum: string
{
    case FOODS = 'Comidas';
    case DRINKS = 'Bebidas';
    case HORTIFRUTI = 'Hortifruti';
    case MEATS = 'Carnes e frios';
    case DISPOSABLES = 'Descartáveis';
    case HYGIENE = 'Higiene';
    case CLEANING = 'Limpeza';
    case DECORATION = 'Decoração';
    case MONEY = 'Dinheiro';
    case OTHERS = 'Outros';
}
