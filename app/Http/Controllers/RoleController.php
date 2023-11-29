<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        $tipo='lista';
        return view('role.index', compact('tipo'));
    }


    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
    public function getDataOperations(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $description = $request->input('description');
        $tipo = $request->input('tipo');
        if($tipo=='lista'){
            $query = Role::orderBy('id', 'DESC');
        }

        // Aplicar filtros si se proporcionan

        if ($description) {
            $query->where('description', $description);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $operations = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $arrayOperations = [];

        foreach ( $operations as $operation )
        {

            array_push($arrayOperations, [
                "id" => $operation->id,
                "name" => $operation->name,
                "description" => $operation->description,
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $arrayOperations, 'pagination' => $pagination];

    }
}
