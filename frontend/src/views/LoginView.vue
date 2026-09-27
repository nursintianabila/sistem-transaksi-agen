<script setup>
import { ref } from 'vue'
import api from '../services/api'

const emit = defineEmits(['login-success'])

const email = ref('')
const password = ref('')
const error = ref('')
const processing = ref(false)

const login = async () => {
  error.value = ''

  if (!email.value || !password.value) {
    error.value = 'Email dan password wajib diisi.'
    return
  }

  processing.value = true

  try {
    const response = await api.post('/login', {
      email: email.value,
      password: password.value,
    })

    const token = response.data.data.token
    const user = response.data.data.user

    // Simpan token untuk request berikutnya
    localStorage.setItem('token', token)

    // Simpan data user sederhana
    localStorage.setItem('user', JSON.stringify(user))

    emit('login-success', user)
  } catch (err) {
    if (err.response?.status === 401) {
      error.value = 'Email atau password salah.'
    } else if (err.response?.status === 422) {
      error.value = 'Email dan password wajib diisi dengan benar.'
    } else {
      error.value = 'Terjadi kesalahan saat login.'
    }
  } finally {
    processing.value = false
  }
}
</script>

<template>
  <div class="login-page">
    <div class="login-card">
      <div class="login-header">
        <h1>Sistem Transaksi Agen</h1>
        <p>Silakan login untuk masuk ke dashboard.</p>
      </div>

      <div
        v-if="error"
        class="login-alert"
      >
        {{ error }}
      </div>

      <form @submit.prevent="login">
        <div class="login-group">
          <label for="email">
            Email
          </label>

          <input
            id="email"
            v-model="email"
            type="email"
            placeholder="Masukkan email"
            autocomplete="email"
            :disabled="processing"
          />
        </div>

        <div class="login-group">
          <label for="password">
            Password
          </label>

          <input
            id="password"
            v-model="password"
            type="password"
            placeholder="Masukkan password"
            autocomplete="current-password"
            :disabled="processing"
          />
        </div>

        <button
          type="submit"
          class="login-button"
          :disabled="processing"
        >
          {{ processing ? 'Memproses...' : 'Login' }}
        </button>
      </form>

      <div class="login-footer">
        Mini Project UTS
      </div>
    </div>
  </div>
</template>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 24px;
  background:
    linear-gradient(
      135deg,
      #f3f4f6 0%,
      #e5e7eb 100%
    );
}

.login-card {
  width: 100%;
  max-width: 430px;
  background: white;
  border-radius: 16px;
  padding: 32px;
  box-shadow:
    0 10px 30px rgba(0, 0, 0, 0.1);
}

.login-header {
  text-align: center;
  margin-bottom: 28px;
}

.login-header h1 {
  margin: 0 0 8px;
  font-size: 26px;
  color: #111827;
}

.login-header p {
  margin: 0;
  color: #6b7280;
  font-size: 14px;
}

.login-group {
  margin-bottom: 18px;
}

.login-group label {
  display: block;
  margin-bottom: 7px;
  font-weight: 600;
  color: #374151;
}

.login-group input {
  box-sizing: border-box;
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
}

.login-group input:focus {
  border-color: #2563eb;
}

.login-group input:disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.login-button {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  background: #2563eb;
  color: white;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
}

.login-button:hover:not(:disabled) {
  background: #1d4ed8;
}

.login-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.login-alert {
  margin-bottom: 18px;
  padding: 11px 13px;
  border-radius: 8px;
  background: #fee2e2;
  color: #991b1b;
  font-size: 14px;
}

.login-footer {
  margin-top: 24px;
  text-align: center;
  color: #9ca3af;
  font-size: 13px;
}
</style>