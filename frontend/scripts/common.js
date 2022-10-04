const getUsers = async (container, page = 'home', chatContainer = null) => {
  const response = await common.getAPI(`${common.baseURL}/users`, common.token)
  response.data.forEach((user) => {
    if (page == 'home') {
      container.appendChild(createProfileCard(user))
    } else if (page == 'message') {
      container.appendChild(createUsers(user, chatContainer))
    }
  })
}

const getFavoriteUsers = async (container) => {
  const response = await common.getAPI(
    `${common.baseURL}/favorites`,
    common.token
  )

  if (!response.data) {
    container.innerHTML = `<h3>${response.message}</h3>`
    return
  }
  //   common.Console('Favorites', response.data)
  response.data.forEach((user) => {
    container.appendChild(createProfileCard(user))
  })
}

const getBlockedUsers = async (container) => {
  const response = await common.getAPI(`${common.baseURL}/blocks`, common.token)

  if (!response.data) {
    container.innerHTML = `<h3>${response.message}</h3>`
    return
  }
  //   common.Console('Blocks', response.data)
  response.data.forEach((user) => {
    container.appendChild(createProfileCard(user))
  })
}

const favoriteUser = (id, element) => async () => {
  const response = await common.getAPI(
    `${common.baseURL}/favorites/${id}`,
    common.token
  )
  common.refresh()
}

const blockUser = (id) => async () => {
  const response = await common.getAPI(
    `${common.baseURL}/blocks/${id}`,
    common.token
  )
  common.refresh()
}

const getMessages = (receiver_id, container) => async () => {
  container.innerHTML = ''
  localStorage.setItem('receiver_id', receiver_id)
  const response = await common.getAPI(
    `${common.baseURL}/messages/${common.userID}/${receiver_id}`,
    common.token
  )
  if (!response.data) {
    container.innerHTML = '<h1>No Messages</h1>'
    return
  }
  response.data.forEach((user) => {
    if (user.id == common.userID) {
      user.sender.forEach((message) => {
        container.appendChild(createMessages('receiver', message.pivot.message))
      })
    } else {
      user.sender.forEach((message) => {
        container.appendChild(createMessages('sender', message.pivot.message))
      })
    }
  })
}
