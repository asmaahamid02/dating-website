window.stop()

const usersContainer = document.querySelector('.users')
const send_button = document.querySelector('.btn-send')
const message = document.getElementById('message')
const getUsers = async () => {
  const response = await common.getAPI(`${common.baseURL}/users`, common.token)

  response.data.forEach((user) => {
    usersContainer.appendChild(createUsers(user))
  })
}

getUsers()

send_button.addEventListener('click', async () => {
  receiver_id = JSON.parse(localStorage.getItem('receiver_id'))
  const response = await common.postAPI(
    `${common.baseURL}/messages/send/${receiver_id}`,
    { message: message.value },
    common.token
  )

  common.refresh()
})
