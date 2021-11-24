<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeOfficeWeekController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function hoWeekReviews(Request $request)
    {
        $data_arr = array();
        $moths = array("En.", "Febr.", "Mzo.", "Abr", "My", "Jun.", "Jul", "Agt.", "Sept.", "Oct.", "Nov.", "Dic.");
        $reviews = DB::connection('mysql_main')->table('evaluacion_encuesta as ev')
            ->select('ev.id_encuesta', 'ev.nombre_encuesta', 'ev.fecha_inicio', 'ev.fecha_fin')
            ->orderBy('ev.id_encuesta', 'DESC')
            ->get();
        foreach ($reviews as $row) {
            $getDayStart = date("d", strtotime($row->fecha_inicio));
            $getMonthStart = date("m", strtotime($row->fecha_inicio));
            $getYearStart = date("y", strtotime($row->fecha_inicio));
            $getDayEnd = date("m", strtotime($row->fecha_fin));
            $getMonthEnd = date("m", strtotime($row->fecha_fin));
            $getYearEnd = date("y", strtotime($row->fecha_fin));
            $dateBetween = $getDayStart . " " . $moths[$getMonthStart - 1] . " " . $getYearStart . " - " . $getDayEnd . " " . $moths[$getMonthEnd - 1] . " " . $getYearEnd;
            $data_arr[] = array("id_poll" => $row->id_encuesta, "name_poll" => $row->nombre_encuesta, "date" => $dateBetween);
        }
        return response()->json(
            $data_arr
        );
    }

    public function hoWeek(Request $request)
    {
        $response = [];
        $id_poll = $request->id_poll;
        $noempleado = auth()->user()->employee_code;
        $quest = DB::connection('mysql_main')->table('evaluacion_respuestas as us')
            ->join('usuarios_evaluacion as usd', 'us.id_usuario', '=', 'usd.id_usuariosev')
            ->select('us.desempeno', 'us.colaboracion', 'us.disponibilidad', 'usd.nombre')
            ->where('us.id_encuesta', '=', $id_poll)
            ->where('usd.no_empleado', '=', $noempleado)
            ->first();
        return response()->json(
            $quest
        );
    }

    public function hoWeekDirector(Request $request)
    {
        $data_arr = array();
        $noempleado = auth()->user()->employee_code;
        $id_poll = $request->id_poll;
        $director = DB::connection('mysql_main')->table('usuarios_evaluacion as ue')
            ->select('ue.id_director')
            ->where('ue.no_empleado', '=', $noempleado)
            ->get();
        foreach ($director as $row) {
            $directorReview = DB::connection('mysql_main')->table('usuarios_evaluacion as eu')
                ->join('evaluacion_respuestas as er', 'eu.id_usuariosev', '=', 'er.id_guardo')
                ->select('eu.nombre')
                ->addSelect(DB::raw('round(AVG(er.desempeno),0) as desempeno'), DB::raw('round(AVG(er.colaboracion),0) as colaboracion'), DB::raw('round(AVG(er.disponibilidad),0) as disponibilidad'))
                ->where('er.id_encuesta', '=', $id_poll)
                ->where('er.id_guardo', '=', $row->id_director)
                ->groupBy('eu.nombre')
                ->get();
            foreach ($directorReview as $row2) {
                $data_arr[] = array("Director" => $row2->nombre, "Desempeño" => $row2->desempeno, "Colaboración" => $row2->colaboracion, "Disponibilidad" => $row2->disponibilidad);
            }
        }
        return response()->json($data_arr);

    }

    public function hoAreaReview()
    {
        $data_array = array();
        $moths = array("En.", "Febr.", "Mzo.", "Abr", "My", "Jun.", "Jul", "Agt.", "Sept.", "Oct.", "Nov.", "Dic.");
        $reviews = DB::connection('mysql_main')->table('evaluacion_areas_encuesta as eav')
            ->select('eav.id_encuesta_a', 'eav.nombre_encuesta', 'eav.fecha_inicio', 'eav.fecha_fin')
            ->orderBy('eav.id_encuesta_a', 'DESC')
            ->get();
        foreach ($reviews as $row) {
            $getDayStart = date("d", strtotime($row->fecha_inicio));
            $getMonthStart = date("m", strtotime($row->fecha_inicio));
            $getYearStart = date("y", strtotime($row->fecha_inicio));
            $getDayEnd = date("m", strtotime($row->fecha_fin));
            $getMonthEnd = date("m", strtotime($row->fecha_fin));
            $getYearEnd = date("y", strtotime($row->fecha_fin));
            $dateBetween = $getDayStart . " " . $moths[$getMonthStart - 1] . " " . $getYearStart . " - " . $getDayEnd . " " . $moths[$getMonthEnd - 1] . " " . $getYearEnd;
            $data_array[] = array("id_poll" => $row->id_encuesta_a, "name_poll" => $row->nombre_encuesta, "date" => $dateBetween);
        }

        return response()->json(
            $data_array
        );
    }

    public function hoAreas(Request $request)
    {
        $data_arr = array();
        $id_poll = $request->id_poll;
        $areas = DB::connection('mysql_main')->table('areas')
            ->select('id_area', 'area')
            ->where('activa', '=', 1)
            ->get();
        foreach ($areas as $row) {
            $areasReview = DB::connection('mysql_main')->table('areas as eu')
                ->join('evaluacion_areas_respuestas as er', 'eu.id_area', '=', 'er.id_area')
                ->select('eu.area')
                ->addSelect(DB::raw('round(AVG(er.desempeno),0) as desempeno'), DB::raw('round(AVG(er.colaboracion)) as colaboracion'), DB::raw('round(AVG(er.disponibilidad)) as disponibilidad'))
                ->where('er.id_area', '=', $row->id_area)
                ->where('er.id_encuesta', '=', $id_poll)
                ->where('er.activo', '=', 2)
                ->groupBy('eu.area')
                ->get();
            foreach ($areasReview as $row2) {
                $data_arr[] = array("Director" => $row2->area, "Desempeño" => $row2->desempeno, "Colaboración" => $row2->colaboracion, "Disponibilidad" => $row2->disponibilidad);
            }
        }
        return response()->json($data_arr);
    }

    public function hoWeekDirectorTeam(Request $request)
    {
        $data_arr = array();
        $noempleado = auth()->user()->employee_code;
        $id_poll = $request->id_poll;
        $director = DB::connection('mysql_main')->table('usuarios_evaluacion as ue')
            ->select('ue.id_usuariosev')
            ->where('ue.no_empleado', '=', $noempleado)
            ->get();
        foreach ($director as $row) {
            $directorTeam = DB::connection('mysql_main')->table('usuarios_evaluacion as ue')
                ->join('evaluacion_respuestas as ur', 'ue.id_usuariosev', '=', 'ur.id_usuario')
                ->select('ue.nombre', 'ur.desempeno', 'ur.colaboracion', 'ur.disponibilidad')
                ->where('ue.id_director', '=', $row->id_usuariosev)
                ->where('ur.id_encuesta', '=', $id_poll)
                ->where('ur.desactivada', '=', 1)
                ->get();
            foreach ($directorTeam as $row2) {
                $data_arr[] = array("Usuario" => $row2->nombre, "Desempeño" => $row2->desempeno, "Colaboración" => $row2->colaboracion, "Disponibilidad" => $row2->disponibilidad);
            }
        }
        return response()->json($data_arr);
    }
}
