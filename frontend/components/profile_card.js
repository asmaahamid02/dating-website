const default_image = './assets/images/png/user-blank.png'
const profiles_path = '../backend/storage/app/public/users/profiles/'
const createProfileCard = (user) => {
  //card container
  const newCard = document.createElement('div')
  newCard.classList.add('card')
  newCard.id = `${user.id}`

  //profile image
  let element = document.createElement('img')
  if (user.profile) {
    element.src = `${profiles_path}${user.profile.profile_picture}`
  } else {
    element.src = `${default_image}`
  }

  //listner to show profile from image
  element.addEventListener('click', () => {})
  newCard.appendChild(element)

  //name of user
  element = document.createElement('h3')
  element.innerText = `${user.name}`

  //listner to show profile from name
  element.addEventListener('click', () => {})
  newCard.appendChild(element)

  //bio section
  element = document.createElement('p')
  element.innerText = `${user.profile ? user.profile.bio : ''}`
  element.classList.add('bio')
  newCard.appendChild(element)

  element = document.createElement('div')
  element.classList.add('actions')

  //favorite button
  let newElement = document.createElement('button')
  newElement.classList.add('btn')
  newElement.classList.add('btn-fav')

  let child = document.createElement('img')
  child.src = './assets/images/svg/favorite-svgrepo-com.svg'

  newElement.appendChild(child)

  //favorite action
  newElement.addEventListener('click', favoriteUser(`${user.id}`))

  element.appendChild(newElement)

  //block button
  newElement = document.createElement('button')
  newElement.classList.add('btn')
  newElement.classList.add('btn-block')

  child = document.createElement('img')
  child.src = './assets/images/svg/block-svgrepo-com.svg'

  newElement.appendChild(child)

  //block action
  newElement.addEventListener('click', blockUser(`${user.id}`))

  element.appendChild(newElement)

  newCard.appendChild(element)

  return newCard
}
