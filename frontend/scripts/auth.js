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

if (login_form) {
  //to remove the error marks upon writing
  validations.removeErrorMessages(login_form)
  login()
} else {
  //to remove the error marks upon writing
  validations.removeErrorMessages(signup_form)
  signup()
}
