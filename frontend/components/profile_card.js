const default_image = './assets/images/png/user-blank.png'
const profiles_path = '../backend/storage/app/public/users/profiles/'
const createProfileCard = (user) => {
  //card container
  let is_favorited = false

  //check if the logged user favorites $this user through relationship
  if (user.favoritor) {
    for (let row of user.favoritor) {
      if (row.id == common.userID) {
        is_favorited = true
        break
      }
    }
  }

  //check if the logged user favorites $this user through pivot table
  if (
    user.favorites &&
    user.favorites.user_id &&
    user.favorites.user_id == common.userID
  ) {
    is_favorited = true
  }

  let is_blocked = false

  if (
    user.blocks &&
    user.blocks.user_id &&
    user.blocks.user_id == common.userID
  ) {
    is_blocked = true
  }

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

  //location section
  element = document.createElement('p')
  element.innerText = `${user.country} - ${user.city}`
  element.classList.add('bio')
  newCard.appendChild(element)

  element = document.createElement('div')
  element.classList.add('actions')

  //favorite button
  let newElement = document.createElement('button')
  newElement.classList.add('btn')
  newElement.classList.add('btn-fav')

  let child = document.createElement('img')
  if (is_favorited) {
    child.src = './assets/images/svg/filled-heart-svgrepo-com.svg'
  } else {
    child.src = './assets/images/svg/heart-svgrepo-com.svg'
  }

  newElement.appendChild(child)

  //favorite action
  newElement.addEventListener('click', favoriteUser(`${user.id}`, child))

  element.appendChild(newElement)

  //block button
  newElement = document.createElement('button')
  newElement.classList.add('btn')
  newElement.classList.add('btn-block')

  child = document.createElement('img')

  if (is_blocked) {
    child.src = './assets/images/svg/unblock-svgrepo-com.svg'
  } else {
    child.src = './assets/images/svg/block-svgrepo-com.svg'
  }

  newElement.appendChild(child)

  //block action
  newElement.addEventListener('click', blockUser(`${user.id}`))

  element.appendChild(newElement)

  newCard.appendChild(element)

  return newCard
}
