<?php

namespace App\Http\Controllers;

use App\Models\Individu;
use App\Models\Groupe;
use App\Models\Salle;
use App\Models\Seance;
use App\Models\SeanceGroupe;
use App\Models\TypeSeance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalleController extends Controller
{

    public function index()
    {
        $salles = Salle::getAll();
        return view('salle.index', compact('salles'));
    }

    public function create()
    {

        $salles = Salle::getAll();

        return view('salle.create', compact(
            'salles'
        ));
    }

    public function store(Request $request){

      $this->validate($request,[
        'batiment'=>'required|max:20|string:20',
        'numero_salle'=>'required|max:10|',
        'capacite'=>'required|max:11|',
        'nb_postes'=>'required|max:11|',
        'projecteur'=>'required|max:1|',
      ]);

      $salle = new Salle;
      $lastSalle = DB::table('salle')->max('id_salle');
      $salle->id_salle=$lastSalle+1;

      $salle->batiment = $request->input('batiment');
      $salle->numero_salle = $request->input('numero_salle');
      $salle->capacite = $request->input('capacite');
      $salle->nb_postes = $request->input('nb_postes');
      $salle->projecteur = $request->input('projecteur');
      DB::table('salle')->insert((array)$salle);

      return redirect()->action('SalleController@index');
    }


    public function edit()
    {
        $salle = Salle::getAll();

        return view('salle.edit', compact(
            'salle'
        ));
    }

    // public function edit($id)
    // {
    //     $groupes = Groupe::getAll();
    //     $typeSeances = TypeSeance::getAll();
    //     $enseignants = Individu::getEnseignants();
    //     $salles = Salle::getAll();
    //
    //     $seance = Seance::getWithGroupe($id);
    //
    //     return view('seance.edit', compact(
    //         'groupes',
    //         'typeSeances',
    //         'enseignants',
    //         'salles',
    //         'seance'
    //     ));
    // }
    //
    // public function update(Request $request, $id)
    // {
    //     $validatedSeanceData = $request->validate([
    //         'fid_type_seance' => 'nullable|exists:type_seance,id_type_seance',
    //         'fid_individu' => 'nullable|exists:individu,id_individu',
    //         'fid_salle' => 'nullable|exists:salle,id_salle',
    //         'date_debut_seance' => 'nullable|date',
    //         'date_fin_seance' => 'nullable|date',
    //     ]);
    //
    //     $validatedGroupeData = $request->validate([
    //         'fid_groupe' => 'required|exists:groupe,id_groupe',
    //     ]);
    //
    //     Seance::update($id, $validatedSeanceData);
    //     SeanceGroupe::delete($id);
    //     $validatedGroupeData['fid_seance'] = $id;
    //     SeanceGroupe::create($validatedGroupeData);
    //
    //     return redirect()->action('SeanceController@edit' , $id);
    // }

    public function destroy()
    {
        $batiment= $request->input('batiment');
        $numero_salle = $request->input('numero_salle');
        $capacite = $request->input('capacite');
        $nb_postes = $request->input('nb_postes');
        $projecteur = $request->input('projecteur');
        Salle::where('batiment',$id)->delete();
        return redirect()->action('SalleController@index');
    }
}
