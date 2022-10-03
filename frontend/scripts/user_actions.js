const favoriteUser = (id) => async () => {
  const response = await common.getAPI(
    `${common.baseURL}/favorites/${id}`,
    common.token
  )

  common.Console('Favorit', [response.message])

  common.refresh()
}

const blockUser = (id) => async () => {
  const response = await common.getAPI(
    `${common.baseURL}/blocks/${id}`,
    common.token
  )

  common.Console('Blocked', [response.message])

  common.refresh()
}

const chat_container = document.querySelector('.chat-container')

const getMessages = (receiver_id) => async () => {
  localStorage.setItem('receiver_id', receiver_id)
  const response = await common.getAPI(
    `${common.baseURL}/${common.userID}/${receiver_id}`,
    common.token
  )
  response.data.forEach((user) => {
    if (user.id == common.userID) {
      user.sender.forEach((message) => {
        chat_container.appendChild(
          createMessages('receiver', message.pivot.message)
        )
      })
    } else {
      user.sender.forEach((message) => {
        chat_container.appendChild(
          createMessages('sender', message.pivot.message)
        )
      })
    }
  })
}
