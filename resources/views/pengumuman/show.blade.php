<x-dashboard-layout :title="$pengumuman->judul">

    <style>
        /* =========================================================
           DETAIL PENGUMUMAN - MODERN UI
        ========================================================= */

        .detail-page {
            width: 100%;
        }

        /* MAIN CONTENT CARD */
        .detail-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 26px 30px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            margin-bottom: 24px;
        }

        .detail-body-text {
            color: #334155;
            font-size: 15px;
            line-height: 1.85;
            white-space: pre-line;
            word-break: break-word;
        }

        /* ATTACHMENT CARD */
        .detail-attachment-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 24px 30px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            margin-bottom: 24px;
        }

        .attachment-header-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #0b602b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 16px;
        }

        .attachment-header-title svg {
            width: 17px;
            height: 17px;
        }

        .detail-attachment-box {
            padding: 16px 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .detail-attachment-info {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .detail-attachment-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #fee2e2;
            color: #ba1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .detail-attachment-icon svg {
            width: 22px;
            height: 22px;
        }

        .detail-attachment-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .detail-attachment-text h4 {
            margin: 0;
            color: #1e293b;
            font-size: 14px;
            font-weight: 700;
        }

        .detail-attachment-text p {
            margin: 3px 0 0;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .detail-download-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 12px;
            background: #0b602b;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 3px 10px rgba(11, 96, 43, 0.2);
            transition: all .2s ease;
        }

        .detail-download-btn:hover {
            background: #084d22;
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(11, 96, 43, 0.3);
        }

        .detail-download-btn svg {
            width: 15px;
            height: 15px;
        }

        .image-preview-container {
            margin-top: 18px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #0f172a;
            display: flex;
            justify-content: center;
        }

        .image-preview-container img {
            max-height: 480px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .detail-card,
            .detail-attachment-card {
                padding: 18px;
                border-radius: 17px;
            }

            .detail-attachment-box {
                flex-direction: column;
                align-items: stretch;
            }

            .detail-download-btn {
                justify-content: center;
                width: 100%;
            }
        }
    </style>

    <div class="detail-page">

        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}
        <div class="mb-5 text-sm">
            <a href="{{ route('pengumuman.index') }}" class="text-gray-500 hover:text-[#0b602b] transition-colors">Pengumuman</a>
            <span class="text-gray-300 mx-2">/</span>
            <span class="font-bold text-[#0b602b]">Detail Pengumuman</span>
        </div>


        <div class="detail-card">
            {{-- =====================================================
                 HEADER: JUDUL, KATEGORI, TANGGAL & AKSI
            ====================================================== --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-6 mb-6 border-b border-gray-100">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $pengumuman->judul }}</h2>
                    <div class="mt-2.5 flex items-center flex-wrap gap-2.5 text-sm text-gray-500">
                        @if ($pengumuman->kategori)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ $pengumuman->kategori }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($pengumuman->tanggal)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>

                @if (auth()->user()->role === 'admin')
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('pengumuman.edit', $pengumuman->id) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0b602b] hover:bg-[#084d22] text-white text-sm font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Edit</span>
                        </a>

                        <form action="{{ route('pengumuman.destroy', $pengumuman->id) }}"
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
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#BA1A1A] hover:bg-[#961313] text-white text-sm font-semibold shadow-xs transition cursor-pointer"
                                    style="background-color: #BA1A1A; color: #ffffff;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="detail-body-text">
                {{ $pengumuman->isi }}
            </div>
        </div>


        {{-- =====================================================
             BERKAS / LAMPIRAN
        ====================================================== --}}
        @if ($pengumuman->file_lampiran)
            @php
                $ext = strtolower(pathinfo($pengumuman->file_lampiran, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            @endphp

            <div class="detail-attachment-card">
                <div class="attachment-header-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <span>Lampiran & Berkas Pendukung</span>
                </div>

                <div class="detail-attachment-box">
                    <div class="detail-attachment-info">
                        @if ($isImage)
                            <img src="{{ Storage::url($pengumuman->file_lampiran) }}"
                                 alt="Lampiran"
                                 class="detail-attachment-preview">
                        @else
                            <div class="detail-attachment-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="detail-attachment-text">
                            <h4>Berkas Lampiran Pengumuman</h4>
                            <p>{{ strtoupper($ext) }} Dokumen</p>
                        </div>
                    </div>

                    <a href="{{ Storage::url($pengumuman->file_lampiran) }}"
                       target="_blank"
                       download
                       class="detail-download-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh / Buka Berkas</span>
                    </a>
                </div>

                @if ($isImage)
                    <div class="image-preview-container">
                        <img src="{{ Storage::url($pengumuman->file_lampiran) }}"
                             alt="Lampiran Lengkap">
                    </div>
                @endif
            </div>
        @endif

    </div>

</x-dashboard-layout>
