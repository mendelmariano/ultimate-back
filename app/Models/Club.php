<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Club extends Model
{
    use HasFactory;


    protected $fillable = [
        "user_id",
        "pontos",
        "vitorias",
        "empates",
        "derrotas",
        "gols_feitos",
        "gols_sofridos",
        "saldo_gols",
        "name"
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jogos_casa()
    {
        return $this->hasMany(Result::class, "casa_id", "id");
    }

    public function jogos_fora()
    {
        return $this->hasMany(Result::class, "fora_id", "id");
    }


    public static function getAllNgSmart()
    {
        $request=request();
        $page                   = $request->has('_page') ? $request->get('_page') : 1;
        $limit                  = $request->has('_limit') ? $request->get('_limit') : 20;
        $sort                   = $request->has('_sort') ? $request->get('_sort'):"descricao";
        $order                  = $request->has('_order') ? $request->get('_order'):"asc";

      
        $total= self::count();

        $query=self::join('users', 'users.id', '=', 'users.municipio_id');
        $query->select("users.*");

      
        if ($request->name):
            $query->where(DB::raw('lower(users.name)'), 'like', "%".strtolower($request->name_like). "%");
        endif;

      

        /*
        Futuramente se necessário: cair na condição somenete se data válida recebida e comparar retirando as horas.
        if ($request->created_at_like):
            $query->where('nucleos.created_at',  '=', Carbon::createFromFormat('d/m/Y', $request->created_at_like)->format('Y-m-d'));
        endif;

        if ($request->updated_at_like):
            $query->where('nucleos.updated_at',  '=', Carbon::createFromFormat('d/m/Y', $request->updated_at_like)->format('Y-m-d'));
        endif;
        */

        $total=$query->count();

        $query->orderBy($sort, $order);
        $query->limit($limit)->offset(($page - 1) * $limit);
       
        $resposta= new \stdClass();
        $resposta->data=$query->get();
        $resposta->total=$total;
       
        return $resposta;




    }


}
