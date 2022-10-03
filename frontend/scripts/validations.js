const validations = {}

const addClasses = (input, error, isError = true) => {
  if (isError) {
    input.classList.add('red-border')
    error.classList.remove('hide')
  } else {
    input.classList.remove('red-border')
    error.classList.add('hide')
  }
}

validations.isNotEmpty = (input, error_holder, title) => {
  if (input.value.length > 0) {
    addClasses(input, error_holder, false)
    return true
  } else {
    addClasses(input, error_holder)
    error_holder.innerText = `${title} should not be empty!`
    return false
  }
}

validations.validEmail = (email, error_holder) => {
  const pattern = /^\w{3,}@\w{3,}\.\w{2,}$/
  const result = email.value.search(pattern)
  if (result == -1) {
    addClasses(email, error_holder)
    error_holder.innerText = `Email not valid`
    return false
  } else {
    addClasses(email, error_holder, false)
    return true
  }
}

validations.validLength = (input, length, error_holder, title) => {
  if (input.value.length < length) {
    addClasses(input, error_holder)
    error_holder.innerText = `${title} should be at least ${length} characters`
    return false
  } else {
    addClasses(input, error_holder, false)
    return true
  }
}

validations.strongPassword = (password, error_holder) => {
  let one_upper = /(?=.*?[A-Z])/
  let one_lower = /(?=.*?[a-z])/
  let one_digit = /(?=.*?[0-9])/
  let min = /.{8,}/
  let text = ''
  if (password.value.search(one_upper) == -1) {
    text = `Password should have at least one upper letter`
    addClasses(password, error_holder)
    error_holder.innerText = text
    return false
  }

  if (password.value.search(one_lower) == -1) {
    text = `Password should have at least one lower letter`
    addClasses(password, error_holder)
    error_holder.innerText = text
    return false
  }

  if (password.value.search(one_digit) == -1) {
    text = `Password should have at least one number`
    addClasses(password, error_holder)
    error_holder.innerText = text
    return false
  }

  if (password.value.search(min) == -1) {
    text = `Password should be at least 8 characters`
    addClasses(password, error_holder)
    error_holder.innerText = text
    return false
  }

  if (text == '') {
    addClasses(password, error_holder, false)
    return true
  }
}

validations.checkPasswordMatch = (password, confirm, error_holder) => {
  if (password.value === confirm.value) {
    addClasses(confirm, error_holder, false)
    return true
  } else {
    addClasses(confirm, error_holder)
    error_holder.innerText = `Passwords are not matching`
    return false
  }
}

validations.removeErrorMessages = (parent) => {
  const inputs = Array.from(parent.querySelectorAll('.input'))
  const errors = Array.from(parent.querySelectorAll('.input-message'))
  for (let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('input', () => {
      errors[i].textContent = ''
    })
  }
}
