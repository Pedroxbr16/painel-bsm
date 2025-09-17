<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\Contract;

class AdminController extends Controller {
  public function index(): string {
    $contracts = Contract::all();
    return $this->render('admin/index', ['title'=>'Admin','contracts'=>$contracts,'csrf'=>$this->csrf()]);
  }

  public function createForm(): string {
    return $this->render('admin/create', ['title'=>'Novo Contrato','csrf'=>$this->csrf()]);
  }

  public function create(): void {
    $this->checkCsrf();
    $data = $this->sanitizeContract($_POST);
    Contract::create($data);
    $this->redirect('/admin');
  }

  public function editForm(int $id): string {
    $c = Contract::find($id);
    if (!$c) { http_response_code(404); return 'Contrato não encontrado'; }
    return $this->render('admin/edit', ['title'=>'Editar Contrato','c'=>$c,'csrf'=>$this->csrf()]);
  }

  public function update(int $id): void {
    $this->checkCsrf();
    $data = $this->sanitizeContract($_POST);
    Contract::update($id, $data);
    $this->redirect('/admin');
  }

  public function delete(int $id): void {
    $this->checkCsrf();
    Contract::delete($id);
    $this->redirect('/admin');
  }

  private function sanitizeContract(array $src): array {
    $status = in_array(($src['status'] ?? ''), ['Ativo','Negociação','Encerrado']) ? $src['status'] : 'Ativo';
    return [
      'company'  => trim($src['company'] ?? ''),
      'location' => trim($src['location'] ?? ''),
      'status'   => $status,
      'notes'    => trim($src['notes'] ?? '')
    ];
  }
}
