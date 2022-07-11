const login = data => {
    return axios.post('login', data)
}

const setSession = data => {
    localStorage.setItem('user', JSON.stringify(data.user))
    localStorage.setItem('token', data.token)
}

const getSession = () => {
    const user = JSON.parse(localStorage.getItem('user'))
    const token = localStorage.getItem('token')
    return { user, token }
}

const getToken = () => {
    return localStorage.getItem('token')
}

const getUser = () => {
  return JSON.parse(localStorage.getItem('user'))
}

const updateUser = (user) => {
    localStorage.setItem('user', JSON.stringify(user))
}

const removeSession = () => {
    localStorage.removeItem('user')
    localStorage.removeItem('token')
}

const logout = () => {
    axios.post('logout')
        .finally(() => {
            removeSession()
        })
}

export default {
    login,
    logout,
    getUser,
    setSession,
    getSession,
    getToken,
    removeSession,
    updateUser
}
