<?php

namespace App\Http\Controllers;

use App\Actions\ObterEstatisticasDashboardAction;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function stats(ObterEstatisticasDashboardAction $action)
    {
        return $action->execute()->toArray();
    }
}
