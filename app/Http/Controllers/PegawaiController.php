<?php

namespace App\Http\Controllers;

use App\Http\Services\PegawaiService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Exceptions\ArrayException;

class PegawaiController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function uploadPhoto(Request $request): JsonResponse
    {
        $data = $request->all();

        try {
            $result = [
                'status' => 201,
                'data' => $this->pegawaiService->uploadPhoto($data)
            ];
        } catch (ArrayException $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessagesArray()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function downloadPhoto(int $idPegawai)
    {

        try {
            return $this->pegawaiService->downloadPhoto($idPegawai);
            // $result = [
            //     'status' => 200,
            //     'data' => $this->pegawaiService->downloadPhoto($idPegawai)
            // ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deletePhoto(int $idPegawai): JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'message' => $this->pegawaiService->deletePhoto($idPegawai)
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function searchPegawai(string $query): JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'data' => $this->pegawaiService->getSearched($query)
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function getAllPegawai(): JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'data' => $this->pegawaiService->getAll()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function getByUnitKerja(int $idUnitKerja): JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'data' => $this->pegawaiService->getByUnitKerja($idUnitKerja)
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function getPegawaiDetail(int $idPegawai): JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'data' => $this->pegawaiService->getPegawaiDetail($idPegawai)
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function getFormData(): JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'form_data' => $this->pegawaiService->getPegawaiFormData()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function storeNewPegawai(Request $request): JsonResponse
    {
        $data = $request->all();

        try {
            $result = [
                'status' => 201,
                'data' => $this->pegawaiService->storePegawai($data)
            ];
        } catch (ArrayException $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessagesArray()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function updatePegawai(Request $request): JsonResponse
    {
        $data = $request->all();        

        try {
            $result = [
                'status' => 200,
                'data' => $this->pegawaiService->updatePegawai($data)
            ];
        } catch (ArrayException $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessagesArray()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deletePegawai(int $idPegawai): JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'message' => "Pegawai " . $this->pegawaiService->deletePegawai($idPegawai) . " deleted successfully"
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function exportExcelDataPegawai()
    {
        try {
            return $this->pegawaiService->exportExcel();
            // $result = [
            //     'status' => 200,
            //     'data' => $this->pegawaiService->exportExcel()
            // ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}