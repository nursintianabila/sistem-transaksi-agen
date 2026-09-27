<script setup>
const props = defineProps({
  form: {
    type: Object,
    required: true,
  },

  sedangEdit: {
    type: Boolean,
    default: false,
  },

  processing: {
    type: Boolean,
    default: false,
  },

  message: {
    type: String,
    default: '',
  },

  error: {
    type: String,
    default: '',
  },
})

const emit = defineEmits([
  'submit',
  'reset',
])

const formatRupiah = (value) => {
  if (
    value === null ||
    value === undefined ||
    value === ''
  ) {
    return ''
  }

  const angka =
    String(value).replace(/\D/g, '')

  if (!angka) {
    return ''
  }

  return Number(angka).toLocaleString('id-ID')
}

const inputNominal = (event) => {
  const angka =
    event.target.value.replace(/\D/g, '')

  props.form.nominal = angka

  event.target.value =
    formatRupiah(angka)
}

const inputBiayaAdmin = (event) => {
  const angka =
    event.target.value.replace(/\D/g, '')

  props.form.biaya_admin = angka

  event.target.value =
    formatRupiah(angka)
}

const kirimForm = () => {
  emit('submit')
}

const resetForm = () => {
  emit('reset')
}
</script>

<template>
  <div class="form-card">

    <div class="form-header">

      <div>

        <h3>
          {{
            sedangEdit
              ? 'Edit Transaksi'
              : 'Tambah Transaksi'
          }}
        </h3>

        <p v-if="!sedangEdit">
          Masukkan data transaksi pelanggan.
        </p>

        <p v-else>
          Ubah data transaksi yang dipilih.
        </p>

      </div>

      <span
        v-if="sedangEdit"
        class="edit-badge"
      >
        Mode Edit
      </span>

    </div>

    <div
      v-if="message"
      class="alert success-alert"
    >
      {{ message }}
    </div>

    <div
      v-if="error"
      class="alert error-alert"
    >
      {{ error }}
    </div>

    <form @submit.prevent="kirimForm">

      <div class="form-grid">

        <div class="form-group">

          <label>
            Jenis Transaksi
          </label>

          <select
            v-model="form.jenis_transaksi"
            :disabled="processing"
            required
          >

            <option value="">
              Pilih jenis transaksi
            </option>

            <option value="Transfer">
              Transfer
            </option>

            <option value="Tarik Tunai">
              Tarik Tunai
            </option>

            <option value="Setor Tunai">
              Setor Tunai
            </option>

            <option value="Pembayaran">
              Pembayaran
            </option>

          </select>

        </div>

        <div class="form-group">

          <label>
            Nama Pelanggan
          </label>

          <input
            v-model="form.nama_pelanggan"
            type="text"
            placeholder="Masukkan nama pelanggan"
            :disabled="processing"
            required
          />

        </div>

        <div class="form-group">

          <label>
            No. Rekening / Pembayaran
          </label>

          <input
            v-model="form.nomor_tujuan"
            type="text"
            inputmode="numeric"
            placeholder="Masukkan nomor"
            :disabled="processing"
            required
          />

        </div>

        <div class="form-group">

          <label>
            Nominal
          </label>

          <div class="rupiah-input">

            <span>Rp</span>

            <input
              :value="formatRupiah(form.nominal)"
              type="text"
              inputmode="numeric"
              placeholder="0"
              :disabled="processing"
              @input="inputNominal"
              required
            />

          </div>

        </div>

        <div class="form-group">

          <label>
            Biaya Admin
          </label>

          <div class="rupiah-input">

            <span>Rp</span>

            <input
              :value="formatRupiah(form.biaya_admin)"
              type="text"
              inputmode="numeric"
              placeholder="0"
              :disabled="processing"
              @input="inputBiayaAdmin"
              required
            />

          </div>

        </div>

        <div class="form-group">

          <label>
            Tanggal Transaksi
          </label>

          <input
            v-model="form.tanggal_transaksi"
            type="date"
            :disabled="processing"
            required
          />

        </div>

      </div>

      <div class="form-actions">

        <button
          type="button"
          class="btn-reset"
          :disabled="processing"
          @click="resetForm"
        >
          {{
            sedangEdit
              ? 'Batal Edit'
              : 'Reset'
          }}
        </button>

        <button
          type="submit"
          class="btn-submit"
          :disabled="processing"
        >
          <template v-if="processing">
            Memproses...
          </template>

          <template v-else>
            {{
              sedangEdit
                ? 'Update Transaksi'
                : 'Simpan Transaksi'
            }}
          </template>
        </button>

      </div>

    </form>

  </div>
</template>

<style scoped>
.form-card {
  margin-top: 25px;

  padding: 25px;

  background-color: white;

  border-radius: 12px;

  box-shadow:
    0 2px 8px
    rgba(0, 0, 0, 0.06);
}

.form-header {
  display: flex;

  justify-content: space-between;

  align-items: center;

  gap: 15px;

  margin-bottom: 22px;
}

.form-header h3 {
  margin: 0;

  color: #1f2937;

  font-size: 18px;
}

.form-header p {
  margin: 6px 0 0;

  color: #6b7280;

  font-size: 13px;
}

.edit-badge {
  padding: 6px 11px;

  background-color: #fef3c7;

  color: #92400e;

  border-radius: 20px;

  font-size: 12px;

  font-weight: 600;
}

.form-grid {
  display: grid;

  grid-template-columns:
    repeat(2, minmax(0, 1fr));

  gap: 20px;
}

.form-group {
  display: flex;

  flex-direction: column;

  gap: 7px;
}

.form-group label {
  color: #374151;

  font-size: 13px;

  font-weight: 600;
}

.form-group input,
.form-group select {
  width: 100%;

  padding: 11px 12px;

  border:
    1px solid #d1d5db;

  border-radius: 8px;

  background-color: white;

  color: #1f2937;

  font-size: 14px;

  outline: none;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #2563eb;

  box-shadow:
    0 0 0 3px
    rgba(37, 99, 235, 0.1);
}

.rupiah-input {
  display: flex;

  align-items: center;

  border:
    1px solid #d1d5db;

  border-radius: 8px;

  overflow: hidden;

  background-color: white;
}

.rupiah-input:focus-within {
  border-color: #2563eb;

  box-shadow:
    0 0 0 3px
    rgba(37, 99, 235, 0.1);
}

.rupiah-input span {
  padding: 11px 12px;

  background-color: #f3f4f6;

  color: #4b5563;

  font-size: 14px;

  font-weight: 600;

  border-right:
    1px solid #d1d5db;
}

.rupiah-input input {
  border: none;

  border-radius: 0;

  box-shadow: none;
}

.rupiah-input input:focus {
  border: none;

  box-shadow: none;
}

.form-actions {
  display: flex;

  justify-content: flex-end;

  gap: 10px;

  margin-top: 24px;
}

.form-actions button {
  padding: 10px 18px;

  border: none;

  border-radius: 8px;

  cursor: pointer;

  font-size: 14px;

  font-weight: 600;
}

.btn-reset {
  background-color: #e5e7eb;

  color: #374151;
}

.btn-submit {
  background-color: #2563eb;

  color: white;
}

.btn-submit:hover:not(:disabled) {
  background-color: #1d4ed8;
}

.btn-reset:hover:not(:disabled) {
  background-color: #d1d5db;
}

button:disabled,
input:disabled,
select:disabled {
  cursor: not-allowed;

  opacity: 0.65;
}

.alert {
  margin-bottom: 20px;

  padding: 12px 15px;

  border-radius: 8px;

  font-size: 13px;
}

.success-alert {
  background-color: #ecfdf5;

  color: #047857;

  border:
    1px solid #a7f3d0;
}

.error-alert {
  background-color: #fef2f2;

  color: #b91c1c;

  border:
    1px solid #fecaca;
}

@media (max-width: 700px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-header {
    align-items: flex-start;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .form-actions button {
    width: 100%;
  }
}
</style>