import useApi from '@/utils/api'

class ApiServices {
  async getAll(url: string): Promise<any> {
    try {
      const response = await useApi.get(url)
      console.log('response', response)
      return response.status === 200 ? response.data : null
    } catch (error) {
      console.error(error)
      throw error
    }
  }
}

export default ApiServices
