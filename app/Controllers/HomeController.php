<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\Contract;

class HomeController extends Controller {
  public function index(): string {
    $s = Contract::stats();
    $contracts = array_slice(Contract::all(), 0, 10);
    $stats = [
      ['label'=>'Contratos ativos', 'value'=>$s['ativos']],
      ['label'=>'Em negociação',    'value'=>$s['neg']],
      ['label'=>'Encerrados',       'value'=>$s['enc']],
    ];
    return $this->render('home/index', compact('stats','contracts') + ['title'=>'Dashboard']);
  }
}
