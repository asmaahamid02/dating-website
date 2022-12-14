const login_form = document.getElementById('login')
const signup_form = document.getElementById('signup')
const edit_profile_form = document.getElementById('edit-profile')
const password = document.getElementById('password')
const success_message = document.querySelector('.success-message')
const error_message = document.querySelector('.error-message')
const inputs = Array.from(
  document.querySelectorAll('.input-container > :first-child')
)
if (edit_profile_form) {
  inputs.push(document.getElementById('is_visible'))
}

const error_messages = Array.from(document.querySelectorAll('.input-message'))

//stop the page from auto-reload
window.stop()

//function used to check if the forms fields are valid
const validateInputs = () => {
  let isValid = true
  for (let i = 0; i < inputs.length; i++) {
    if (
      !validations.isNotEmpty(inputs[i], error_messages[i], inputs[i].name) ||
      (inputs[i].name == 'password' &&
        !validations.strongPassword(
          inputs[i],
          error_messages[i],
          inputs[i].name
        )) ||
      (inputs[i].name == 'email' &&
        !validations.validEmail(inputs[i], error_messages[i])) ||
      (inputs[i].name == 'password-confirm' &&
        !validations.checkPasswordMatch(password, inputs[i], error_messages[i]))
    ) {
      isValid = false
      break
    }
  }
  return isValid
}

//function to display success message
const displaySuccessMessage = (message) => {
  //display success message
  success_message.innerText = message
  error_message.classList.add('hide')
  success_message.classList.remove('hide')
}

//function to display error message
const displayErrorMessage = (message) => {
  //display error message
  error_message.textContent = message
  success_message.classList.add('hide')
  error_message.classList.remove('hide')
}

//login funcction
const login = () => {
  login_form.addEventListener('submit', async (e) => {
    e.preventDefault()
    //validate inputs
    if (validateInputs()) {
      const credentials = new FormData(login_form)

      //send request to login api
      const response = await common.postAPI(
        `${common.baseURL}/login`,
        credentials
      )

      //if the request succeed
      if (response.statusCode < 400) {
        //success
        //get the user data
        const userData = {
          id: response.data.id,
          name: response.data.name,
          picture: response.data.profile
            ? response.data.profile.profile_picture
            : null,
          token: response.data.token,
        }
        //strore user data in localStorage
        localStorage.setItem('__DateUser', JSON.stringify(userData))

        //display success message
        displaySuccessMessage(response.message)

        //move to the homepage
        common.changeRoute('homepage.html')
      } else {
        //error
        displayErrorMessage(response.data ? response.data : response.message)
      }
    } else {
      //prevented
      displayErrorMessage('Fields are not valid')
      e.preventDefault()
    }
  })
}

//signup function
const signup = () => {
  signup_form.addEventListener('submit', async (e) => {
    e.preventDefault()
    //validate inputs

    if (!validateInputs()) {
      //prevented
      displayErrorMessage('Fields are not valid')
      e.preventDefault()
    } else {
      let userData = new FormData(signup_form)
      const response = await common.postAPI(
        `${common.baseURL}/register`,
        userData
      )

      if (response.statusCode < 400) {
        //success
        displaySuccessMessage(response.message)

        //move to the login page
        common.changeRoute('index.html')
      } else {
        //error
        displayErrorMessage(
          response.data && response.data.email
            ? response.data.email
            : response.message
        )
      }
    }
  })
}

//get the base64 of the chosen image
let base64_image
if (edit_profile_form) {
  document.getElementById('profile_picture').onchange = function () {
    const reader = new FileReader()
    reader.onload = () => {
      const image = reader.result
      // document.getElementById('image-element').src = image
      base64_image = image
    }
    console.log(reader.files)
    reader.readAsDataURL(this.files[0])
  }
}

const updateUserInLocalStorage = (item, key, newValue) => {
  // Get the existing data
  var existing = localStorage.getItem(item)

  // If no existing data, create an array
  // Otherwise, convert the localStorage string to an array
  existing = existing ? JSON.parse(existing) : {}

  // Add new data to localStorage Array
  existing[key] = newValue

  // Save back to localStorage
  localStorage.setItem(item, JSON.stringify(existing))
}

// edit profile function
const editProfile = () => {
  edit_profile_form.addEventListener('submit', async (e) => {
    e.preventDefault()
    //validate inputs

    // if (!validateInputs()) {
    //   //prevented
    //   displayErrorMessage('Fields are not valid')
    //   e.preventDefault()
    // } else {
    let userData = new FormData(edit_profile_form)
    document.getElementById('profile_picture').files.length
      ? userData.set('profile_picture', base64_image)
      : userData.delete('profile_picture')
    userData.set(
      'is_visible',
      document.getElementById('is_visible').checked ? 1 : 0
    )

    console.log([...userData])
    const response = await common.postAPI(
      `${common.baseURL}/users/edit`,
      userData,
      common.token
    )

    console.log(response)
    if (response.statusCode < 400) {
      //success
      displaySuccessMessage(response.message)

      updateUserInLocalStorage(
        '__DateUser',
        'picture',
        response.data.profile_picture
      )
      common.refresh()
    } else {
      //error
      displayErrorMessage(response.data ? response.data : response.message)
    }
    // }
  })
}

if (login_form) {
  //to remove the error marks upon writing
  validations.removeErrorMessages(login_form)
  login()
} else if (signup_form) {
  //to remove the error marks upon writing
  validations.removeErrorMessages(signup_form)
  signup()
} else {
  //to remove the error marks upon writing
  validations.removeErrorMessages(edit_profile_form)
  editProfile()
}

const fillEditForm = async () => {
  const response = await common.getAPI(
    `${common.baseURL}/users/${common.userID}`,
    common.token
  )

  if (response.statusCode < 400) {
    inputs.forEach((input) => {
      //get the value
      const inputValue = response.data[input.name]
        ? response.data[input.name]
        : response.data.profile[input.name]

      //fill the inputs (texts)
      if (
        (input.classList.contains('input') || input.id == 'is_visible') &&
        input.type != 'file'
      ) {
        input.value = inputValue
      }

      //check checkbox
      if (input.type == 'checkbox') {
        input.checked = inputValue
      }

      //selected option
      if (input.name == 'gender' || input.name == 'interested_in') {
        const selectedIndex = document.querySelector(
          `#${input.id} option[value="${inputValue}"]`
        ).index
        input.options.selectedIndex = selectedIndex
      }
    })
  }
}

if (edit_profile_form) fillEditForm()
