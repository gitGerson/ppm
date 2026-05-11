<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\CurriculumItem;
use App\Models\DetailSantri;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function home()
    {
        $curriculumItems = CurriculumItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $articles = Berita::query()
            ->where('visible', true)
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->with('user')
            ->limit(8)
            ->get();

        $startDate = now()->subMonthsNoOverflow(11)->startOfMonth();

        $santriMonthly = DetailSantri::query()
            ->whereNotNull('created_at')
            ->where('created_at', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key")
            ->selectRaw('MIN(created_at) as month_date')
            ->selectRaw("SUM(CASE WHEN jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) as male_count")
            ->selectRaw("SUM(CASE WHEN jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) as female_count")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->get()
            ->map(function ($row) {
                $monthDate = $row->month_date ? Carbon::parse($row->month_date) : null;

                return [
                    'month_key' => $row->month_key,
                    'month_label' => $monthDate
                        ? $monthDate->locale('id')->translatedFormat('MMMM Y')
                        : $row->month_key,
                    'male_count' => (int) $row->male_count,
                    'female_count' => (int) $row->female_count,
                ];
            })
            ->values();

        $santriDefaultMonthKey = data_get($santriMonthly->last(), 'month_key');

        return view('pages.home', [
            'curriculumItems' => $curriculumItems,
            'beritaItems' => $articles,
            'santriChart' => $santriMonthly,
            'santriDefaultMonthKey' => $santriDefaultMonthKey,
        ]);
    }

    public function pengumuman()
    {
        $announcements = Pengumuman::query()
            ->where('visible', true)
            ->orderByDesc('date')
            ->paginate(5);

        $firstWithDate = $announcements->getCollection()->firstWhere('date');

        $calendarFocus = $firstWithDate && $firstWithDate->date
            ? Carbon::parse($firstWithDate->date)
            : Carbon::now();

        $calendarYear = (int) $calendarFocus->year;
        $calendarMonth = (int) $calendarFocus->month;

        $firstOfMonth = Carbon::create($calendarYear, $calendarMonth, 1);
        $startWeekday = $firstOfMonth->dayOfWeekIso; // Monday = 1
        $daysInMonth = $firstOfMonth->daysInMonth;

        $weeks = [];
        $week = [];

        for ($i = 1; $i < $startWeekday; $i++) {
            $week[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $week[] = $day;
            if (count($week) === 7) {
                $weeks[] = $week;
                $week = [];
            }
        }

        if (count($week)) {
            while (count($week) < 7) {
                $week[] = null;
            }
            $weeks[] = $week;
        }

        $highlightedDays = $announcements->getCollection()
            ->filter(function ($announcement) use ($calendarYear, $calendarMonth) {
                if (empty($announcement->date)) {
                    return false;
                }

                $announcementDate = Carbon::parse($announcement->date);

                return $announcementDate->year === $calendarYear
                    && $announcementDate->month === $calendarMonth;
            })
            ->map(function ($announcement) {
                return Carbon::parse($announcement->date)->day;
            })
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('pages.pengumuman', [
            'announcements' => $announcements,
            'calendarFirstOfMonth' => $firstOfMonth,
            'calendarWeeks' => $weeks,
            'highlightedDays' => $highlightedDays,
        ]);
    }

    public function pendaftaran()
    {
        $detailSantri = DetailSantri::firstOrNew([
            'user_id' => auth()->id(),
        ]);

        return view('pages.pendaftaran', [
            'detailSantri' => $detailSantri,
        ]);
    }

    public function submitPendaftaran(Request $request)
    {
        $user = $request->user();
        $detailSantri = DetailSantri::firstOrNew([
            'user_id' => $user->id,
        ]);

        $yearUpperBound = now()->year + 1;

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['nullable', 'string', 'max:255'],
            'status_anak' => ['nullable', Rule::in(['Lengkap', 'Yatim', 'Piatu', 'Yatim Piatu'])],
            'anak_ke' => ['nullable', 'integer', 'min:0'],
            'NISN' => ['nullable', 'string', 'max:20'],
            'jumlah_saudara' => ['nullable', 'integer', 'min:0'],
            'NIK' => ['nullable', 'string', 'max:16'],
            'hobi' => ['nullable', 'string', 'max:500'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'cita-cita' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'tinggi_badan' => ['nullable', 'integer', 'min:0'],
            'status_bpjs' => ['nullable', Rule::in(['Tidak Memiliki', 'Memiliki', 'Memiliki KIS'])],
            'berat_badan' => ['nullable', 'integer', 'min:0'],
            'status_pip' => ['nullable', Rule::in(['Tidak Memiliki', 'Memiliki'])],
            'golongan_darah' => ['nullable', 'string', 'max:3'],
            'riwayat_penyakit' => ['nullable', 'string', 'max:255'],
            'ijazah_terakhir' => ['nullable', Rule::in(['SD', 'SMP', 'SMA'])],
            'is_mondok' => ['nullable', 'boolean'],
            'nama_sekolah_asal' => ['nullable', 'string', 'max:255'],
            'khatam' => ['nullable', 'array'],
            'khatam.*' => ['required', Rule::in(['Tidak Ada', 'Bukhori', 'Nasai', 'Muslim', 'Tirmidzi', 'Abu Daud', 'Ibnu Majah'])],
            'prodi_sekolah_asal' => ['nullable', 'string', 'max:255'],
            'tahun_masuk_sekolah' => ['nullable', 'integer', 'min:1900', 'max:'.$yearUpperBound],
            'tahun_masuk_ppm' => ['nullable', 'integer', 'min:1900', 'max:'.$yearUpperBound],
            'asalkelompoksambung' => ['nullable', 'string', 'max:255'],
            'desa' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rtrw' => ['nullable', 'string', 'max:20'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'kabupaten' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'kodepos' => ['nullable', 'string', 'max:10'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'media_sosial' => ['nullable', 'string', 'max:255'],
            'is_motor' => ['nullable', 'boolean'],
            'is_sepeda' => ['nullable', 'boolean'],
            'is_laptop' => ['nullable', 'boolean'],
            'sim' => ['nullable', Rule::in(['SIM A', 'SIM C', 'Tidak Punya'])],
            'image_ktp_path' => ['nullable', 'image', 'max:4096'],
            'image_pasfoto_path' => ['nullable', 'image', 'max:4096'],
            'no_kk' => ['nullable', 'string', 'max:16'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'pendidikan_ayah' => ['nullable', 'string', 'max:255'],
            'nik_ayah' => ['nullable', 'string', 'max:16'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:255'],
            'tempat_lahir_ayah' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir_ayah' => ['nullable', 'date'],
            'penghasilan_ayah' => ['nullable', 'integer', 'min:0'],
            'no_hp_ayah' => ['nullable', 'string', 'max:20'],
            'is_ayah_alive' => ['nullable', 'boolean'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'pendidikan_ibu' => ['nullable', 'string', 'max:255'],
            'nik_ibu' => ['nullable', 'string', 'max:16'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:255'],
            'tempat_lahir_ibu' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir_ibu' => ['nullable', 'date'],
            'penghasilan_ibu' => ['nullable', 'integer', 'min:0'],
            'no_hp_ibu' => ['nullable', 'string', 'max:20'],
            'is_ibu_alive' => ['nullable', 'boolean'],
        ]);

        $numericFields = [
            'anak_ke',
            'jumlah_saudara',
            'tinggi_badan',
            'berat_badan',
            'tahun_masuk_sekolah',
            'tahun_masuk_ppm',
            'penghasilan_ayah',
            'penghasilan_ibu',
        ];

        foreach ($numericFields as $numericField) {
            if (array_key_exists($numericField, $validated) && $validated[$numericField] !== null) {
                $validated[$numericField] = (int) $validated[$numericField];
            }
        }

        $booleanFields = [
            'is_mondok',
            'is_motor',
            'is_sepeda',
            'is_laptop',
            'is_ayah_alive',
            'is_ibu_alive',
        ];

        foreach ($booleanFields as $booleanField) {
            if (array_key_exists($booleanField, $validated)) {
                $validated[$booleanField] = $request->filled($booleanField)
                    ? $request->boolean($booleanField)
                    : null;
            }
        }

        if (array_key_exists('khatam', $validated)) {
            $validated['khatam'] = collect($validated['khatam'])
                ->filter()
                ->when(
                    in_array('Tidak Ada', $validated['khatam'], true),
                    fn ($collection) => collect(['Tidak Ada'])
                )
                ->unique()
                ->implode(', ');
        }

        if (array_key_exists('image_ktp_path', $validated) && $validated['image_ktp_path'] instanceof UploadedFile) {
            if ($detailSantri->image_ktp_path) {
                Storage::disk('public')->delete($detailSantri->image_ktp_path);
            }

            $validated['image_ktp_path'] = $validated['image_ktp_path']->store('detail-santri/ktp', 'public');
        } else {
            unset($validated['image_ktp_path']);
        }

        if (array_key_exists('image_pasfoto_path', $validated) && $validated['image_pasfoto_path'] instanceof UploadedFile) {
            if ($detailSantri->image_pasfoto_path) {
                Storage::disk('public')->delete($detailSantri->image_pasfoto_path);
            }

            $validated['image_pasfoto_path'] = $validated['image_pasfoto_path']->store('detail-santri/pasfoto', 'public');
        } else {
            unset($validated['image_pasfoto_path']);
        }

        $detailSantri->fill($validated);
        $detailSantri->user_id = $user->id;
        $detailSantri->save();

        return redirect()
            ->route('pendaftaran')
            ->with('status', 'Data pendaftaran berhasil disimpan.');
    }
}
