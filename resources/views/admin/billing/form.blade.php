@extends('layouts.admin')

@section('title', 'Buat Invoice')
@section('page-title', 'Buat Invoice Baru')
@section('page-subtitle', 'Tambahkan item dan buat tagihan')

@push('styles')
    <style>
        .form-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
        }

        .form-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .form-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .form-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 14px;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .items-table th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
            color: var(--gray-500);
            background: var(--gray-50);
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid var(--gray-100);
        }

        .items-table input {
            width: 60px;
            padding: 6px;
            border: 1px solid var(--gray-200);
            border-radius: 4px;
            text-align: center;
        }

        .add-item-row {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .add-item-row select {
            flex: 1;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
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

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-danger {
            background: #EF4444;
            color: white;
        }

        .summary-card {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .summary-row.total {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            border-top: 2px solid var(--gray-300);
            padding-top: 12px;
            margin-top: 8px;
        }

        .form-footer {
            padding: 20px 24px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        @media (max-width: 1024px) {
            .form-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <form method="POST" action="{{ route('admin.billing.store') }}" id="invoiceForm">
        @csrf

        <div class="form-container">
            <div class="form-card">
                <div class="form-header">
                    <h3><i class="ri-receipt-line" style="color: var(--primary);"></i> Detail Invoice</h3>
                </div>
                <div class="form-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Invoice</label>
                            <input type="text" class="form-input" value="{{ $invoiceNumber }}" readonly
                                style="background: var(--gray-50);">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="invoice_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Pasien *</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">Pilih pasien...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $medicalRecord?->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="bpjs">BPJS</option>
                            </select>
                        </div>
                    </div>

                    @if($medicalRecord)
                        <input type="hidden" name="medical_record_id" value="{{ $medicalRecord->id }}">
                    @endif

                    <!-- Items -->
                    <h4 style="font-size: 14px; font-weight: 600; margin: 20px 0 12px;">Item Tagihan</h4>

                    <div class="add-item-row">
                        <select id="itemType" class="form-select" style="max-width: 150px;">
                            <option value="service">Layanan</option>
                            <option value="product">Obat/Produk</option>
                        </select>
                        <select id="itemSelect" class="form-select">
                            <option value="">Pilih item...</option>
                            @foreach($services as $service)
                                <option value="service-{{ $service->id }}" data-price="{{ $service->price }}"
                                    data-name="{{ $service->name }}">
                                    {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary btn-sm" onclick="addItem()">
                            <i class="ri-add-line"></i> Tambah
                        </button>
                    </div>

                    <table class="items-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="width: 100px;">Qty</th>
                                <th style="width: 150px;">Harga</th>
                                <th style="width: 150px;">Subtotal</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="form-card">
                <div class="form-header">
                    <h3>Ringkasan</h3>
                </div>
                <div class="form-body">
                    <div class="summary-card">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="subtotalDisplay">Rp 0</span>
                        </div>
                        <div class="summary-row">
                            <span>Diskon</span>
                            <input type="number" name="discount" id="discount" class="form-input" value="0" min="0"
                                style="width: 120px; text-align: right;" onchange="calculateTotal()">
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="totalDisplay">Rp 0</span>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-input" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="form-footer">
                    <a href="{{ route('admin.billing.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="ri-save-line"></i> Simpan Invoice</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let items = [];
        let itemIndex = 0;

        const services = @json($services);
        const inventories = @json($inventories);

        document.getElementById('itemType').addEventListener('change', function () {
            const type = this.value;
            const select = document.getElementById('itemSelect');
            select.innerHTML = '<option value="">Pilih item...</option>';

            const data = type === 'service' ? services : inventories;
            data.forEach(item => {
                const price = type === 'service' ? item.price : item.sell_price;
                select.innerHTML += `<option value="${type}-${item.id}" data-price="${price}" data-name="${item.name}">
                ${item.name} - Rp ${parseInt(price).toLocaleString('id-ID')}
            </option>`;
            });
        });

        function addItem() {
            const select = document.getElementById('itemSelect');
            const option = select.options[select.selectedIndex];

            if (!option.value) return;

            const [type, id] = option.value.split('-');
            const price = parseFloat(option.dataset.price);
            const name = option.dataset.name;

            const item = { type, id: parseInt(id), name, price, quantity: 1 };
            items.push(item);
            renderItems();
            select.value = '';
        }

        function renderItems() {
            const tbody = document.getElementById('itemsBody');
            tbody.innerHTML = '';

            items.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                tbody.innerHTML += `
                <tr>
                    <td>${item.name}
                        <input type="hidden" name="items[${index}][type]" value="${item.type}">
                        <input type="hidden" name="items[${index}][id]" value="${item.id}">
                    </td>
                    <td><input type="number" name="items[${index}][quantity]" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)"></td>
                    <td>Rp ${parseInt(item.price).toLocaleString('id-ID')}</td>
                    <td>Rp ${parseInt(subtotal).toLocaleString('id-ID')}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})"><i class="ri-delete-bin-line"></i></button></td>
                </tr>`;
            });
            calculateTotal();
        }

        function updateQuantity(index, qty) {
            items[index].quantity = parseInt(qty);
            renderItems();
        }

        function removeItem(index) {
            items.splice(index, 1);
            renderItems();
        }

        function calculateTotal() {
            const subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const total = subtotal - discount;

            document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
@endpush