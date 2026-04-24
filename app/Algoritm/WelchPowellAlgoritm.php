<?php

namespace App\Algoritm;

// ─── Data Classes ─────────────────────────────────────────────────────────────

class Dosen
{
    public function __construct(
        public int $id,
        public string $nama
    ) {
    }
}

class Teknisi
{
    public function __construct(
        public int $id,
        public string $nama
    ) {
    }
}

class MataKuliah
{
    public bool $isWorkshop;
    public int $pertemuanPerMinggu;
    public int $durasiJam;

    public function __construct(
        public string $kode,
        public string $nama,
        public Dosen $dosen,
        public int $sksTeori,
        public int $sksPraktek,
        public int $semester,
        public string $kelas = ''
    ) {
        $this->isWorkshop = ($sksPraktek === 4);
        $this->pertemuanPerMinggu = $this->isWorkshop ? 2 : 1;

        $teori = $sksTeori * DurasiConfig::$jamPerSksTeori;
        $praktik = $this->isWorkshop
            ? intdiv($sksPraktek, 2) * DurasiConfig::$jamPerSksPraktik
            : $sksPraktek * DurasiConfig::$jamPerSksPraktik;

        $this->durasiJam = $teori + $praktik;
    }

    public function getNamaLengkap(): string
    {
        $kelasLabel = $this->kelas !== '' ? " {$this->kelas}" : '';
        return $this->pertemuanPerMinggu > 1
            ? "{$this->nama}{$kelasLabel}"
            : $this->nama;
    }
}

class DurasiConfig
{
    public static int $jamPerSksTeori = 1;
    public static int $jamPerSksPraktik = 2;
}

class Hari
{
    public array $jamPelajaran; // list of int hours
    public array $jamIstirahat; // range as [start, end) or []

    public function __construct(
        public string $nama,
        public int $jamMulai,
        public int $jamSelesai,
        public ?int $jamIstirahatMulai = null,
        public ?int $jamIstirahatSelesai = null
    ) {
        $istirahat = [];
        if ($jamIstirahatMulai !== null && $jamIstirahatSelesai !== null) {
            for ($i = $jamIstirahatMulai; $i < $jamIstirahatSelesai; $i++) {
                $istirahat[] = $i;
            }
        }
        $this->jamIstirahat = $istirahat;

        $pelajaran = [];
        for ($i = $jamMulai; $i < $jamSelesai; $i++) {
            if (!in_array($i, $istirahat)) {
                $pelajaran[] = $i;
            }
        }
        $this->jamPelajaran = $pelajaran;
    }
}

class Slot
{
    public function __construct(
        public Hari $hari,
        public int $jamMulai,
        public int $jamSelesai
    ) {
    }

    public function __toString(): string
    {
        return "{$this->hari->nama} {$this->jamMulai}:00-{$this->jamSelesai}:00";
    }
}

class Ruangan
{
    public function __construct(
        public string $nama,
        public array $supports // ['TEORI', 'PRAKTIK']
    ) {
    }
}

class JadwalItem
{
    public function __construct(
        public MataKuliah $mataKuliah,
        public Ruangan $ruangan,
        public Slot $slot,
        public int $pertemuanKe = 1,
        public ?Teknisi $teknisi = null
    ) {
    }

    public function getNamaLengkap(): string
    {
        $kelasLabel = $this->mataKuliah->kelas !== '' ? " {$this->mataKuliah->kelas}" : '';
        return $this->mataKuliah->pertemuanPerMinggu > 1
            ? "{$this->mataKuliah->nama}{$kelasLabel} (Pertemuan {$this->pertemuanKe})"
            : $this->mataKuliah->nama;
    }
}

class MKDegree
{
    public function __construct(
        public MataKuliah $mk,
        public int $pertemuan,
        public int $degree
    ) {
    }
}

class SlotPriority
{
    public function __construct(
        public Slot $slot,
        public int $priority
    ) {
    }
}

// ─── Conflict Index ───────────────────────────────────────────────────────────

class ConflictIndex
{
    public array $byHari = [];
    public array $byDosen = [];
    public array $byRuangan = [];
    public array $bySemester = [];
    public array $byTeknisi = [];

    public function add(JadwalItem $item): void
    {
        $this->byHari[$item->slot->hari->nama][] = $item;
        $this->byDosen[$item->mataKuliah->dosen->id][] = $item;
        $this->byRuangan[$item->ruangan->nama][] = $item;
        $this->bySemester[$item->mataKuliah->semester][] = $item;
        if ($item->teknisi) {
            $this->byTeknisi[$item->teknisi->id][] = $item;
        }
    }

    public function candidates(MataKuliah $mk, Slot $slot, Ruangan $ruangan): array
    {
        $result = [];
        foreach ($this->byHari[$slot->hari->nama] ?? [] as $i)
            $result[spl_object_id($i)] = $i;
        foreach ($this->byDosen[$mk->dosen->id] ?? [] as $i)
            $result[spl_object_id($i)] = $i;
        foreach ($this->byRuangan[$ruangan->nama] ?? [] as $i)
            $result[spl_object_id($i)] = $i;
        foreach ($this->bySemester[$mk->semester] ?? [] as $i)
            $result[spl_object_id($i)] = $i;
        return array_values($result);
    }

    public function remove(JadwalItem $item): void
    {
        $this->byHari[$item->slot->hari->nama] = $this->removeItem($this->byHari[$item->slot->hari->nama] ?? [], $item);
        $this->byDosen[$item->mataKuliah->dosen->id] = $this->removeItem($this->byDosen[$item->mataKuliah->dosen->id] ?? [], $item);
        $this->byRuangan[$item->ruangan->nama] = $this->removeItem($this->byRuangan[$item->ruangan->nama] ?? [], $item);
        $this->bySemester[$item->mataKuliah->semester] = $this->removeItem($this->bySemester[$item->mataKuliah->semester] ?? [], $item);
        if ($item->teknisi) {
            $this->byTeknisi[$item->teknisi->id] = $this->removeItem($this->byTeknisi[$item->teknisi->id] ?? [], $item);
        }
    }

    private function removeItem(array $list, JadwalItem $target): array
    {
        return array_values(array_filter($list, fn($i) => $i !== $target));
    }
}

// ─── Main Algorithm ───────────────────────────────────────────────────────────

class WelchPowellAlgorithm
{
    private array $daftarHari = [];
    private array $daftarMataKuliah = [];
    private array $slotCache = [];

    private const HARI_ORDER = [
        'senin' => 1,
        'selasa' => 2,
        'rabu' => 3,
        'kamis' => 4,
        'jumat' => 5,
        'sabtu' => 6,
        'minggu' => 7,
    ];

    // ─── Slot Generation ─────────────────────────────────────────────────────

    private function generateSlotsForDuration(int $durasi): array
    {
        if ($durasi <= 0)
            return [];
        if (isset($this->slotCache[$durasi]))
            return $this->slotCache[$durasi];

        $slots = [];
        foreach ($this->daftarHari as $hari) {
            $jamList = $hari->jamPelajaran;
            $len = count($jamList);
            if ($len < $durasi)
                continue;

            for ($i = 0; $i <= $len - $durasi; $i++) {
                $window = array_slice($jamList, $i, $durasi);
                $contiguous = true;
                for ($j = 0; $j < count($window) - 1; $j++) {
                    if ($window[$j + 1] !== $window[$j] + 1) {
                        $contiguous = false;
                        break;
                    }
                }
                if ($contiguous) {
                    $slots[] = new Slot($hari, $window[0], $window[count($window) - 1] + 1);
                }
            }
        }

        $this->slotCache[$durasi] = $slots;
        return $slots;
    }

    // ─── Conflict Detection ───────────────────────────────────────────────────

    private function isTimeOverlap(Slot $s1, Slot $s2): bool
    {
        if ($s1->hari !== $s2->hari)
            return false;
        return $s1->jamMulai < $s2->jamSelesai && $s2->jamMulai < $s1->jamSelesai;
    }

    private function findTeknisi(Slot $slot, array $daftarTeknisi, ConflictIndex $index): ?Teknisi
    {
        foreach ($daftarTeknisi as $teknisi) {
            $sibuk = false;
            foreach ($index->byTeknisi[$teknisi->id] ?? [] as $item) {
                if ($this->isTimeOverlap($item->slot, $slot)) {
                    $sibuk = true;
                    break;
                }
            }
            if (!$sibuk)
                return $teknisi;
        }
        return null;
    }

    private function getRuanganCocok(MataKuliah $mk, array $semuaRuangan): array
    {
        $filtered = array_filter($semuaRuangan, function (Ruangan $r) use ($mk) {
            return $mk->sksPraktek <= 1
                ? in_array('TEORI', $r->supports)
                : in_array('PRAKTIK', $r->supports);
        });

        usort($filtered, function (Ruangan $a, Ruangan $b) use ($mk) {
            return $this->ruanganScore($b, $mk) <=> $this->ruanganScore($a, $mk);
        });

        return array_values($filtered);
    }

    private function ruanganScore(Ruangan $r, MataKuliah $mk): int
    {
        if ($mk->sksPraktek > 1) {
            if (stripos($r->nama, 'Lab') !== false)
                return 4;
            if ($r->supports === ['PRAKTIK'])
                return 3;
            if (in_array('PRAKTIK', $r->supports))
                return 2;
            return 1;
        }
        if ($r->supports === ['TEORI'])
            return 3;
        if (count($r->supports) === 2)
            return 2;
        return 1;
    }

    private function isConflict(
        MataKuliah $mk1,
        MataKuliah $mk2,
        Slot $slot1,
        Slot $slot2,
        Ruangan $r1,
        Ruangan $r2
    ): bool {
        if ($mk1 === $mk2 && $slot1->hari === $slot2->hari)
            return true;
        if (!$this->isTimeOverlap($slot1, $slot2))
            return false;
        if ($mk1->dosen->id === $mk2->dosen->id)
            return true;
        if ($r1->nama === $r2->nama)
            return true;
        if ($mk1->semester !== $mk2->semester)
            return false;

        $mk1Umum = $mk1->kelas === '';
        $mk2Umum = $mk2->kelas === '';
        if ($mk1Umum || $mk2Umum)
            return true;
        if ($mk1->kelas !== $mk2->kelas)
            return false;
        return true;
    }

    // ─── Degree Computation ───────────────────────────────────────────────────

    private function hitungSemuaDegree(array $daftarMK): array
    {
        $degrees = [];
        foreach ($daftarMK as $mk) {
            $degree = 0;
            foreach ($daftarMK as $other) {
                if ($other === $mk)
                    continue;
                if ($other->dosen->id === $mk->dosen->id) {
                    $degree += 3 * $other->pertemuanPerMinggu;
                    continue;
                }
                if ($mk->isWorkshop && $other->isWorkshop) {
                    $degree += 1 * $other->pertemuanPerMinggu;
                    continue;
                }
                if ($mk->semester === $other->semester) {
                    $degree += $other->pertemuanPerMinggu;
                }
            }
            $degrees[spl_object_id($mk)] = $degree;
        }
        return $degrees;
    }

    // ─── Slot Priority ────────────────────────────────────────────────────────

    private function hitungPrioritasSlot(
        Slot $slot,
        MataKuliah $mk,
        array $jadwalPerHari,
        int $pertemuanKe,
        ?Slot $pertemuan1Slot
    ): int {
        $prioritas = 100;
        $jadwalHariIni = $jadwalPerHari[$slot->hari->nama] ?? [];

        if ($pertemuanKe === 2 && $pertemuan1Slot !== null) {
            if ($pertemuan1Slot->hari === $slot->hari)
                $prioritas -= 50;
            else
                $prioritas += 10;
        }

        $jadwalTerkait = array_filter($jadwalHariIni, function (JadwalItem $item) use ($mk) {
            return $item->mataKuliah->dosen->id === $mk->dosen->id ||
                ($item->mataKuliah->semester === $mk->semester &&
                    ($item->mataKuliah->kelas === $mk->kelas ||
                        $item->mataKuliah->kelas === '' ||
                        $mk->kelas === ''));
        });
        $jadwalTerkait = array_values($jadwalTerkait);
        $terkaitCount = count($jadwalTerkait);

        if ($terkaitCount > 0) {
            $prioritas += 20;
            if ($terkaitCount >= 3)
                $prioritas -= ($terkaitCount - 2) * 20;
            else
                $prioritas -= $terkaitCount * 5;
        } else {
            $prioritas -= 15;
        }

        if ($mk->isWorkshop) {
            $totalWorkshop = count(array_filter($jadwalHariIni, fn($i) => $i->mataKuliah->isWorkshop));
            $prioritas -= $totalWorkshop * 50;
        } else {
            $prioritas -= count($jadwalHariIni) * 2;
        }

        $firstJam = $slot->hari->jamPelajaran[0] ?? $slot->hari->jamMulai;
        $istirahatSelesai = !empty($slot->hari->jamIstirahat)
            ? (max($slot->hari->jamIstirahat) + 1)
            : null;

        $isAwalHari = $slot->jamMulai === $firstJam;
        $isSetelahIstirahat = $istirahatSelesai !== null && $slot->jamMulai === $istirahatSelesai;
        if ($isAwalHari || $isSetelahIstirahat)
            $prioritas += 10;

        foreach ($jadwalTerkait as $existing) {
            $isNempel = $existing->slot->jamSelesai === $slot->jamMulai
                || $slot->jamSelesai === $existing->slot->jamMulai;

            $isNempelIstirahat = false;
            if (!empty($slot->hari->jamIstirahat)) {
                $brFirst = min($slot->hari->jamIstirahat);
                $brLast = max($slot->hari->jamIstirahat) + 1;
                $isNempelIstirahat =
                    ($existing->slot->jamSelesai === $brFirst && $slot->jamMulai === $brLast) ||
                    ($slot->jamSelesai === $brFirst && $existing->slot->jamMulai === $brLast);
            }

            if ($isNempel || $isNempelIstirahat)
                $prioritas += 35;
        }

        $allStarts = array_map(
            fn($i) => [$i->slot->jamMulai, $i->slot->jamSelesai],
            $jadwalTerkait
        );
        $allStarts[] = [$slot->jamMulai, $slot->jamSelesai];
        usort($allStarts, fn($a, $b) => $a[0] <=> $b[0]);

        $gapPenalty = 0;
        for ($i = 0; $i < count($allStarts) - 1; $i++) {
            $end = $allStarts[$i][1];
            $start = $allStarts[$i + 1][0];
            if ($start > $end) {
                $brInGap = 0;
                if (!empty($slot->hari->jamIstirahat)) {
                    $brFirst = min($slot->hari->jamIstirahat);
                    $brLast = max($slot->hari->jamIstirahat) + 1;
                    $brInGap = max(0, min($start, $brLast) - max($end, $brFirst));
                }
                $gapPenalty += max(0, $start - $end - $brInGap) * 10;
            }
        }
        $prioritas -= $gapPenalty;

        if ($terkaitCount > 0) {
            $minStart = min($slot->jamMulai, min(array_map(fn($i) => $i->slot->jamMulai, $jadwalTerkait)));
            $maxEnd = max($slot->jamSelesai, max(array_map(fn($i) => $i->slot->jamSelesai, $jadwalTerkait)));
            $usedTime = ($slot->jamSelesai - $slot->jamMulai) +
                array_sum(array_map(fn($i) => $i->slot->jamSelesai - $i->slot->jamMulai, $jadwalTerkait));
            $span = max(1, $maxEnd - $minStart);
            $prioritas += intdiv($usedTime * 100 / $span, 8);
        }

        return $prioritas;
    }

    // ─── Main Scheduling ─────────────────────────────────────────────────────

    public function buatJadwal(
        array $daftarMataKuliah,
        array $daftarRuangan,
        array $daftarHari,
        array $daftarTeknisi = [],
        ?int $overrideDurasiWorkshop = null
    ): array // [bool $isSuccess, JadwalItem[] $jadwal, UnscheduledItem[] $unscheduled]
    {
        $this->daftarMataKuliah = $daftarMataKuliah;
        $this->daftarHari = $daftarHari;
        $this->slotCache = [];

        $degreeMap = $this->hitungSemuaDegree($daftarMataKuliah);

        // Expand MK by pertemuanPerMinggu
        $expandedMK = [];
        foreach ($daftarMataKuliah as $mk) {
            for ($p = 1; $p <= $mk->pertemuanPerMinggu; $p++) {
                $expandedMK[] = new MKDegree($mk, $p, $degreeMap[spl_object_id($mk)] ?? 0);
            }
        }

        usort($expandedMK, function (MKDegree $a, MKDegree $b) {
            if ($b->mk->durasiJam !== $a->mk->durasiJam)
                return $b->mk->durasiJam <=> $a->mk->durasiJam;
            if ($b->degree !== $a->degree)
                return $b->degree <=> $a->degree;
            return ($b->mk->kelas === '' ? 1 : 0) <=> ($a->mk->kelas === '' ? 1 : 0);
        });

        $jadwal = [];
        $conflictIndex = new ConflictIndex();
        $isSuccess = true;
        $unscheduledList = [];
        $jadwalPerHari = [];
        $pertemuan1SlotMap = [];

        foreach ($expandedMK as $mkDegree) {
            [$mk, $pertemuanKe, $degree] = [$mkDegree->mk, $mkDegree->pertemuan, $mkDegree->degree];

            $durasi = ($mk->isWorkshop && $overrideDurasiWorkshop !== null)
                ? $overrideDurasiWorkshop
                : $mk->durasiJam;

            $availableSlots = $this->generateSlotsForDuration($durasi);

            if (empty($availableSlots)) {
                $isSuccess = false;
                $unscheduledList[] = [
                    'nama_mk' => $mk->getNamaLengkap(),
                    'semester' => $mk->semester,
                    'sks' => $mk->sksTeori + $mk->sksPraktek,
                    'nama_dosen' => $mk->dosen->nama,
                    'pertemuan_ke' => $pertemuanKe,
                    'alasan' => "Tidak ada slot tersedia untuk durasi {$mk->durasiJam} jam",
                    'degree' => $degree,
                ];
                continue;
            }

            $pertemuan1Slot = ($pertemuanKe === 2) ? ($pertemuan1SlotMap[spl_object_id($mk)] ?? null) : null;

            $slotsWithPriority = array_map(
                fn(Slot $slot) => new SlotPriority(
                    $slot,
                    $this->hitungPrioritasSlot($slot, $mk, $jadwalPerHari, $pertemuanKe, $pertemuan1Slot)
                ),
                $availableSlots
            );

            usort($slotsWithPriority, function (SlotPriority $a, SlotPriority $b) {
                $hariA = array_search($a->slot->hari->nama, array_column($this->daftarHari, 'nama'));
                $hariB = array_search($b->slot->hari->nama, array_column($this->daftarHari, 'nama'));
                if ($hariA !== $hariB)
                    return $hariA <=> $hariB;
                if ($a->slot->jamMulai !== $b->slot->jamMulai)
                    return $a->slot->jamMulai <=> $b->slot->jamMulai;
                return $b->priority <=> $a->priority;
            });

            $ruanganCocok = $this->getRuanganCocok($mk, $daftarRuangan);
            $berhasil = false;

            foreach ($slotsWithPriority as $sp) {
                if ($berhasil)
                    break;
                foreach ($ruanganCocok as $ruangan) {
                    $candidates = $conflictIndex->candidates($mk, $sp->slot, $ruangan);
                    $adaKonflik = false;
                    foreach ($candidates as $existing) {
                        if ($this->isConflict($mk, $existing->mataKuliah, $sp->slot, $existing->slot, $ruangan, $existing->ruangan)) {
                            $adaKonflik = true;
                            break;
                        }
                    }

                    if (!$adaKonflik) {
                        $teknisi = $mk->isWorkshop
                            ? $this->findTeknisi($sp->slot, $daftarTeknisi, $conflictIndex)
                            : null;

                        $newItem = new JadwalItem($mk, $ruangan, $sp->slot, $pertemuanKe, $teknisi);
                        $jadwal[] = $newItem;
                        $conflictIndex->add($newItem);
                        $jadwalPerHari[$sp->slot->hari->nama][] = $newItem;

                        if ($pertemuanKe === 1 && $mk->isWorkshop) {
                            $pertemuan1SlotMap[spl_object_id($mk)] = $sp->slot;
                        }

                        $berhasil = true;
                        break;
                    }
                }
            }

            if (!$berhasil) {
                $isSuccess = false;
                $unscheduledList[] = [
                    'nama_mk' => $mk->getNamaLengkap(),
                    'semester' => $mk->semester,
                    'sks' => $mk->sksTeori + $mk->sksPraktek,
                    'nama_dosen' => $mk->dosen->nama,
                    'pertemuan_ke' => $pertemuanKe,
                    'alasan' => 'Konflik tidak dapat diselesaikan (dosen/ruangan/semester)',
                    'degree' => $degree,
                ];
            }
        }

        // ─── Compaction Pass ─────────────────────────────────────────────────
        $adaPerubahan = true;
        $compactionLoop = 0;

        while ($adaPerubahan && $compactionLoop < 3) {
            $adaPerubahan = false;
            $compactionLoop++;

            $snapshot = $jadwal;
            usort($snapshot, function (JadwalItem $a, JadwalItem $b) {
                $hariA = array_search($a->slot->hari->nama, array_column($this->daftarHari, 'nama'));
                $hariB = array_search($b->slot->hari->nama, array_column($this->daftarHari, 'nama'));
                if ($hariA !== $hariB)
                    return $hariB <=> $hariA;
                return $b->slot->jamMulai <=> $a->slot->jamMulai;
            });

            foreach ($snapshot as $item) {
                $jadwal = array_values(array_filter($jadwal, fn($i) => $i !== $item));
                $conflictIndex->remove($item);
                $jadwalPerHari[$item->slot->hari->nama] = array_values(
                    array_filter($jadwalPerHari[$item->slot->hari->nama] ?? [], fn($i) => $i !== $item)
                );

                $durasi = $item->slot->jamSelesai - $item->slot->jamMulai;
                $available = $this->generateSlotsForDuration($durasi);
                $ruanganCocok = $this->getRuanganCocok($item->mataKuliah, $daftarRuangan);

                $slotBaru = null;
                $hariNames = array_map(fn($h) => $h->nama, $this->daftarHari);
                $currHariIdx = array_search($item->slot->hari->nama, $hariNames);

                foreach ($available as $kandidatSlot) {
                    $candHariIdx = array_search($kandidatSlot->hari->nama, $hariNames);
                    if ($candHariIdx > $currHariIdx)
                        continue;
                    if ($candHariIdx === $currHariIdx && $kandidatSlot->jamMulai >= $item->slot->jamMulai)
                        continue;

                    if ($item->mataKuliah->pertemuanPerMinggu > 1) {
                        $hariSama = array_filter(
                            $jadwal,
                            fn($i) =>
                            $i->mataKuliah === $item->mataKuliah && $i->slot->hari === $kandidatSlot->hari
                        );
                        if (!empty($hariSama))
                            continue;
                    }

                    foreach ($ruanganCocok as $kandidatRuangan) {
                        $candidates = $conflictIndex->candidates($item->mataKuliah, $kandidatSlot, $kandidatRuangan);
                        $adaKonflikBaru = false;
                        foreach ($candidates as $existing) {
                            if ($this->isConflict($item->mataKuliah, $existing->mataKuliah, $kandidatSlot, $existing->slot, $kandidatRuangan, $existing->ruangan)) {
                                $adaKonflikBaru = true;
                                break;
                            }
                        }
                        if (!$adaKonflikBaru) {
                            $teknisi = $item->mataKuliah->isWorkshop
                                ? $this->findTeknisi($kandidatSlot, $daftarTeknisi, $conflictIndex)
                                : null;
                            $slotBaru = new JadwalItem($item->mataKuliah, $kandidatRuangan, $kandidatSlot, $item->pertemuanKe, $teknisi);
                            break 2;
                        }
                    }
                }

                $targetItem = $slotBaru ?? $item;
                if ($slotBaru !== null)
                    $adaPerubahan = true;

                $jadwal[] = $targetItem;
                $conflictIndex->add($targetItem);
                $jadwalPerHari[$targetItem->slot->hari->nama][] = $targetItem;
            }
        }

        // Sort final
        $hariNames = array_map(fn($h) => $h->nama, $this->daftarHari);
        usort($jadwal, function (JadwalItem $a, JadwalItem $b) use ($hariNames) {
            $hariA = array_search($a->slot->hari->nama, $hariNames);
            $hariB = array_search($b->slot->hari->nama, $hariNames);
            if ($hariA !== $hariB)
                return $hariA <=> $hariB;
            return $a->slot->jamMulai <=> $b->slot->jamMulai;
        });

        return [$isSuccess, $jadwal, $unscheduledList];
    }

    // ─── JSON Output ─────────────────────────────────────────────────────────

    public function jadwalToArray(
        string $title,
        bool $isSuccess,
        array $jadwal,
        string $semester,
        array $daftarMataKuliah,
        array $referensiRombel,
        array $unscheduledItems = []
    ): array {
        $jadwalData = array_map(fn(JadwalItem $item) => [
            'nama_jadwal' => $item->getNamaLengkap(),
            'hari' => $item->slot->hari->nama,
            'jam_mulai' => $item->slot->jamMulai,
            'jam_selesai' => $item->slot->jamSelesai,
            'nama_dosen' => $item->mataKuliah->dosen->nama,
            'semester' => $item->mataKuliah->semester,
            'sks' => $item->mataKuliah->sksTeori + $item->mataKuliah->sksPraktek,
            'nama_ruangan' => $item->ruangan->nama,
            'nama_teknisi' => $item->teknisi?->nama,
        ], $jadwal);

        // Group by ruangan
        $byRuangan = [];
        foreach ($jadwalData as $d) {
            $byRuangan[$d['nama_ruangan']][] = $d;
        }
        ksort($byRuangan);
        $groupedByRuangan = [];
        foreach ($byRuangan as $ruangan => $items) {
            usort(
                $items,
                fn($a, $b) =>
                (self::HARI_ORDER[strtolower($a['hari'])] ?? 99) <=> (self::HARI_ORDER[strtolower($b['hari'])] ?? 99)
                ?: $a['jam_mulai'] <=> $b['jam_mulai']
            );
            $groupedByRuangan[] = ['nama' => $ruangan, 'items' => $items];
        }

        // Group by hari
        $byHari = [];
        foreach ($jadwalData as $d) {
            $byHari[$d['hari']][] = $d;
        }
        $groupedByHari = [];
        foreach ($byHari as $hari => $items) {
            $groupedByHari[] = ['nama' => $hari, 'items' => $items];
        }
        usort(
            $groupedByHari,
            fn($a, $b) =>
            (self::HARI_ORDER[strtolower($a['nama'])] ?? 99) <=> (self::HARI_ORDER[strtolower($b['nama'])] ?? 99)
        );

        return [
            'title' => $title,
            'is_success' => $isSuccess,
            'jadwal' => $groupedByRuangan,
            'jadwal_view' => $groupedByHari,
            'semester' => $semester,
            'unscheduled_count' => count($unscheduledItems),
            'unscheduled_items' => $unscheduledItems,
        ];
    }
}
