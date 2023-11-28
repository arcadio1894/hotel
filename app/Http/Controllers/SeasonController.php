<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonRequest;
use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seasons = Season::orderBy('start_date')->get();
        $title = "Temporadas";
        $tipo='Lista';
        return view('season.index', compact('seasons', 'title','tipo'));
    }

    public function showDeletes()
    {
        $seasons = Season::onlyTrashed()->orderBy('deleted_at')->get();
        $title = "Temporadas eliminadas";
        $tipo='Eliminados';
        return view('season.index', compact('seasons', 'title','tipo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonRequest $request)
    {
        try {
            DB::beginTransaction();
            $season = new Season();
            $season->name = $request->input('name');
            $season->start_date = $request->input('start_date');
            $season->end_date = $request->input('end_date');
            $season->save();
            DB::commit();
            return response()->json(['success' => 'Temporada creada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear la temporada. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function update(SeasonRequest $request, Season $season)
    {
        try {
            DB::beginTransaction();
            $season = Season::find($request->input('id'));
            $season->update([
                'name' => $request->input('name'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);
            DB::commit();
            return response()->json(['success' => 'Temporada actualizada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar la temporada. Detalles: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $season = Season::find($id);

            if (!$season) {
                return response()->json(['message' => 'La temporada no existe'], 404);
            }
            $season->delete();
            DB::commit();
            return response()->json(['message' => 'Temporada eliminada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar la temporada. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $season = Season::onlyTrashed()->find($id);

            if (!$season) {
                return response()->json(['message' => 'La temporada no existe'], 404);
            }
            $season->restore();
            DB::commit();
            return response()->json(['message' => 'Temporada restaurada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al restaurar la temporada. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function getDataSeason(Request $request, $pageNumber = 1){
        $perPage = 10;

        $nameSeason = $request->input('nameSeason');
        $tipo = $request->input('tipo');
        if ($tipo == 'Lista') {
            $query = Season::orderBy('name', 'ASC');
        } else{
            $query = Season::onlyTrashed()->orderBy('name', 'ASC');
        }
        if ($nameSeason) {
            $query->where('name', $nameSeason);
        }
        $results = $query->get();


        $totalFilteredRecords = $results->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $seasons = $results->skip(($pageNumber - 1) * $perPage)
            ->take($perPage);

        $arraySeasons = [];

        foreach ( $seasons as $season )
        {
            array_push($arraySeasons, [
                "id" => $season->id,
                "name" => $season->name,
                "start_date" => Carbon::parse($season->start_date)->format('d/m/Y'),
                "end_date" => Carbon::parse($season->end_date)->format('d/m/Y'),
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

        return ['data' => $arraySeasons, 'pagination' => $pagination];
    }
}
