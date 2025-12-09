<?php
// Ziyaretçi ana sayfası ve genel tanıtım ekranını sunar.

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class HomeController extends Controller
{
    public function index(): void
    {
        $features = [
            'Kompetenzbasierte Quizübungen',
            'Persönliches Dashboard mit Lernzielen',
            'Aktuelle News & Gesetzesänderungen',
            'Mobile-first responsive Oberfläche',
        ];
        $this->view('home/index', [
            'features' => $features,
            'flash' => Session::getFlash('success'),
        ]);
    }
}
