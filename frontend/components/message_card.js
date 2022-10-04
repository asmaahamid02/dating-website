const createUsers = (user, chatContainer) => {
  //user container
  const newUser = document.createElement('div')
  newUser.classList.add('user')
  newUser.id = `${user.id}`

  let element = document.createElement('h3')
  element.innerText = `${user.name}`

  element.addEventListener('click', getMessages(user.id, chatContainer))
  newUser.appendChild(element)

  element = document.createElement('hr')
  newUser.appendChild(element)

  return newUser
}

const createMessages = (className, text) => {
  const message = document.createElement('p')
  message.classList.add(className)
  message.innerText = text

  return message
}
