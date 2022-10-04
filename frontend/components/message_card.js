const default_image = './assets/images/png/user-blank.png'
const profiles_path = '../backend/storage/app/public/users/profiles/'

const createUsers = (user, containers) => {
  //user container
  const newUser = document.createElement('div')
  newUser.classList.add('user')
  newUser.classList.add('flex')
  newUser.id = `${user.id}`

  let element = document.createElement('img')
  element.classList.add('round-image')
  element.classList.add('mr')
  element.src = user.profile
    ? `${profiles_path}${user.profile.profile_picture}`
    : `${default_image}`

  newUser.appendChild(element)

  element = document.createElement('h3')
  element.classList.add('username')
  element.innerText = `${user.name}`

  newUser.appendChild(element)

  newUser.addEventListener('click', () => getMessages(user, containers))

  return newUser
}

const createMessages = (className, text) => {
  const message = document.createElement('p')
  message.classList.add(className)
  message.innerText = text

  return message
}

const createSenderDiv = (text, imageName = null) => {
  const mainDiv = document.createElement('div')
  mainDiv.classList.add('sender')
  mainDiv.classList.add('flex')

  let element = document.createElement('p')
  element.innerText = text

  mainDiv.appendChild(element)

  element = document.createElement('img')
  element.classList.add('round-image')
  element.classList.add('ml')
  element.src = imageName ? `${profiles_path}${imageName}` : `${default_image}`

  mainDiv.appendChild(element)

  return mainDiv
}

const createReceiverDiv = (text, imageName = null) => {
  const mainDiv = document.createElement('div')
  mainDiv.classList.add('receiver')
  mainDiv.classList.add('flex')

  let element = document.createElement('img')
  element.classList.add('round-image')
  element.classList.add('mr')
  element.src = imageName ? `${profiles_path}${imageName}` : `${default_image}`

  mainDiv.appendChild(element)

  element = document.createElement('p')
  element.innerText = text

  mainDiv.appendChild(element)

  return mainDiv
}
