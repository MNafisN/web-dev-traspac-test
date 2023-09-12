<?php

namespace App\Http\Services;

use App\Http\Repositories\PegawaiRepository;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Exceptions\ArrayException;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class PegawaiService
{
    protected $pegawaiRepository;

    public function __construct(PegawaiRepository $pegawaiRepository)
    {
        $this->pegawaiRepository = $pegawaiRepository;
    }

    public function uploadPhoto(array $data): array
    {
        $validator = Validator::make($data, [
            'photo' => 'required|mimes:jpg,jpeg,png|max:10000',
            'id_pegawai' => 'required',
        ],
        [
            'photo.required' => 'Please upload a file',
            'photo.mimes' => 'Only jpg, jpeg, and png images are allowed',
            'photo.max' => 'Sorry! Maximum allowed size for a file is 10MB',
        ]);

        if ($validator->fails()) {
            throw new ArrayException($validator->errors()->toArray());
        }

        $data['file_name'] = date('Y-m-d H-i-s ') . pathinfo($data['photo']->getClientOriginalName(), PATHINFO_FILENAME) .
                            "." . $data['photo']->getClientOriginalExtension();
        // $data['id_pegawai'] = fake()->numberBetween(1, 10);

        if ($this->pegawaiRepository->getPhotoByIdPegawai($data['id_pegawai']) == null) {
            $photo = $this->pegawaiRepository->uploadPhoto($data);
        } else {
            $photo = $this->pegawaiRepository->updatePhoto($data);
        }

        return $photo->toArray();
    }

    public function downloadPhoto(int $idPegawai)
    {
        $photo = $this->pegawaiRepository->getPhotoByIdPegawai($idPegawai);
        if (!$photo) {
            throw new InvalidArgumentException('Photo not found');
        }
        $photo = $this->pegawaiRepository->downloadPhoto($idPegawai);
        return $photo;
    }

    public function deletePhoto(int $idPegawai): string
    {
        $photo = $this->pegawaiRepository->getPhotoByIdPegawai($idPegawai);
        if (!$photo) {
            throw new InvalidArgumentException('Photo not found');
        }
        $photo = $this->pegawaiRepository->deletePhoto($idPegawai);

        $message = "Pegawai " . $photo . " photo deleted successfully";
        return $message;
    }

    public function getPegawaiFullData(Object $daftarPegawai): array
    {
        $pegawai = collect(Arr::except($daftarPegawai->toArray(), ['created_at', 'updated_at']));
        $bio = collect(Arr::except($daftarPegawai->pegawai->toArray(), ['id_pegawai', 'created_at', 'updated_at']));
        $jabatan = collect(Arr::except($daftarPegawai->jabatan->toArray(), ['id_jabatan', 'created_at', 'updated_at']));
        $unitKerja = collect(Arr::except($daftarPegawai->unitKerja->toArray(), ['id_unit_kerja', 'created_at', 'updated_at']));
        return $pegawai->union($bio->union($jabatan->union($unitKerja)))->toArray();
    }

    public function getSearched(string $query): array
    {
        $pegawai = [];

        $queryDecoder = rawurldecode($query);
        $result1 = $this->pegawaiRepository->searchPegawaiByNIP($queryDecoder);
        $result2 =$this->pegawaiRepository->searchPegawaiByNama($queryDecoder);

        if ($result1->isEmpty() && $result2->isEmpty()) {
            throw new InvalidArgumentException('Hasil pencarian kosong');
        } else {
            foreach ($result1 as $key => $value) {
                if (in_array($value->toArray(), $pegawai)) {
                    continue;
                } else {
                    $pegawai[] = $this->getPegawaiFullData($value);
                }
            }
            foreach ($result2 as $result) {
                if (in_array($result->daftarPegawai->toArray(), $pegawai)) {
                    continue;
                } else {
                    $pegawai[] = $this->getPegawaiFullData($result->daftarPegawai);
                }
            }
    
        }

        return $pegawai;
    }

    public function getAll(): array
    {
        $pegawai = $this->pegawaiRepository->getAll();
        if ($pegawai->isEmpty()) {
            throw new InvalidArgumentException('Data pegawai kosong');
        }
        foreach ($pegawai as $p) {
            $result[] = $this->getPegawaiFullData($p);
        }
        return $result;
    }

    public function getByUnitKerja(int $idUnitKerja): array
    {
        $uk = $this->pegawaiRepository->getUnitKerjaById($idUnitKerja);
        if (!$uk) {
            throw new InvalidArgumentException('Unit kerja tidak valid');
        }
        $pegawai = $this->pegawaiRepository->getPegawaiByUnitKerja($idUnitKerja);
        if ($pegawai->isEmpty()) {
            throw new InvalidArgumentException("Data pegawai pada unit kerja " . $uk->nama_unit_kerja . " kosong");
        }
        foreach ($pegawai as $p) {
            $result[] = $this->getPegawaiFullData($p);
        }
        return $result;
    }

    public function getPegawaiDetail(int $idPegawai): array
    {
        $pegawai = $this->pegawaiRepository->getPegawaiById($idPegawai);
        if (!$pegawai) {
            throw new InvalidArgumentException('Data pegawai tidak ditemukan');
        }
        return $this->getPegawaiFullData($pegawai);
    }

    public function getPegawaiFormData(): array
    {
        $jabatan = $this->pegawaiRepository->getListJabatan();
        $unitKerja = $this->pegawaiRepository->getListUnitKerja();

        if ($jabatan->isEmpty() || $unitKerja->isEmpty()) {
            throw new InvalidArgumentException('Ada form data yang kosong karena tabel jabatan/unit kerja kosong');
        }

        $data['jabatan'] = $jabatan->toArray();
        $data['unit_kerja'] = $unitKerja->toArray();
        return $data;
    }

    public function storePegawai(array $formData): array
    {
        $validator = Validator::make($formData, [
            'nip' => ['required', 'int', 'max_digits:18', 'unique:App\Models\DaftarPegawai,nip'],
            'nama' => ['required', 'string', 'max:50'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date_format:Y-m-d'],
            'jenis_kelamin' => ['required', 'size:1'],
            'agama' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'no_telepon' => ['required', 'string'],
            'eselon' => ['required', 'string'],
            'golongan' => ['required', 'string'],
            'nama_jabatan' => ['required', 'string'],
            'daerah_unit_kerja' => ['required', 'string'],
            'nama_unit_kerja' => ['required', 'string'],
            'no_npwp' => ['required', 'string']
        ]);

        if ($validator->fails()) { throw new ArrayException($validator->errors()->toArray()); }

        $formData['id_jabatan'] = $this->pegawaiRepository->getJabatanId($validator->validated());
        $formData['id_unit_kerja'] = $this->pegawaiRepository->getUnitKerjaId($validator->validated());
        $pegawai = $this->pegawaiRepository->storePegawai($validator->validated());

        $formData['id_pegawai'] = $pegawai->id_pegawai;
        $daftarPegawai = $this->pegawaiRepository->storeDaftarPegawai($formData);

        return $this->getPegawaiFullData($daftarPegawai);
    }

    public function updatePegawai(array $formData): array
    {
        $validator = Validator::make($formData, [
            'id_pegawai' => ['required', 'exists:daftar_pegawai'],
            'nip' => ['required', 'int', 'max_digits:18', Rule::unique('daftar_pegawai')->ignore($formData['nip'], 'nip')],
            'nama' => ['required', 'string', 'max:50'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date_format:Y-m-d'],
            'jenis_kelamin' => ['required', 'size:1'],
            'agama' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'no_telepon' => ['required', 'string'],
            'eselon' => ['required', 'string'],
            'golongan' => ['required', 'string'],
            'nama_jabatan' => ['required', 'string'],
            'daerah_unit_kerja' => ['required', 'string'],
            'nama_unit_kerja' => ['required', 'string'],
            'no_npwp' => ['required', 'string']
        ]);

        if ($validator->fails()) { throw new ArrayException($validator->errors()->toArray()); }

        $formData['id_jabatan'] = $this->pegawaiRepository->getJabatanId($validator->validated());
        $formData['id_unit_kerja'] = $this->pegawaiRepository->getUnitKerjaId($validator->validated());
        $pegawai = $this->pegawaiRepository->updatePegawai($formData);

        $daftarPegawai = $this->pegawaiRepository->updateDaftarPegawai($formData);

        return $this->getPegawaiFullData($daftarPegawai);
    }

    public function deletePegawai(int $idPegawai): string
    {
        $pegawai = $this->pegawaiRepository->getPegawaiById($idPegawai);
        if (!$pegawai) {
            throw new InvalidArgumentException('Data pegawai tidak ditemukan');
        }

        $nama = $pegawai->pegawai->nama;
        $this->pegawaiRepository->delete($idPegawai);
        return $nama;
    }

    public function exportExcel()
    {
        $pegawai = $this->pegawaiRepository->exportDaftarPegawai();
        return $pegawai;
    }
}