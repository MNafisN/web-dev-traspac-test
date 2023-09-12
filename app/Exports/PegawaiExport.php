<?php

namespace App\Exports;

use App\Models\DaftarPegawai;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

class PegawaiExport implements FromQuery, ShouldAutoSize, WithTitle, WithStyles, WithHeadings, WithColumnFormatting
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return DaftarPegawai::all();
    // }

    /**
     * @return Builder
     */
    public function query()
    {
        return DaftarPegawai::query()
            ->join('pegawai', 'daftar_pegawai.id_pegawai', '=', 'pegawai.id_pegawai')
            ->join('jabatan', 'daftar_pegawai.id_jabatan', '=', 'jabatan.id_jabatan')
            ->join('unit_kerja', 'daftar_pegawai.id_unit_kerja', '=', 'unit_kerja.id_unit_kerja')
            ->select(
                'daftar_pegawai.nip',
                'pegawai.nama',
                'pegawai.tempat_lahir',
                'pegawai.alamat',
                'pegawai.tanggal_lahir',
                'pegawai.jenis_kelamin',
                'jabatan.golongan',
                'jabatan.eselon',
                'jabatan.nama_jabatan',
                'unit_kerja.daerah_unit_kerja',
                'pegawai.agama',
                'unit_kerja.nama_unit_kerja',
                'pegawai.no_telepon',
                'daftar_pegawai.no_npwp'
            );
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'DAFTAR PEGAWAI (nama institusi)';  
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_WHITE],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => Color::COLOR_BLUE],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Tempat Lahir',
            'Alamat',
            'Tgl Lahir',
            'L/P',
            'Gol',
            'Eselon',
            'Jabatan',
            'Tempat Tugas',
            'Agama',
            'Unit Kerja',
            'No. HP',
            'NPWP'
        ];
    }

    // why
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
