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
