<?php

namespace App\Http\Repositories;

use App\Models\File;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\UnitKerja;
use App\Models\DaftarPegawai;

use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\PegawaiExport;

class PegawaiRepository
{
    private $file;
    private $pegawai;
    private $jabatan;
    private $unitKerja;
    private $daftarPegawai;

    public function __construct(File $file, Pegawai $pegawai, Jabatan $jabatan, UnitKerja $unitKerja, DaftarPegawai $daftarPegawai)
    {
        $this->file = $file;
        $this->pegawai = $pegawai;
        $this->jabatan = $jabatan;
        $this->unitKerja = $unitKerja;
        $this->daftarPegawai = $daftarPegawai;
    }

    public function getPegawaiById(int $idPegawai): ?Object
    {
        $pegawai = $this->daftarPegawai->where('id_pegawai', $idPegawai)->first();
        return $pegawai;
    }

    public function searchPegawaiByNIP(string $query): Object
    {
        $pegawai = $this->daftarPegawai->where('nip', 'LIKE', '%'.$query.'%')->get();
        return $pegawai;
    }

    public function searchPegawaiByNama(string $query): Object
    {
        $pegawai = $this->pegawai->where('nama', 'LIKE', '%'.$query.'%')->get();
        return $pegawai;
    }

    public function getAll(): Object
    {
        $pegawai = $this->daftarPegawai->get();
        return $pegawai;
    }

    public function getPegawaiByUnitKerja(int $idUnitKerja): Object
    {
        $pegawai = $this->daftarPegawai->where('id_unit_kerja', $idUnitKerja)->get();
        return $pegawai;
    }

    public function getUnitKerjaById(int $idUnitKerja): ?Object
    {
        $unitKerja = $this->unitKerja->where('id_unit_kerja', $idUnitKerja)->first();
        return $unitKerja;
    }

    public function getListJabatan(): Object
    {
        $jabatan = $this->jabatan->orderBy('eselon', 'DESC')->orderBy('golongan', 'ASC')->get();
        return $jabatan;
    }

    public function getListUnitKerja(): Object
    {
        $unitKerja = $this->unitKerja->orderBy('daerah_unit_kerja', 'ASC')->get();
        return $unitKerja;
    }

    public function getPhotoByIdPegawai(int $idPegawai): ?Object
    {
        $file = $this->file->where('id_pegawai', $idPegawai)->first();
        return $file;
    }

    public function uploadPhoto(array $data): Object
    {
        $file = new $this->file;

        $data['photo']->storeAs("/pegawai " . $data['id_pegawai'] . "/", $data['file_name']);
        $file->file_name = $data['file_name'];
        $file->id_pegawai = $data['id_pegawai'];

        $file->save();
        return $file->fresh();
    }

    public function updatePhoto(array $data): Object
    {
        $file = $this->getPhotoByIdPegawai($data['id_pegawai']);

        Storage::disk('public')->deleteDirectory("/pegawai " . $data['id_pegawai']);

        $data['photo']->storeAs("/pegawai " . $data['id_pegawai'] . "/", $data['file_name']);
        $file->file_name = $data['file_name'];

        $file->save();
        return $file->fresh();
    }

    public function downloadPhoto(int $idPegawai): StreamedResponse
    {
        $file = $this->getPhotoByIdPegawai($idPegawai);

        $fileName = $file->file_name;
        return Storage::download("/pegawai " . $idPegawai . "/" . $fileName, $fileName);
    }

    public function deletePhoto(int $idPegawai): string
    {
        $file = $this->getPhotoByIdPegawai($idPegawai);

        Storage::disk('public')->deleteDirectory("/pegawai " . $idPegawai);
        $file->delete();

        return (string)$idPegawai;
    }

    public function getJabatanId(array $formData): ?int
    {
        $jabatan = $this->jabatan->where([
            ['eselon', $formData['eselon']],
            ['golongan', $formData['golongan']],
            ['nama_jabatan', $formData['nama_jabatan']]
        ])->first();

        if (!$jabatan) { throw new InvalidArgumentException("Jabatan tidak valid"); }

        return $jabatan->id_jabatan;
    }

    public function getUnitKerjaId(array $formData): ?int
    {
        $unitKerja = $this->unitKerja->where([
            ['daerah_unit_kerja', $formData['daerah_unit_kerja']],
            ['nama_unit_kerja', $formData['nama_unit_kerja']]
        ])->first();

        if (!$unitKerja) { throw new InvalidArgumentException("Unit Kerja tidak valid"); }

        return $unitKerja->id_unit_kerja;
    }

    public function storePegawai(array $formData): Object
    {
        $pegawai = new $this->pegawai;

        $pegawai->nama = $formData['nama'];
        $pegawai->tempat_lahir = $formData['tempat_lahir'];
        $pegawai->tanggal_lahir = $formData['tanggal_lahir'];
        $pegawai->jenis_kelamin = $formData['jenis_kelamin'];
        $pegawai->agama = $formData['agama'];
        $pegawai->alamat = $formData['alamat'];
        $pegawai->no_telepon = $formData['no_telepon'];

        $pegawai->save();
        return $pegawai->fresh();
    }

    public function storeDaftarPegawai(array $formData): Object
    {
        $daftarPegawai = new $this->daftarPegawai;

        $daftarPegawai->nip = $formData['nip'];
        $daftarPegawai->id_pegawai = $formData['id_pegawai'];
        $daftarPegawai->id_jabatan = $formData['id_jabatan'];
        $daftarPegawai->id_unit_kerja = $formData['id_unit_kerja'];
        $daftarPegawai->no_npwp = $formData['no_npwp'];

        $daftarPegawai->save();
        return $daftarPegawai->fresh();
    }

    public function updatePegawai(array $formData): Object
    {
        $pegawai = $this->pegawai->where('id_pegawai', $formData['id_pegawai'])->first();

        $pegawai->nama = $formData['nama'];
        $pegawai->tempat_lahir = $formData['tempat_lahir'];
        $pegawai->tanggal_lahir = $formData['tanggal_lahir'];
        $pegawai->jenis_kelamin = $formData['jenis_kelamin'];
        $pegawai->agama = $formData['agama'];
        $pegawai->alamat = $formData['alamat'];
        $pegawai->no_telepon = $formData['no_telepon'];

        $pegawai->save();
        return $pegawai->fresh();
    }

    public function updateDaftarPegawai(array $formData): Object
    {
        $daftarPegawai = $this->getPegawaiById($formData['id_pegawai']);

        $daftarPegawai->nip = $formData['nip'];
        $daftarPegawai->id_jabatan = $formData['id_jabatan'];
        $daftarPegawai->id_unit_kerja = $formData['id_unit_kerja'];
        $daftarPegawai->no_npwp = $formData['no_npwp'];

        $daftarPegawai->save();
        return $daftarPegawai->fresh();
    }

    public function delete(int $idPegawai): void
    {
        $photo = $this->getPhotoByIdPegawai($idPegawai);
        if ($photo) {
            Storage::disk('public')->deleteDirectory("/pegawai " . $idPegawai);
            $photo->delete();
        }
        $daftarPegawai = $this->getPegawaiById($idPegawai);
        $pegawai = $this->pegawai->where('id_pegawai', $idPegawai)->first();
        $daftarPegawai->delete();
        $pegawai->delete();
    }

    public function exportDaftarPegawai()
    {
        return (new PegawaiExport)->download('daftar_pegawai.xlsx');
    }
}