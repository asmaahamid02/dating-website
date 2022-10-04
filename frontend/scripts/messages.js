window.stop()

const usersContainer = document.querySelector('.users')
const headerImage = document.querySelector('.header > img')
const headerTitle = document.querySelector('.header > h3')
const send_button = document.querySelector('.btn-send')
const message = document.getElementById('message')
const chat_container = document.querySelector('.chat-container')

const containers = {
  main: usersContainer,
  chat: chat_container,
  image: headerImage,
  header: headerTitle,
}

//display users to message
getUsers(containers, 'message')

//display the messages of the saved user in local Storage
getMessagesOfClickedUser(containers)

//send message action
send_button.addEventListener('click', async () => {
  const receiver_id = JSON.parse(localStorage.getItem('receiver')).id
  const response = await common.postAPI(
    `${common.baseURL}/messages/send/${receiver_id}`,
    { message: message.value },
    common.token
  )
  common.refresh()
})
