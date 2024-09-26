import axios from 'axios'

const useApi = axios.create({
  baseURL: import.meta.env.VITE_BASE_API_URL || 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json'
  }
})

export default useApi
