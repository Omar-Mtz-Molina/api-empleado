<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeOfficePersonalController extends Controller
{
    //
    public function getHOPersonalPoll()
    {
        /* $personalPoll = DB::connection('mysql_main')->table('evaluacion_personal_encuesta')
        ->select('id_encuesta_p', 'nombre_encuesta')
        ->orderBy('id_encuesta_p', 'DESC')
        ->get();
        return response()->json($personalPoll); */
        $data_array = array();
        $moths = array("En.", "Febr.", "Mzo.", "Abr", "My", "Jun.", "Jul", "Agt.", "Sept.", "Oct.", "Nov.", "Dic.");
        $personalPoll = DB::connection('mysql_main')->table('evaluacion_personal_encuesta')
            ->select('id_encuesta_p', 'nombre_encuesta', 'fecha_inicio', 'fecha_fin')
            ->orderBy('id_encuesta_p', 'DESC')
            ->get();
        foreach ($personalPoll as $row) {
            $getDayStart = date("d", strtotime($row->fecha_inicio));
            $getMonthStart = date("m", strtotime($row->fecha_inicio));
            $getYearStart = date("y", strtotime($row->fecha_inicio));
            $getDayEnd = date("m", strtotime($row->fecha_fin));
            $getMonthEnd = date("m", strtotime($row->fecha_fin));
            $getYearEnd = date("y", strtotime($row->fecha_fin));
            $dateBetween = $getDayStart . " " . $moths[$getMonthStart - 1] . " " . $getYearStart . " - " . $getDayEnd . " " . $moths[$getMonthEnd - 1] . " " . $getYearEnd;
            $data_array[] = array("id_encuesta_p" => $row->id_encuesta_p, "nombre_encuesta" => $row->nombre_encuesta, "date" => $dateBetween);
        }
        return response()->json($data_array);
    }

    public function getSections()
    {
        $sections = DB::connection('mysql_main')->table('evaluacion_personal_secciones_graficas')
            ->select('id_seccion', 'nombre_seccion')
            ->where('activa', '=', 1)
            ->get();
        return response()->json($sections);

    }

    public function HOPersonalQuestions(Request $request)
    {
        /*FUNCION DE PREGUNTAS*/
        /*EL CAMPO DE JSON DE HAVE_CHILDE NOS INDICA SI LA PREGUNTA TIENE PREGUNTAS HIJO*/
        $data_array = array();
        $id_poll = $request->id_poll;
        $id_section = $request->id_section;

        $questions = DB::connection('mysql_main')->table('evaluacion_personal_preguntas_total as ept')
            ->select('ept.id_pregunta_ev', 'ept.pregunta', 'ept.hijos', 'ept.tipo_grafica as type')
            ->where('ept.id_encuesta', '=', $id_poll)
            ->where('ept.id_seccion', '=', $id_section)
            ->where('ept.grafica', '=', 1)
            ->where('ept.tipo', '=', 1)
            ->where('ept.activa', '=', 1)
            ->orderBy('ept.posicion')
            ->get();
        foreach ($questions as $row) {
            if ($row->hijos == 1) {
                $have_child = true;
            } else {
                $have_child = false;
            }

            if ($row->type == 1) {
                $type = 'bar';
            } else if ($row->type == 2) {
                $type = 'pie';
            }
            $data_array[] = array("id_question" => $row->id_pregunta_ev, "question" => $row->pregunta, "have_child" => $have_child, "type" => $type);
        }
        return response()->json($data_array);

    }

    public function HOPersonalAnswareFather(Request $request)
    {
        /*FUNCION EN DONDE TRAEMOS LAS RESPUESTAS QUE VIENEN SOLAS Y NO TIENEN HIJOS ESTAS SON DE TIPO PADRE*/
        /* $data_array = array();
        $data_answareType = array();
        $data_percentage = array();
        $id_poll = $request->id_poll;
        $id_question = $request->id_question;

        $answare = "SELECT A.percentage,opc.opciones FROM
        (SELECT id_pregunta,id_opc_resp,count(1) Preguntas_Respon
        ,round(( COUNT(id_opc_resp) / 247 * 100 )) AS percentage
        FROM  evaluacion_personal_respuestas_act
        WHERE id_encuesta=$id_poll AND id_pregunta= $id_question
        AND id_opc_resp is not null AND id_opc_resp <> 0
        GROUP BY id_opc_resp,id_pregunta) A LEFT JOIN  evaluacion_personal_opciones_respuesta as opc
        ON A.id_opc_resp = opc.id_opciones";

        $result = DB::connection('mysql_main')->select(DB::raw($answare));
        foreach ($result as $row2) {
        $data_answareType[] = ($row2->opciones);
        $data_percentage[] = ($row2->percentage);

        }
        $data_array[] = array_combine($data_answareType, $data_percentage);
        return response()->json($data_array); */
        /*FUNCION EN DONDE TRAEMOS LAS RESPUESTAS QUE VIENEN SOLAS Y NO TIENEN HIJOS ESTAS SON DE TIPO PADRE*/
        $data_array = array();
        $data_answareType = array();
        $data_percentage = array();
        $id_poll = $request->id_poll;
        $id_question = $request->id_question;

        $query_total = "SELECT COUNT(ue.id_usuariosev) as tot_user
                         FROM usuarios_evaluacion as ue
                         JOIN evaluacion_personal_respuestas_act as er
                           ON ue.id_usuariosev = er.id_usuario
                          AND er.id_encuesta = $id_poll
                          AND er.desactivada =1
                     GROUP BY er.id_pregunta
                        LIMIT 1";
        $result_t = DB::connection('mysql_main')->select(DB::raw($query_total));

        foreach ($result_t as $row) {
            $answare = "SELECT A.percentage,opc.opciones FROM
                               (SELECT id_pregunta,id_opc_resp,count(1) Preguntas_Respon
                                ,round(( COUNT(id_opc_resp) / $row->tot_user * 100 )) AS percentage
                                  FROM  evaluacion_personal_respuestas_act
                                 WHERE id_encuesta=$id_poll AND id_pregunta= $id_question
                                   AND id_opc_resp is not null
                                   AND id_opc_resp <>0
                              GROUP BY id_opc_resp,id_pregunta) A LEFT JOIN  evaluacion_personal_opciones_respuesta as opc
                                    ON A.id_opc_resp = opc.id_opciones";

            $result = DB::connection('mysql_main')->select(DB::raw($answare));
            foreach ($result as $row2) {
                $data_answareType[] = ($row2->opciones);
                $data_percentage[] = ($row2->percentage);

            }
        }
        $data_array[] = array_combine($data_answareType, $data_percentage);
        return response()->json($data_array);
    }

    public function HOPersonalAnswareChild(Request $request)
    {
        /*FUNCION QUE NOS TRAE A LOS HIJOS Y SUS RESPECTIVAS RESPUESTAS*/
        $data_array = array();
        $data_answareType = array();
        $data_percentage = array();
        $data_question = array();
        $answare_combine = array();
        $id_poll = $request->id_poll;
        $id_pregunta = $request->id_question;

        $answare_child = "SELECT  id_pregunta_ev, pregunta, sub_seccion
                            FROM evaluacion_personal_preguntas_total
                           WHERE sub_seccion = $id_pregunta
                             AND activa =1
                             AND grafica =1
                             AND id_encuesta = $id_poll
                             AND hijos =2";
        $result = DB::connection('mysql_main')->select(DB::raw($answare_child));
        foreach ($result as $rows) {

            $answare = "SELECT A.percentage,opc.opciones FROM
                               (SELECT id_pregunta,id_opc_resp,count(1) Preguntas_Respon
                                ,round(( COUNT(id_opc_resp) / 247 * 100 )) AS percentage
                                  FROM  evaluacion_personal_respuestas_act
                                 WHERE id_encuesta=$id_poll AND id_pregunta= $rows->id_pregunta_ev
                                   AND id_opc_resp is not null AND id_opc_resp <> 0
                              GROUP BY id_opc_resp,id_pregunta) A LEFT JOIN  evaluacion_personal_opciones_respuesta as opc
                                    ON A.id_opc_resp = opc.id_opciones";

            $result2 = DB::connection('mysql_main')->select(DB::raw($answare));
            foreach ($result2 as $row2) {
                $data_answareType[] = ($row2->opciones);
                $data_percentage[] = ($row2->percentage);

            }
            $data_question[] = ($rows->pregunta);
            $answare_combine[] = array_combine($data_answareType, $data_percentage);

        }
        $data_array = array_combine($data_question, $answare_combine);

        return response()->json($data_array);
    }

    public function HOPersonalConclusion(Request $request)
    {
        $data_array = array();
        $id_poll = $request->id_poll;
        $id_section = $request->id_section;

        $conclusion = DB::connection('mysql_main')->table('evaluacion_personal_conclusiones')
            ->select('id_conclusiones', 'conclusiones')
            ->where('id_encuesta', '=', $id_poll)
            ->where('id_seccion', '=', $id_section)
            ->get();

        return response()->json($conclusion);
    }

}
