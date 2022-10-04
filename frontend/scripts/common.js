const getUsers = async (containers, page = 'home') => {
  const response = await common.getAPI(`${common.baseURL}/users`, common.token)
  if (page === 'message' && !localStorage.getItem('receiver')) {
    //save the fisrt user id to retreive his/her messages
    receiver = {
      id: response.data[0].id,
      name: response.data[0].name,
      picture: response.data[0].profile
        ? response.data[0].profile.profile_picture
        : null,
    }
    localStorage.setItem('receiver', JSON.stringify(receiver))
  }
  containers.main.innerHTML = ''
  response.data.forEach((user) => {
    if (page == 'home') {
      containers.main.appendChild(createProfileCard(user))
    } else if (page == 'message') {
      containers.main.appendChild(createUsers(user, containers))
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

const getMessages = async (user, containers) => {
  containers.chat.innerHTML = ''
  const receiverImage = user.profile ? user.profile.profile_picture : null
  receiver = {
    id: user.id,
    name: user.name,
    picture: receiverImage,
  }
  localStorage.setItem('receiver', JSON.stringify(receiver))

  const response = await common.getAPI(
    `${common.baseURL}/messages/${user.id}`,
    common.token
  )

  containers.image.src = receiverImage
    ? `${profiles_path}${receiverImage}`
    : `${default_image}`

  containers.header.innerText = user.name ? `${user.name}` : `Username`

  if (!response.data) {
    containers.chat.innerHTML = '<p class="no-data">No Messages</p>'
    return
  }
  response.data.forEach((message) => {
    if (message.sender_id == common.userID) {
      containers.chat.appendChild(
        createSenderDiv(message.message, common.userProfile)
      )
    } else {
      containers.chat.appendChild(
        createReceiverDiv(message.message, receiverImage)
      )
    }
  })

  // common.Console('Messages', response.data)
}

const getMessagesOfClickedUser = async (containers) => {
  const receiver = JSON.parse(localStorage.getItem('receiver'))
  user = {
    id: receiver.id,
    name: receiver.name,
    profile: {
      profile_picture: receiver.picture,
    },
  }
  return getMessages(user, containers)
}

const logout = document.getElementById('logout')

logout.addEventListener('click', (e) => {
  e.preventDefault()
  localStorage.removeItem('__DateUser')
  localStorage.removeItem('receiver')
  common.refresh(500)
})
