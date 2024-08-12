<?php

namespace App\Http\Controllers;

use App\Http\Resources\NilaiCollection;
use App\Http\Resources\NilaiRtResource;
use App\Http\Resources\NilaiStResource;
use App\Models\Nilai;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function apiGetNilaiRT()
    {
        $nilaiRt = Nilai::where('materi_uji_id', 7)->where('nama_pelajaran', '!=', 'pelajaran khusus')
            ->groupBy('nisn', 'pelajaran_id')
            ->get()
            ->sortBy('id_siswa')
            ->groupBy('nisn')
            ->map(function ($group) {
                return (object) [
                    'nama' => $group->first()->nama,
                    'nilaiRt' => $group->pluck('skor', 'nama_pelajaran'),
                    'nisn' => $group->first()->nisn,
                ];
            })->values();

        return NilaiRtResource::collection($nilaiRt);
    }

    public function apiGetNilaiST()
    {
        $nilaiSt = Nilai::where('materi_uji_id', 4)
            ->groupBy('nisn', 'pelajaran_id')
            ->get()
            ->sortBy('id_siswa')
            ->groupBy('nisn')
            ->map(function ($group) {
                $totalSkor = 0;
                $group->each(function ($item) use (&$totalSkor) {
                    if ($item->pelajaran_id == 44) {
                        $item->skor = round($item->skor * 41.67, 2);
                    } elseif ($item->pelajaran_id == 45) {
                        $item->skor = round($item->skor * 29.67, 2);
                    } elseif ($item->pelajaran_id == 46) {
                        $item->skor = round($item->skor * 100, 2);
                    } elseif ($item->pelajaran_id == 47) {
                        $item->skor = round($item->skor * 23.81, 2);
                    }
                    $totalSkor += $item->skor;
                });

                return (object) [
                    'nama' => $group->first()->nama,
                    'listNilai' => $group->pluck('skor', 'nama_pelajaran'),
                    'nisn' => $group->first()->nisn,
                    'total' => $totalSkor,
                ];
            })
            ->sortByDesc('total')
            ->values();

        return NilaiStResource::collection($nilaiSt);
    }
}
