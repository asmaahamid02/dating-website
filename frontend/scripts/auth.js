const login_form = document.getElementById('login')
const signup_form = document.getElementById('signup')

const name_input = document.querySelector('#name')
const name_error_message = document.querySelector('#input-name')

const email = document.getElementById('email')
const email_error_message = document.getElementById('input-email')

const password = document.getElementById('password')
const password_error_message = document.getElementById('input-password')

const password_conf = document.querySelector('#password-confirm')
const password_conf_error_message = document.querySelector(
  '#input-password-confirm'
)

const gender = document.getElementById('gender')
const gender_error_message = document.getElementById('input-gender')

const interested_in = document.getElementById('interested_in')
const interested_in_error_message = document.getElementById('input-interested')

const country = document.getElementById('country')
const country_error_message = document.getElementById('input-country')

const city = document.getElementById('city')
const city_error_message = document.getElementById('input-city')

const success_message = document.querySelector('.success-message')
const error_message = document.querySelector('.error-message')

//stop the page from auto-reload
window.stop()

const login = () => {
  login_form.addEventListener('submit', async (e) => {
    e.preventDefault()
    //validate inputs
    if (
      validations.isNotEmpty(email, email_error_message, 'Email') &&
      validations.validEmail(email, email_error_message) &&
      validations.isNotEmpty(password, password_error_message, 'password') &&
      validations.validLength(password, 5, password_error_message, 'password')
    ) {
      const credentials = {
        email: email.value,
        password: password.value,
      }

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
        }

        //strore user data in localStorage
        localStorage.setItem('__DateUser', JSON.stringify(userData))

        //display success message
        success_message.innerText = response.message
        error_message.classList.add('hide')
        success_message.classList.remove('hide')

        //move to the homepage
        common.changeRoute('homepage.html')
      } else {
        //error
        if (response.data) {
          error_message.innerText = response.data
        } else {
          error_message.innerText = response.message
        }
        success_message.classList.add('hide')
        error_message.classList.remove('hide')
      }
    } else {
      //prevented
      success_message.classList.add('hide')
      error_message.classList.remove('hide')
      error_message.textContent = 'Fields are not valid'
      e.preventDefault()
    }
  })
}

const inputs = Array.from(
  document.querySelectorAll('.input-container > :first-child')
)

const error_messages = Array.from(document.querySelectorAll('.input-message'))

const signup = () => {
  signup_form.addEventListener('submit', async (e) => {
    e.preventDefault()
    //validate inputs
    let flag = true
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
          !validations.checkPasswordMatch(
            password,
            inputs[i],
            error_messages[i]
          ))
      ) {
        flag = false
        break
      }
    }

    if (!flag) {
      return
    } else {
      let userData = {}
      for (let j = 0; j < inputs.length; j++) {
        if (inputs[j].name == 'password-confirm') continue

        userData[inputs[j].name] = inputs[j].value
      }

      const response = await common.postAPI(
        `${common.baseURL}/register`,
        userData
      )

      if (response.statusCode < 400) {
        //success

        //display success message
        success_message.innerText = response.message
        error_message.classList.add('hide')
        success_message.classList.remove('hide')

        //move to the homepage
        common.changeRoute('index.html')
      } else {
        //error
        if (response.data && response.data.email) {
          error_message.innerText = response.data.email
        } else {
          error_message.innerText = response.message
        }
        success_message.classList.add('hide')
        error_message.classList.remove('hide')
      }
    }
  })
}

if (login_form) {
  //to remove the error marks upon writing
  validations.removeErrorMessages(login_form)
  login()
} else {
  //to remove the error marks upon writing
  validations.removeErrorMessages(signup_form)
  signup()
}
