import { ref } from 'vue'
import api from '@/services/api'

export const useApi = () => {
  const loading = ref(false)
  const error = ref(null)

  // 通用 GET 請求
  const get = async (url, params = {}) => {
    try {
      loading.value = true
      error.value = null
      const response = await api.get(url, { params })
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      console.error('GET Error:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  // 通用 POST 請求
  const post = async (url, data = {}) => {
    try {
      loading.value = true
      error.value = null
      const response = await api.post(url, data)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      console.error('POST Error:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  // 通用 PUT/PATCH
  const put = async (url, data = {}) => {
    const response = await api.put(url, data)
    return response.data
  }

  const patch = async (url, data = {}) => {
    const response = await api.patch(url, data)
    return response.data
  }

  // 通用 DELETE
  const del = async (url) => {
    const response = await api.delete(url)
    return response.data
  }

  return {
    loading,
    error,
    get,
    post,
    put,
    patch,
    delete: del
  }
}