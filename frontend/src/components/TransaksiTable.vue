<script setup>
defineProps({
  transaksi: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits([
  'edit',
  'hapus',
])

const formatRupiah = (value) => {
  return Number(value || 0).toLocaleString('id-ID')
}
</script>

<template>
  <div class="table-card">

    <div class="table-header">

      <div>
        <h3>Daftar Transaksi</h3>

        <p>
          Daftar seluruh transaksi yang telah tersimpan.
        </p>
      </div>

      <div class="jumlah-data">
        {{ transaksi.length }} Transaksi
      </div>

    </div>

    <div class="table-responsive">

      <table>

        <thead>
          <tr>
            <th>No</th>
            <th>Jenis Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>No. Rekening / Pembayaran</th>
            <th>Nominal</th>
            <th>Biaya Admin</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>

          <tr
            v-for="(item, index) in transaksi"
            :key="item.id"
          >

            <td>
              {{ index + 1 }}
            </td>

            <td>
              <span class="jenis-badge">
                {{ item.jenis_transaksi || '-' }}
              </span>
            </td>

            <td>
              {{ item.nama_pelanggan || '-' }}
            </td>

            <td>
              {{ item.nomor_tujuan || '-' }}
            </td>

            <td>
              Rp {{ formatRupiah(item.nominal) }}
            </td>

            <td>
              Rp {{ formatRupiah(item.biaya_admin) }}
            </td>

            <td class="total-column">
              Rp {{ formatRupiah(item.total) }}
            </td>

            <td>
              {{ item.tanggal_transaksi || '-' }}
            </td>

            <td>

              <div class="action-buttons">

                <button
                  type="button"
                  class="btn-edit"
                  @click="emit('edit', item)"
                >
                  Edit
                </button>

                <button
                  type="button"
                  class="btn-delete"
                  @click="emit('hapus', item)"
                >
                  Hapus
                </button>

              </div>

            </td>

          </tr>

          <tr v-if="transaksi.length === 0">

            <td
              colspan="9"
              class="empty-data"
            >
              Belum ada transaksi yang tersimpan.
            </td>

          </tr>

        </tbody>

      </table>

    </div>

  </div>
</template>

<style scoped>
.table-card {
  margin-top: 25px;
  padding: 25px;

  background-color: white;

  border-radius: 12px;

  box-shadow:
    0 2px 8px
    rgba(0, 0, 0, 0.06);
}

.table-header {
  display: flex;

  justify-content: space-between;
  align-items: center;

  gap: 20px;

  margin-bottom: 20px;
}

.table-header h3 {
  margin: 0;

  color: #1f2937;

  font-size: 18px;
}

.table-header p {
  margin: 6px 0 0;

  color: #6b7280;

  font-size: 13px;
}

.jumlah-data {
  padding: 7px 12px;

  background-color: #eff6ff;

  color: #2563eb;

  border-radius: 20px;

  font-size: 12px;

  font-weight: 600;

  white-space: nowrap;
}

.table-responsive {
  width: 100%;

  overflow-x: auto;
}

table {
  width: 100%;

  min-width: 1180px;

  border-collapse: collapse;
}

thead {
  background-color: #2563eb;

  color: white;
}

th {
  padding: 13px 14px;

  text-align: left;

  font-size: 12px;

  font-weight: 600;

  white-space: nowrap;
}

th:first-child {
  border-radius: 8px 0 0 8px;
}

th:last-child {
  border-radius: 0 8px 8px 0;
}

td {
  padding: 14px;

  border-bottom:
    1px solid #e5e7eb;

  color: #374151;

  font-size: 13px;

  white-space: nowrap;
}

tbody tr {
  transition: background-color 0.2s;
}

tbody tr:hover {
  background-color: #f8fafc;
}

.jenis-badge {
  display: inline-block;

  padding: 5px 9px;

  background-color: #eff6ff;

  color: #1d4ed8;

  border-radius: 6px;

  font-size: 12px;

  font-weight: 600;
}

.total-column {
  color: #047857;

  font-weight: 700;
}

.action-buttons {
  display: flex;

  gap: 7px;
}

.action-buttons button {
  padding: 7px 11px;

  border: none;

  border-radius: 6px;

  cursor: pointer;

  font-size: 12px;

  font-weight: 600;
}

.btn-edit {
  background-color: #fef3c7;

  color: #92400e;
}

.btn-edit:hover {
  background-color: #fde68a;
}

.btn-delete {
  background-color: #fee2e2;

  color: #b91c1c;
}

.btn-delete:hover {
  background-color: #fecaca;
}

.empty-data {
  padding: 35px;

  text-align: center;

  color: #9ca3af;
}

@media (max-width: 700px) {
  .table-card {
    padding: 18px;
  }

  .table-header {
    flex-direction: column;

    align-items: flex-start;
  }
}
</style>