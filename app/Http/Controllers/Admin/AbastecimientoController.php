<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Http\Requests\CreateAbastecimientoRequest;
use App\Http\Requests\UpdateAbastecimientoRequest;
use App\Repositories\AbastecimientoRepository;
use App\Models\Establecimiento;
use App\Models\Abastecimiento;
use App\Http\Controllers\AppBaseController;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AbastecimientoController extends AppBaseController
{
 
    private $abastecimientoRepository;

    public function __construct(AbastecimientoRepository $abastecimientoRepo)
    {
        $this->abastecimientoRepository = $abastecimientoRepo;
    }

    public function index(Request $request)
    {
        $this->abastecimientoRepository->pushCriteria(new RequestCriteria($request));
        $abastecimientos = $this->abastecimientoRepository->all();

        return view('admin.abastecimientos.index')
            ->with('abastecimientos', $abastecimientos); 
    }

    
    public function edit($id)
    {
        
    }

    public function update($id, UpdateAbastecimientoRequest $request)
    {
        
    
    }

    public function destroy($id)
    {
        $abastecimiento = $this->abastecimientoRepository->findWithoutFail($id);

        if (empty($abastecimiento)) {
            Flash::error('Abastecimiento no encontrado');

            return redirect(route('abastecimientos.index'));
        }

        $this->abastecimientoRepository->delete($id);

        Flash::success('Abastecimiento eliminado.');

        return redirect(route('abastecimientos.index'));
    }

}
