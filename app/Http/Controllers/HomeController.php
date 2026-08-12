<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitPendaftaranRequest;
use App\Jobs\SendPendaftaranWhatsapp;
use App\Models\Berita;
use App\Models\CurriculumItem;
use App\Models\DetailSantri;
use App\Models\Event;
use App\Models\Pengumuman;
use App\Models\Slider;
use App\Support\Fonnte;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function home()
    {
        $heroSlides = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Slider $slider): array => [
                'image' => $slider->imageUrl(),
                'alt' => $slider->alt_text ?: $slider->title,
            ])
            ->whenEmpty(fn () => collect([
                [
                    'image' => asset('images/assets/hero.png'),
                    'alt' => 'Kegiatan hero PPM',
                ],
                [
                    'image' => asset('images/assets/hero2.png'),
                    'alt' => 'Suasana pembelajaran PPM',
                ],
            ]))
            ->values();

        $curriculumItems = CurriculumItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $categoryCounts = Berita::query()
            ->where('visible', true)
            ->select('category')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('category')
            ->pluck('aggregate', 'category');

        $latestBeritaByCategory = Berita::query()
            ->where('visible', true)
            ->whereIn('category', array_keys(Berita::categoryOptions()))
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get()
            ->unique('category')
            ->keyBy('category');

        $beritaCategories = collect(Berita::categoryOptions())
            ->map(function (string $label, string $category) use ($categoryCounts, $latestBeritaByCategory): array {
                return [
                    'label' => $label,
                    'slug' => Berita::categorySlug($category),
                    'count' => (int) ($categoryCounts[$category] ?? 0),
                    'latest' => $latestBeritaByCategory->get($category),
                ];
            })
            ->filter(fn (array $category): bool => $category['count'] > 0)
            ->values();

        $events = Event::query()
            ->latest()
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
            'heroSlides' => $heroSlides,
            'curriculumItems' => $curriculumItems,
            'beritaCategories' => $beritaCategories,
            'eventItems' => $events,
            'santriChart' => $santriMonthly,
            'santriDefaultMonthKey' => $santriDefaultMonthKey,
        ]);
    }

    public function beritaCategory(string $category)
    {
        $categoryName = Berita::categoryFromSlug($category);

        abort_if($categoryName === null, 404);

        $articles = Berita::query()
            ->where('visible', true)
            ->where('category', $categoryName)
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->with('user')
            ->paginate(9);

        return view('pages.berita-category', [
            'categoryName' => $categoryName,
            'beritaItems' => $articles,
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

    public function submitPendaftaran(SubmitPendaftaranRequest $request)
    {
        $user = $request->user();
        $detailSantri = DetailSantri::firstOrNew([
            'user_id' => $user->id,
        ]);

        $validated = $request->validated();

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

        // Confirmation goes out once, on the first completed submit; later edits
        // of the same form find the timestamp already set.
        if (Fonnte::isEnabled() && $detailSantri->pendaftaran_notified_at === null) {
            SendPendaftaranWhatsapp::dispatch($detailSantri->getKey());
        }

        return redirect()
            ->route('pendaftaran')
            ->with('status', 'Data pendaftaran berhasil disimpan.');
    }
}
