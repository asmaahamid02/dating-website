const common = {}

common.baseURL = 'http://127.0.0.1:8000/api/v0.1'

common.Console = (title, values) => {
  console.log('---' + title + '---')

  for (let value of values) {
    console.log(value)
  }

  console.log('--/' + title + '---')
}

common.getAPI = async (api_url) => {
  try {
    const data = await axios.get(api_url)
    return data.data
  } catch (error) {
    common.Console('Error from GET API', [error])
  }
}

common.postAPI = async (api_url, api_data) => {
  try {
    const response = await axios.post(api_url, api_data)
    return response.data
  } catch (error) {
    // common.Console('Error from POST API', [error])
    return error.response.data
  }
}

common.refresh = () => {
  setTimeout(function () {
    window.location.reload()
  }, 1000)
}

common.changeRoute = (page) => {
  setTimeout(function () {
    window.location.href = page
  }, 1000)
}
