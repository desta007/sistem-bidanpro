@extends('layouts.admin')

@section('title', $inventory ? 'Edit Item' : 'Tambah Item')
@section('page-title', $inventory ? 'Edit Item Inventaris' : 'Tambah Item Baru')

@push('styles')
    <style>
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            max-width: 700px;
        }

        .form-header {
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .form-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .form-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 15px;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary);
            outline: none;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .form-footer {
            padding: 20px 24px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .error-message {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check input {
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
        }
    </style>
@endpush

@section('content')
    <div class="form-card">
        <div class="form-header">
            <h3>{{ $inventory ? 'Edit Data Item' : 'Form Tambah Item' }}</h3>
        </div>

        <form method="POST"
            action="{{ $inventory ? route('admin.inventory.update', $inventory) : route('admin.inventory.store') }}">
            @csrf
            @if($inventory) @method('PUT') @endif

            <div class="form-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kode Item <span class="required">*</span></label>
                        <input type="text" name="code" class="form-input" value="{{ old('code', $inventory?->code) }}"
                            placeholder="OBT-001" required>
                        @error('code')<span class="error-message">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Item <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $inventory?->name) }}"
                            required>
                        @error('name')<span class="error-message">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="obat" {{ old('category', $inventory?->category) === 'obat' ? 'selected' : '' }}>
                                Obat</option>
                            <option value="alkes" {{ old('category', $inventory?->category) === 'alkes' ? 'selected' : '' }}>
                                Alkes</option>
                            <option value="vitamin" {{ old('category', $inventory?->category) === 'vitamin' ? 'selected' : '' }}>Vitamin</option>
                            <option value="lainnya" {{ old('category', $inventory?->category) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satuan <span class="required">*</span></label>
                        <input type="text" name="unit" class="form-input" value="{{ old('unit', $inventory?->unit) }}"
                            placeholder="strip, botol, box" required>
                    </div>
                    @if(!$inventory)
                        <div class="form-group">
                            <label class="form-label">Stok Awal <span class="required">*</span></label>
                            <input type="number" name="stock" class="form-input" value="{{ old('stock', 0) }}" min="0" required>
                        </div>
                    @endif
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Stok Minimum <span class="required">*</span></label>
                        <input type="number" name="min_stock" class="form-input"
                            value="{{ old('min_stock', $inventory?->min_stock ?? 10) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="buy_price" class="form-input"
                            value="{{ old('buy_price', $inventory?->buy_price) }}" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Jual <span class="required">*</span></label>
                        <input type="number" name="sell_price" class="form-input"
                            value="{{ old('sell_price', $inventory?->sell_price) }}" min="0" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. Batch</label>
                        <input type="text" name="batch_number" class="form-input"
                            value="{{ old('batch_number', $inventory?->batch_number) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Kadaluarsa</label>
                        <input type="date" name="expired_date" class="form-input"
                            value="{{ old('expired_date', $inventory?->expired_date?->format('Y-m-d')) }}">
                    </div>
                </div>

                @if($inventory)
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $inventory->is_active) ? 'checked' : '' }}>
                            <label for="is_active">Item aktif</label>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-footer">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary"><i class="ri-arrow-left-line"></i>
                    Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i>
                    {{ $inventory ? 'Simpan' : 'Tambah Item' }}</button>
            </div>
        </form>
    </div>
@endsection