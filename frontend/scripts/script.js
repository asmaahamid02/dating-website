const common = {}

common.baseURL = 'http://127.0.0.1:8000/api/v0.1'

common.Console = (title, values) => {
  console.log('---' + title + '---')

  for (let value of values) {
    console.log(value)
  }

  console.log('--/' + title + '---')
}

common.getAPI = async (api_url, token = null) => {
  try {
    const response = await axios.get(api_url, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    return response.data
  } catch (error) {
    return error.response.data
  }
}

common.postAPI = async (api_url, api_data, token = null) => {
  try {
    const response = await axios.post(api_url, api_data, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
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

common.userID = localStorage.getItem('__DateUser')
  ? JSON.parse(localStorage.getItem('__DateUser')).id
  : null

common.token = localStorage.getItem('__DateUser')
  ? JSON.parse(localStorage.getItem('__DateUser')).token
  : null

common.pathName = window.location.pathname
common.fileName = common.pathName.substring(
  common.pathName.lastIndexOf('/') + 1
)

if (
  !common.userID &&
  common.fileName != 'index.html' &&
  common.fileName != 'signup.html'
) {
  common.changeRoute('index.html')
}
