<x-dashboard-layout :title="'Pengumuman'">

    <style>
        /* =========================================================
           PENGUMUMAN - MODERN UI
        ========================================================= */

        .announcement-page {
            width: 100%;
        }

        /* SUCCESS ALERT */
        .success-alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 13px;
        }

        /* LIST */
        .announcement-list {
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        /* CARD */
        .announcement-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px 28px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            transition: all .25s ease;
        }

        .announcement-card:hover {
            border-color: #a7f3d0;
            box-shadow: 0 8px 24px rgba(11, 96, 43, 0.08);
            transform: translateY(-2px);
        }

        .announcement-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }

        .announcement-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #087f3e;
            border: 1px solid #d1fae5;
            font-size: 11px;
            font-weight: 700;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 500;
        }

        .date-badge svg {
            width: 13px;
            height: 13px;
        }

        .has-attachment-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #0b602b;
            font-size: 11px;
            font-weight: 600;
            background: #f0fdf4;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .has-attachment-badge svg {
            width: 12px;
            height: 12px;
        }

        .announcement-title-link {
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
        }

        .announcement-title {
            margin: 0;
            color: #1f2937;
            font-size: 18px;
            line-height: 1.4;
            font-weight: 750;
            word-break: break-word;
            transition: color .2s ease;
        }

        .announcement-title-link:hover .announcement-title {
            color: #0b602b;
        }

        .announcement-snippet {
            margin: 0;
            color: #4b5563;
            font-size: 14.5px;
            line-height: 1.75;
            word-break: break-word;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .inline-read-more {
            color: #0b602b;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s ease;
            white-space: nowrap;
        }

        .inline-read-more:hover {
            color: #084d22;
            text-decoration: underline;
        }

        /* ACTIONS */
        .announcement-admin-actions {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            transition: all .2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn svg {
            width: 17px;
            height: 17px;
        }

        .edit-btn {
            color: #059669;
        }

        .edit-btn:hover {
            background: #ecfdf5;
        }

        .delete-btn {
            color: #BA1A1A;
        }

        .delete-btn:hover {
            background: #fef2f2;
            color: #961313;
        }

        /* EMPTY */
        .empty-announcement {
            background: #ffffff;
            border: 1px dashed #d1d5db;
            border-radius: 20px;
            padding: 55px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            margin: 0 auto 14px;
            background: #f0fdf4;
            color: #86a894;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg {
            width: 26px;
            height: 26px;
        }

        .empty-announcement p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
        }

        /* PAGINATION */
        .announcement-pagination {
            margin-top: 22px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .announcement-card {
                padding: 18px 20px;
                border-radius: 17px;
            }

            .announcement-title {
                font-size: 16px;
            }

            .announcement-snippet {
                font-size: 13.5px;
            }
        }
    </style>


    <div class="announcement-page">

        {{-- =====================================================
             HEADER (STANDAR SEPERTI IZIN & LAPORAN KINERJA)
        ====================================================== --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Pusat Informasi & Pengumuman</h2>
                <p class="mt-1 text-sm text-gray-500">Pemberitahuan, kebijakan, dan pengumuman terbaru.</p>
            </div>

            @if (auth()->user()->role === 'admin')
                <div class="w-full sm:w-auto shrink-0 flex justify-end">
                    <a href="{{ route('pengumuman.create') }}"
                       class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 rounded-full bg-[#0b602b] hover:bg-[#084d22] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Pengumuman</span>
                    </a>
                </div>
            @endif
        </div>


        {{-- =====================================================
             SUCCESS MESSAGE
        ====================================================== --}}
        @if (session('success'))
            <div class="success-alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0z"/>
                    </svg>
                    <span>
                        {{ session('success') }}
                    </span>
                </div>

                <button type="button"
                        onclick="this.parentElement.remove()"
                        class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif


        {{-- =====================================================
             LIST PENGUMUMAN (FORMAT ARTIKEL DENGAN BACA SELENGKAPNYA...)
        ====================================================== --}}
        <div class="announcement-list">

            @forelse ($pengumuman as $item)

                <div class="announcement-card">

                    {{-- META & ADMIN ACTIONS --}}
                    <div class="announcement-top">
                        <div class="announcement-meta">
                            @if($item->kategori)
                                <span class="category-badge">
                                    {{ $item->kategori }}
                                </span>
                            @endif

                            <span class="date-badge">
                                <svg fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </span>

                            @if(\Carbon\Carbon::parse($item->tanggal)->isFuture() && !\Carbon\Carbon::parse($item->tanggal)->isToday())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Terjadwal
                                </span>
                            @endif

                            @if($item->file_lampiran)
                                <span class="has-attachment-badge">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    Ada Lampiran
                                </span>
                            @endif
                        </div>

                        {{-- ADMIN ACTIONS --}}
                        @if (auth()->user()->role === 'admin')
                            <div class="announcement-admin-actions">
                                <a href="{{ route('pengumuman.edit', $item->id) }}"
                                   class="action-btn edit-btn"
                                   title="Edit Pengumuman">
                                    <svg fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form action="{{ route('pengumuman.destroy', $item->id) }}"
                                      method="POST"
                                      class="confirm-form inline-block"
                                      data-title="Hapus Pengumuman"
                                      data-text="Apakah Anda yakin ingin menghapus pengumuman ini?"
                                      data-icon="warning"
                                      data-icon-color="#BA1A1A"
                                      data-confirm-color="#BA1A1A"
                                      data-confirm-text="Ya, Hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="action-btn delete-btn cursor-pointer"
                                            title="Hapus Pengumuman">
                                        <svg fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    {{-- JUDUL PENGUMUMAN --}}
                    <a href="{{ route('pengumuman.show', $item->id) }}"
                       class="announcement-title-link">
                        <h3 class="announcement-title">
                            {{ $item->judul }}
                        </h3>
                    </a>

                    {{-- CUPLIKAN ISI DENGAN LINK BACA SELENGKAPNYA DI BAWAHNYA --}}
                    <p class="announcement-snippet">
                        {{ \Illuminate\Support\Str::limit($item->isi, 288, '...') }}
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('pengumuman.show', $item->id) }}"
                           class="inline-read-more">Baca Selengkapnya...</a>
                    </div>

                </div>

            @empty

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}
                <div class="empty-announcement">
                    <div class="empty-icon">
                        <svg fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.5"
                                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <p>
                        Belum ada pengumuman yang dipublikasikan.
                    </p>
                </div>

            @endforelse

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if ($pengumuman->hasPages())
            <div class="announcement-pagination">
                {{ $pengumuman->links() }}
            </div>
        @endif

    </div>

</x-dashboard-layout>