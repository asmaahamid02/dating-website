window.stop()

const usersContainer = document.querySelector('.users')

const getUsers = async () => {
  const response = await common.getAPI(`${common.baseURL}/users`, common.token)

  response.data.forEach((user) => {
    usersContainer.appendChild(createUsers(user))
  })
}

getUsers()
