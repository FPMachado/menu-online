<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;

class AdminCardapioController extends Controller
{
    // ── CATEGORIAS ──────────────────────────────────────────

    public function getCategorias()
    {
        $categorias = Categoria::orderBy('ordem')->get();

        return response()->json([
            'categorias' => $categorias,
        ]);
    }

    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nome'  => 'required|string',
            'emoji' => 'nullable|string',
        ]);

        $categoria = Categoria::create([
            'nome'  => $request->nome,
            'emoji' => $request->emoji,
            'ordem' => Categoria::count(),
            'ativo' => true,
        ]);

        return response()->json([
            'success'   => true,
            'categoria' => $categoria,
        ]);
    }

    public function updateCategoria(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nome'  => 'required|string',
            'emoji' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $categoria->update($request->only('nome', 'emoji', 'ativo'));

        return response()->json([
            'success'   => true,
            'categoria' => $categoria,
        ]);
    }

    public function destroyCategoria(Categoria $categoria)
    {
        $categoria->delete();

        return response()->json(['success' => true]);
    }

    // ── PRODUTOS ─────────────────────────────────────────────

    public function getProdutos()
    {
        $produtos = Produto::with('categoria')
            ->orderBy('categoria_id')
            ->orderBy('ordem')
            ->get();

        return response()->json([
            'produtos' => $produtos,
        ]);
    }

    public function storeProduto(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nome'         => 'required|string',
            'preco'        => 'required|numeric',
        ]);

        $produto = Produto::create([
            'categoria_id' => $request->categoria_id,
            'nome'         => $request->nome,
            'descricao'    => $request->descricao,
            'preco'        => $request->preco,
            'preco_antigo' => $request->preco_antigo,
            'badge'        => $request->badge,
            'ativo'        => true,
            'ordem'        => Produto::where('categoria_id', $request->categoria_id)->count(),
        ]);

        return response()->json([
            'success' => true,
            'produto' => $produto,
        ]);
    }

    public function updateProduto(Request $request, Produto $produto)
    {
        $request->validate([
            'nome'  => 'required|string',
            'preco' => 'required|numeric',
        ]);

        $produto->update($request->only(
            'categoria_id',
            'nome',
            'descricao',
            'preco',
            'preco_antigo',
            'badge',
            'ativo',
            'imagem',
        ));

        return response()->json([
            'success' => true,
            'produto' => $produto,
        ]);
    }

    public function destroyProduto(Produto $produto)
    {
        $produto->delete();

        return response()->json(['success' => true]);
    }
}
