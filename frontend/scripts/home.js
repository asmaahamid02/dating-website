window.stop()
const cards_container = document.querySelector('.cards-container')
const getUsers = async () => {
  const response = await common.getAPI(`${common.baseURL}/users`, common.token)

  response.data.forEach((user) => {
    cards_container.appendChild(createProfileCard(user))
  })
}

getUsers()
