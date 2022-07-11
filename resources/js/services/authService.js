const login = data => {
    return axios.post('login', data)
}

const setSession = data => {
    localStorage.setItem('session', JSON.stringify(data))
}

const getSession = () => JSON.parse(sessionStorage.getItem('session'))

const getToken = () => {
    const session = localStorage.getItem('session')
    console.log(session)
    if (!session) {
        return null
    }
    console.log(session.token)
    return session.token
}

const getUser = () => {
    const session = getSession()
    if (!session) {
        return null
    }

    return session.user
}

const removeSession = () => localStorage.removeItem('session')

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
    removeSession
}
